<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Member extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

     public function __construct(){
        parent::__construct();
        //load model divisi_model,nama objeknya = divisi
        $this->load->model('member_model','member');
        $this->load->model('transaksi_model','transaksi');
        $this->load->model('admin_model','admin');
        $this->load->model('voucher_model','voucher');
        $this->load->model('VoucherMember_model','vouchermember');
    }
    private function _has_login()
    {
        if (!$this->session->has_userdata('login_session')) {
            redirect('auth');
        }
    }
	public function index()
	{
	    $this->_has_login();
        $data['title'] = "Data Member";
        // Make sure we're selecting profile_pic field
        $data['members'] = $this->db->select('id, name, phone_number, email, balance, poin, 
                                        profile_pic, registration_time')
                               ->from('users')
                               ->order_by('name', 'ASC')
                               ->get()
                               ->result_array();
    
        // Debug to check if profile_pic is present in the data
        // echo '<pre>'; print_r($data['members']); die;
    
        $this->template->load('templates/dashboard', 'member/index', $data);
	}
    public function indexKasir()
{
    $this->_has_login();
    $data['title'] = "Member Management";
    $data['members'] = $this->member->find_all();
    $this->template->load('templates/kasir', 'member/indexKasir', $data);
}
	public function indexCabang()
	{
        $data['title'] = "Member Management";
        $data['members'] = $this->member->find_all();
		$this->template->load('templates/cabang', 'member/indexCabang', $data);
	}
    public function tambah() {
        $this->_has_login();
        $data['title'] = "Tambah Member";
        $this->template->load('templates/dashboard', 'member/add', $data);
    }
    public function tambahKasir() {
        $this->_has_login();
        $data['title'] = "Tambah Member";
        $this->template->load('templates/kasir', 'member/addKasir', $data);
    }
    public function tambahCabang() {
        $data['title'] = "Tambah Member";
        $this->template->load('templates/cabang', 'member/addCabang', $data);
    }
    public function tambah_save() {
        $this->form_validation->set_rules('namamember', 'Nama Member', 'required|trim');
        $this->form_validation->set_rules('nomor', 'Nomor Handphone', 'required|trim|is_unique[users.phone_number]');
    
        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Member";
            $this->template->load('templates/dashboard', 'member/add', $data);
        } else {
            $data = [
                'name' => $this->input->post('namamember', true),
                'phone_number' => $this->input->post('nomor', true),
                'balance' => 0,
                'poin' => 0,
                'registration_time' => date('Y-m-d H:i:s')
            ];
    
            if ($this->member->insert($data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Member berhasil ditambahkan!</div>');
                redirect('member');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Member gagal ditambahkan!</div>');
                redirect('member/add');
            }
        }
    }
    public function tambah_save_kasir()
    {
        $this->form_validation->set_rules("namamember","Nama Member","required|trim");
        $this->form_validation->set_rules("nomor","Nomor","required|trim|callback_check_unique_number|min_length[11]");
        if($this->form_validation->run() == false){
            $this->_has_login();
            $data['title'] = "Tambah Member";
            $this->template->load('templates/kasir', 'member/addKasir', $data);
        }else{
            $nomor = $this->input->post("nomor");
            $namamember = $this->input->post("namamember");
            $poin = 0;
            $data = array(
                'nomor' => $nomor,
                'namamember' => $namamember,
                'poin' => $poin,
                'foto' => 'hero.png',
                'tanggaldaftar' => date('Y-m-d H:i:s')
            );
            $this->db->insert('member',$data);
            $voucher_details = $this->voucher->find_all();
            foreach ($voucher_details as $voucher) {
            if ($voucher['isNew'] == 'memberbaru') {
                $kodevoucher = $voucher['kodevoucher'];
                $poin = $voucher['poin'];
                $dateRedeem = date('Y-m-d H:i:s');
                $expired_date = date('Y-m-d H:i:s', strtotime('+2 weeks'));
                $vouchergenerate = date('YmdHis') . $kodevoucher;
                $this->vouchermember->insertVoucherNewMember($kodevoucher, $nomor, $poin, $dateRedeem, $expired_date, $vouchergenerate);
            }
        }
            $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
            redirect(base_url('member/indexKasir'));
        }
    }
    public function tambah_saveCabang()
{
    $this->form_validation->set_rules("namamember", "Nama Member", "required|trim");
    $this->form_validation->set_rules("nomor", "Nomor", "required|trim|callback_check_unique_number|min_length[11]");
    
    if ($this->form_validation->run() == FALSE) {
        $this->_has_login();
        $data['title'] = "Tambah Member";
        $this->template->load('templates/cabang', 'member/addCabang', $data);
    } else {
        $data = array(
            'name' => $this->input->post("namamember"),
            'phone_number' => $this->input->post("nomor"),
            'poin' => 0,
            'balance' => 0,
            'profile_pic' => 'profile_default.png',
            'registration_time' => date('Y-m-d H:i:s')
        );

        // Insert into users table
        if ($this->db->insert('users', $data)) {
            $user_id = $this->db->insert_id();
            
            // Handle new member vouchers if needed
            $voucher_details = $this->voucher->find_all();
            foreach ($voucher_details as $voucher) {
                if ($voucher['isNew'] == 'memberbaru') {
                    $kodevoucher = $voucher['kodevoucher'];
                    $poin = $voucher['poin'];
                    $dateRedeem = date('Y-m-d H:i:s');
                    $expired_date = date('Y-m-d H:i:s', strtotime('+2 weeks'));
                    $vouchergenerate = date('YmdHis') . $kodevoucher;
                    
                    // Update voucher_member table with user_id instead of phone number
                    $this->vouchermember->insertVoucherNewMember(
                        $kodevoucher, 
                        $user_id, // Changed from phone number to user_id
                        $poin, 
                        $dateRedeem, 
                        $expired_date, 
                        $vouchergenerate
                    );
                }
            }

            $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Gagal menambahkan data</div>');
        }
        
        redirect(base_url('member/indexCabang'));
    }
}
    public function check_unique_number($nomor){
        $existing_number = $this->member->get_by_nomor($nomor);

        if($existing_number){
            $this->form_validation->set_message('check_unique_number','Nomor Telepon sudah digunakan');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    public function check_unique_email($email){
        $existing_number = $this->member->get_by_email($email);

        if($existing_number){
            $this->form_validation->set_message('check_unique_email','Email sudah digunakan');
            return FALSE;
        }else{
            return TRUE;
        }
    }

    public function detail(){
        $this->_has_login();
        $data['title'] = "Detail Member";
        $nomor = $this->uri->segment('3');
        $data['member'] = $this->member->cari_detail_id($nomor);
        $data['trans'] = $this->transaksi->getTransaksiDetails($nomor);
		$this->template->load('templates/dashboard', 'member/detail', $data);
    }
    public function detailKasir(){
        $data['title'] = "Detail Member";
        $nomor = $this->uri->segment('3');
        $data['member'] = $this->member->cari_detail_id($nomor);
        $data['trans'] = $this->transaksi->getTransaksiDetails($nomor);
		$this->template->load('templates/kasir', 'member/detailKasir', $data);
    }
    public function detailCabang(){
        $data['title'] = "Detail Member";
        $nomor = $this->uri->segment('3');
        $data['member'] = $this->member->cari_detail_id($nomor);
        $data['trans'] = $this->transaksi->getTransaksiDetails($nomor);
		$this->template->load('templates/cabang', 'member/detailCabang', $data);
    }
    public function getLoggingMember(){
        $data['title'] = "Tracking Login Member";
        $data['loggin'] = $this->member->get_login_history();
        $this->template->load('templates/dashboard','member/logging', $data);
    }
    public function getLoggingMemberCabang(){
        $data['title'] = "Logging Member";
        $data['loggin'] = $this->member->get_login_history();
        $this->template->load('templates/cabang','member/loggingCabang', $data);
    }
    public function edit($getId) 
{
    $member = $this->member->get_member($getId);
    
    if (!$member) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
        redirect('member');
    }

    $this->form_validation->set_rules('nama', 'Nama Member', 'required|trim');
    $this->form_validation->set_rules('phone', 'Nomor Handphone', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
    $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
    $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');
    $this->form_validation->set_rules('birthdate', 'Tanggal Lahir', 'required');
    $this->form_validation->set_rules('city', 'Kota', 'required|trim');

    if ($this->form_validation->run() == false) {
        $data['title'] = "Edit Member";
        $data['member'] = $member;
        $this->template->load('templates/dashboard', 'member/edit', $data);
    } else {
        $data = [
            'name' => $this->input->post('nama', true),
            'phone_number' => $this->input->post('phone', true),
            'email' => $this->input->post('email', true),
            'address' => $this->input->post('alamat', true),
            'gender' => $this->input->post('gender', true),
            'birthdate' => $this->input->post('birthdate', true),
            'city' => $this->input->post('city', true)
        ];

        // Handle file upload
        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path'] = '../ImageTerasJapan/ProfPic/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = uniqid('prof_');

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                // Delete old image
                $old_image = $this->input->post('old_image');
                if ($old_image != 'default.png' && file_exists($config['upload_path'] . $old_image)) {
                    unlink($config['upload_path'] . $old_image);
                }
                
                $data['profile_pic'] = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
                redirect('member/edit/' . $getId);
            }
        }

        // Update data
        $update = $this->db->update('users', $data, ['id' => $getId]);

        if ($update) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Member berhasil diupdate</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Member gagal diupdate</div>');
        }
        redirect('member');
    }
}
    public function editKasir(){
        $this->_has_login();
        $data['title'] = "Edit Member";
        $nomor = $this->uri->segment('3');
    
        // Pastikan $id valid sebelum digunakan
        if (!empty($nomor)) {
            // Cek apakah member dengan ID tersebut ada
            $data['member'] = $this->member->cari_detail_id($nomor);
    
            // Pastikan member ditemukan sebelum memuat template
            if ($data['member']) {
                // Load template dengan data yang telah disiapkan
                $this->template->load('templates/kasir', 'member/editKasir', $data);
            } else {
                // Handle jika member tidak ditemukan
                echo "Member tidak ditemukan.";
            }
        } else {
            // Handle jika $id tidak valid
            echo "ID member tidak valid.";
        }
        }
        public function edit_member($nomor){
            $nomor = encode_php_tags($nomor);
            $this->form_validation->set_rules("namamember","Nama Member","required");
            $this->form_validation->set_rules("nomor","Nomor","required");
            $this->form_validation->set_rules("alamat","Alamat","required");
            $this->form_validation->set_rules("email","Email","required");
            $this->form_validation->set_rules("jeniskelamin","Jenis Kelamin","required");
            $this->form_validation->set_rules("tanggallahir","Tanggal Lahir","required");
            $this->form_validation->set_rules("tempatlahir","Tempat Lahir","required");
            if($this->form_validation->run() == FALSE){
                $data['title'] = "Edit User";
                $data['member'] = $this->admin->get('member', ['nomor' => $nomor]);
                $this->template->load('templates/dashboard', 'member/edit', $data);
            }else{
                $config['upload_path'] = '../fotouser/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG';
                $config['max_size'] = 2048000;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('foto')){
                    $namamember = $this->input->post("namamember");
                    $nomor = $this->input->post("nomor");
                    $alamat = $this->input->post("alamat");
                    $email = $this->input->post("email");
                    $jeniskelamin = $this->input->post("jeniskelamin");
                    $tanggallahir = $this->input->post("tanggallahir");
                    $tempatlahir = $this->input->post("tempatlahir");
                    $data = array(
                        'namamember' => $namamember,
                        'nomor' => $nomor,
                        'alamat' => $alamat,
                        'email' => $email,
                        'jeniskelamin' => $jeniskelamin,
                        'tanggallahir' => $tanggallahir,
                        'tempatlahir' => $tempatlahir,
                    );
                    var_dump($data);
                    $this->db->where('nomor',$nomor);
                    $this->db->update('member',$data);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                    redirect('member');
                }else{
                    $foto = $this->upload->data();
                    $foto = $foto['file_name'];
                    $namamember = $this->input->post("namamember");
                    $nomor = $this->input->post("nomor");
                    $alamat = $this->input->post("alamat");
                    $email = $this->input->post("email");
                    $jeniskelamin = $this->input->post("jeniskelamin");
                    $tanggallahir = $this->input->post("tanggallahir");
                    $tempatlahir = $this->input->post("tempatlahir");
                    $data = array(
                        'namamember' => $namamember,
                        'nomor' => $nomor,
                        'alamat' => $alamat,
                        'email' => $email,
                        'jeniskelamin' => $jeniskelamin,
                        'tanggallahir' => $tanggallahir,
                        'tempatlahir' => $tempatlahir,
                        'foto' => $foto
                    );
                    var_dump($data);
                    $this->db->where('nomor',$nomor);
                    $this->db->update('member',$data);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                    redirect('member');
                }
                
            }
        }
        public function edit_memberKasir($nomor){
            $nomor = encode_php_tags($nomor);
            $this->form_validation->set_rules("namamember","Nama Member","required");
            $this->form_validation->set_rules("nomor","Nomor","required");
            $this->form_validation->set_rules("alamat","Alamat","required");
            $this->form_validation->set_rules("email","Email","required");
            $this->form_validation->set_rules("jeniskelamin","Jenis Kelamin","required");
            $this->form_validation->set_rules("tanggallahir","Tanggal Lahir","required");
            $this->form_validation->set_rules("tempatlahir","Tempat Lahir","required");
            if($this->form_validation->run() == FALSE){
                $data['title'] = "Edit User";
                $data['member'] = $this->admin->get('member', ['nomor' => $nomor]);
                $this->template->load('templates/kasir', 'member/editKasir', $data);
            }else{
                $config['upload_path'] = '../fotouser/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG';
                $config['max_size'] = 2048000;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('foto')){
                    $namamember = $this->input->post("namamember");
                    $nomor = $this->input->post("nomor");
                    $alamat = $this->input->post("alamat");
                    $email = $this->input->post("email");
                    $jeniskelamin = $this->input->post("jeniskelamin");
                    $tanggallahir = $this->input->post("tanggallahir");
                    $tempatlahir = $this->input->post("tempatlahir");
                    $data = array(
                        'namamember' => $namamember,
                        'nomor' => $nomor,
                        'alamat' => $alamat,
                        'email' => $email,
                        'jeniskelamin' => $jeniskelamin,
                        'tanggallahir' => $tanggallahir,
                        'tempatlahir' => $tempatlahir,
                    );
                    var_dump($data);
                    $this->db->where('nomor',$nomor);
                    $this->db->update('member',$data);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                    redirect('member/indexKasir');
                }else{
                    $foto = $this->upload->data();
                    $foto = $foto['file_name'];
                    $namamember = $this->input->post("namamember");
                    $nomor = $this->input->post("nomor");
                    $alamat = $this->input->post("alamat");
                    $email = $this->input->post("email");
                    $jeniskelamin = $this->input->post("jeniskelamin");
                    $tanggallahir = $this->input->post("tanggallahir");
                    $tempatlahir = $this->input->post("tempatlahir");
                    $data = array(
                        'namamember' => $namamember,
                        'nomor' => $nomor,
                        'alamat' => $alamat,
                        'email' => $email,
                        'jeniskelamin' => $jeniskelamin,
                        'tanggallahir' => $tanggallahir,
                        'tempatlahir' => $tempatlahir,
                        'foto' => $foto
                    );
                    var_dump($data);
                    $this->db->where('nomor',$nomor);
                    $this->db->update('member',$data);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                    redirect('member/indexKasir');
                }
                
            }
        }
        public function edit_memberCabang($nomor){
            $nomor = encode_php_tags($nomor);
            $this->form_validation->set_rules("namamember","Nama Member","required");
            $this->form_validation->set_rules("nomor","Nomor","required");
            $this->form_validation->set_rules("alamat","Alamat","required");
            $this->form_validation->set_rules("email","Email","required");
            $this->form_validation->set_rules("jeniskelamin","Jenis Kelamin","required");
            $this->form_validation->set_rules("tanggallahir","Tanggal Lahir","required");
            $this->form_validation->set_rules("tempatlahir","Tempat Lahir","required");
            if($this->form_validation->run() == FALSE){
                $data['title'] = "Edit User";
                $data['member'] = $this->admin->get('member', ['nomor' => $nomor]);
                $this->template->load('templates/cabang', 'member/editCabang', $data);
            }else{
                $config['upload_path'] = '../fotouser/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG';
                $config['max_size'] = 2048000;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('foto')){
                    $namamember = $this->input->post("namamember");
                    $nomor = $this->input->post("nomor");
                    $alamat = $this->input->post("alamat");
                    $email = $this->input->post("email");
                    $jeniskelamin = $this->input->post("jeniskelamin");
                    $tanggallahir = $this->input->post("tanggallahir");
                    $tempatlahir = $this->input->post("tempatlahir");
                    $data = array(
                        'namamember' => $namamember,
                        'nomor' => $nomor,
                        'alamat' => $alamat,
                        'email' => $email,
                        'jeniskelamin' => $jeniskelamin,
                        'tanggallahir' => $tanggallahir,
                        'tempatlahir' => $tempatlahir,
                    );
                    var_dump($data);
                    $this->db->where('nomor',$nomor);
                    $this->db->update('member',$data);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                    redirect('member/indexCabang');
                }else{
                    $foto = $this->upload->data();
                    $foto = $foto['file_name'];
                    $namamember = $this->input->post("namamember");
                    $nomor = $this->input->post("nomor");
                    $alamat = $this->input->post("alamat");
                    $email = $this->input->post("email");
                    $jeniskelamin = $this->input->post("jeniskelamin");
                    $tanggallahir = $this->input->post("tanggallahir");
                    $tempatlahir = $this->input->post("tempatlahir");
                    $data = array(
                        'namamember' => $namamember,
                        'nomor' => $nomor,
                        'alamat' => $alamat,
                        'email' => $email,
                        'jeniskelamin' => $jeniskelamin,
                        'tanggallahir' => $tanggallahir,
                        'tempatlahir' => $tempatlahir,
                        'foto' => $foto
                    );
                    var_dump($data);
                    $this->db->where('nomor',$nomor);
                    $this->db->update('member',$data);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                    redirect('member/indexCabang');
                }
                
            }
        }
    
    
}
