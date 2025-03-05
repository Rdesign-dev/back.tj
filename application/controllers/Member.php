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
        $data['title'] = "Management Member";
        $data['members'] = $this->member->find_all();
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
    public function tambah_save()
    {
        
        $this->form_validation->set_rules("namamember","Nama Member","required|trim");
        $this->form_validation->set_rules("nomor","Nomor","required|trim|callback_check_unique_number|min_length[11]");
        if($this->form_validation->run() == FALSE){
            $this->_has_login();
            $data['title'] = "Tambah Member";
            $this->template->load('templates/dashboard', 'member/add', $data);
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
            redirect(base_url('member'));
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
        $this->form_validation->set_rules("namamember","Nama Member","required|trim");
        $this->form_validation->set_rules("nomor","Nomor","required|trim|callback_check_unique_number|min_length[11]");
        if($this->form_validation->run() == FALSE){
            $this->_has_login();
            $data['title'] = "Tambah Member";
            $this->template->load('templates/dashboard', 'member/add', $data);
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
        $data['title'] = "Logging Member";
        $data['loggin'] = $this->member->get_login_history();
        $this->template->load('templates/dashboard','member/logging', $data);
    }
    public function getLoggingMemberCabang(){
        $data['title'] = "Logging Member";
        $data['loggin'] = $this->member->get_login_history();
        $this->template->load('templates/cabang','member/loggingCabang', $data);
    }
    public function edit($nomor){
    $this->_has_login();
    $data['title'] = "Edit Member";
    $nomor = $this->uri->segment('3');

    if (!empty($nomor)) {
        $data['member'] = $this->member->cari_detail_id($nomor);

        // Pastikan member ditemukan sebelum memuat template
        if ($data['member']) {
            // Load template dengan data yang telah disiapkan
            $this->template->load('templates/dashboard', 'member/edit', $data);
        } else {
            // Handle jika member tidak ditemukan
            echo "Member tidak ditemukan.";
        }
    } else {
        // Handle jika $id tidak valid
        echo "Nomor member tidak valid.";
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
