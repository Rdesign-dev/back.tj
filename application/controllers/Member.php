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
    public function tambah_save() 
{
    $this->_has_login();
    
    $this->form_validation->set_rules("namamember", "Nama Member", "required|trim");
    $this->form_validation->set_rules("nomor", "Nomor", "required|trim|callback_check_unique_number|min_length[11]");
    
    if ($this->form_validation->run() == false) {
        $data['title'] = "Tambah Member";
        $this->template->load('templates/dashboard', 'member/add', $data);
    } else {
        try {
            $this->db->trans_start();

            // Insert member baru
            $member_data = array(
                'name' => $this->input->post("namamember", true),
                'phone_number' => $this->input->post("nomor", true),
                'poin' => 0,
                'balance' => 0,
                'profile_pic' => 'profile.jpg',
                'registration_time' => date('Y-m-d H:i:s')
            );

            $this->db->insert('users', $member_data);
            $user_id = $this->db->insert_id();

            // Ambil semua reward untuk member baru
            $new_member_rewards = $this->db->where('category', 'newmember')
                                         ->get('rewards')
                                         ->result_array();

            // Generate dan assign voucher untuk setiap reward
            foreach ($new_member_rewards as $reward) {
                // Generate kode voucher
                $name_part = strtoupper(substr(str_replace(' ', '', $member_data['name']), 0, 5));
                $three_name = substr($name_part, 0, 3);
                $random_num = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                $last_phone = substr($member_data['phone_number'], -4);
                $random_char = substr(bin2hex(random_bytes(ceil(13 / 2))), 0, 13);
                $voucher_code = "NEW-{$name_part}-{$reward['id']}-{$random_num}";
                $expires_at = date('Y-m-d H:i:s', strtotime("+{$reward['total_days']} days"));

                // Generate QR Code URL from API
                $qr_image_name = 'vcreward-' . $three_name . '-' . $last_phone . '-' . $random_char . '.png';
                $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($voucher_code);

                // Get the QR Code image
                $qr_image = file_get_contents($qr_url);

                // Check if the QR Code image error
                if ($qr_image === false) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Terjadi kesalahan saat menghasilkan QR Code.</div>');
                    redirect('member/tambah_save');
                }

                // Directory to save QR Code
                $save_directory = $_SERVER['DOCUMENT_ROOT'] . '/ImageTerasJapan/qrcode/';

                // Save QR Code to directory
                file_put_contents($save_directory . $qr_image_name, $qr_image);

                // Prepare voucher data
                $voucher_data = array(
                    'user_id' => $user_id,
                    'reward_id' => $reward['id'],
                    'brand_id' => $reward['brand_id'],
                    'points_used' => 0,
                    'redeem_date' => date('Y-m-d H:i:s'),
                    'status' => 'Available',
                    'qr_code_url' => $qr_image_name,
                    'kode_voucher' => $voucher_code,
                    'expires_at' => $expires_at
                );

                // Insert voucher to database
                $this->db->insert('redeem_voucher', $voucher_data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menambahkan member baru');
            }

            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Member berhasil ditambahkan beserta voucher member baru</div>');
            redirect('member');
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>');
            redirect('member');
        }
    }
}
    public function tambah_save_kasir()
{
    $this->_has_login();
    
    $this->form_validation->set_rules("namamember", "Nama Member", "required|trim");
    $this->form_validation->set_rules("nomor", "Nomor", "required|trim|callback_check_unique_number|min_length[11]");
    
    if ($this->form_validation->run() == false) {
        $data['title'] = "Tambah Member";
        $this->template->load('templates/kasir', 'member/addKasir', $data);
    } else {
        try {
            $this->db->trans_start();

            // Insert member baru
            $member_data = array(
                'name' => $this->input->post("namamember", true),
                'phone_number' => $this->input->post("nomor", true),
                'poin' => 0,
                'balance' => 0,
                'profile_pic' => 'profile.jpg',
                'registration_time' => date('Y-m-d H:i:s')
            );

            $this->db->insert('users', $member_data);
            $user_id = $this->db->insert_id();

            // Ambil semua reward untuk member baru
            $new_member_rewards = $this->db->where('category', 'newmember')
                                         ->get('rewards')
                                         ->result_array();

            // Generate dan assign voucher untuk setiap reward
            foreach ($new_member_rewards as $reward) {
                // Generate kode voucher
                $name_part = strtoupper(substr(str_replace(' ', '', $member_data['name']), 0, 5));
				$three_name = substr($name_part, 0, 3);
                $random_num = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
				$last_phone = substr($member_data['phone_number'], -4);
				$random_char = substr(bin2hex(random_bytes(ceil(13 / 2))), 0, 13);
                $voucher_code = "NEW-{$name_part}-{$reward['id']}-{$random_num}";
                $expires_at = date('Y-m-d H:i:s', strtotime("+{$reward['total_days']} days"));

                // Generate QR Code URL from API
                $qr_image_name = 'vcreward-' . $three_name . '-' . $last_phone . '-' . $random_char . '.png';
                $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($voucher_code);

				// Get the QR Code image
				$qr_image = file_get_contents($qr_url);

				// Check if the QR Code image error
				if ($qr_image === false) {
					$this->db->trans_rollback();
					$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Terjadi kesalahan saat menghasilkan QR Code.</div>');
					redirect('member/tambah_save_kasir');
				}

				// Directory to save QR Code
				$save_directory = $_SERVER['DOCUMENT_ROOT'] . '/ImageTerasJapan/qrcode/'; 

				// Save QR Code to directory
				file_put_contents($save_directory . $qr_image_name, $qr_image);

                // Prepare voucher data
                $voucher_data = array(
                    'user_id' => $user_id,
                    'reward_id' => $reward['id'],
                    'brand_id' => $reward['brand_id'],
                    'points_used' => 0,
                    'redeem_date' => date('Y-m-d H:i:s'),
                    'status' => 'Available',
                    'qr_code_url' => $qr_image_name,
                    'kode_voucher' => $voucher_code,
                    'expires_at' => $expires_at
                );

				// Insert voucher to database
                $this->db->insert('redeem_voucher', $voucher_data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menambahkan member baru');
            }

            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Member berhasil ditambahkan beserta voucher member baru</div>');
            redirect('member/indexKasir');
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>');
        }
    }
}
    public function tambah_saveCabang()
{
    $this->form_validation->set_rules("namamember", "Nama Member", "required|trim");
    $this->form_validation->set_rules("nomor", "Nomor", "required|trim|callback_check_unique_number|min_length[11]");
    
    if ($this->form_validation->run() == false) {
        $data['title'] = "Tambah Member";
        $this->template->load('templates/cabang', 'member/addCabang', $data);
    } else {
        try {
            $this->db->trans_start();

            // Insert member baru
            $member_data = array(
                'name' => $this->input->post("namamember", true),
                'phone_number' => $this->input->post("nomor", true),
                'poin' => 0,
                'balance' => 0,
                'profile_pic' => 'profile.jpg',
                'registration_time' => date('Y-m-d H:i:s')
            );

            $this->db->insert('users', $member_data);
            $user_id = $this->db->insert_id();

            // Ambil semua reward untuk member baru
            $new_member_rewards = $this->db->where('category', 'newmember')
                                        ->get('rewards')
                                        ->result_array();

            // Generate dan assign voucher untuk setiap reward
            foreach ($new_member_rewards as $reward) {
                // Generate kode voucher
                $name_part = strtoupper(substr(str_replace(' ', '', $member_data['name']), 0, 5));
                $three_name = substr($name_part, 0, 3);
                $random_num = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                $last_phone = substr($member_data['phone_number'], -4);
                $random_char = substr(bin2hex(random_bytes(ceil(13 / 2))), 0, 13);
                $voucher_code = "NEW-{$name_part}-{$reward['id']}-{$random_num}";
                $expires_at = date('Y-m-d H:i:s', strtotime("+{$reward['total_days']} days"));

                // Generate QR Code URL from API
                $qr_image_name = 'vcreward-' . $three_name . '-' . $last_phone . '-' . $random_char . '.png';
                $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($voucher_code);

                // Get the QR Code image
                $qr_image = file_get_contents($qr_url);

                // Check if the QR Code image error
                if ($qr_image === false) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Terjadi kesalahan saat menghasilkan QR Code.</div>');
                    redirect('member/tambah_saveCabang');
                }

                // Directory to save QR Code
                $save_directory = $_SERVER['DOCUMENT_ROOT'] . '/ImageTerasJapan/qrcode/';

                // Save QR Code to directory
                file_put_contents($save_directory . $qr_image_name, $qr_image);

                // Prepare voucher data
                $voucher_data = array(
                    'user_id' => $user_id,
                    'reward_id' => $reward['id'],
                    'brand_id' => $reward['brand_id'],
                    'points_used' => 0,
                    'redeem_date' => date('Y-m-d H:i:s'),
                    'status' => 'Available',
                    'qr_code_url' => $qr_image_name,
                    'kode_voucher' => $voucher_code,
                    'expires_at' => $expires_at
                );

                // Insert voucher to database
                $this->db->insert('redeem_voucher', $voucher_data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menambahkan member baru');
            }

            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Member berhasil ditambahkan beserta voucher member baru</div>');
            redirect('member/indexCabang');
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>');
            redirect('member/indexCabang');
        }
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

    public function detail()
{
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
        public function edit_memberKasir($phone_number = null)
{
    if (!$phone_number) {
        redirect('member/indexKasir');
    }

    // Get member data from users table
    $data['title'] = "Edit Member";
    $data['member'] = $this->db->select('id, name as namamember, phone_number as nomor, 
                                       address as alamat, email, gender as jeniskelamin, 
                                       birthdate as tanggallahir, city as tempatlahir, 
                                       profile_pic as foto')
                              ->where('phone_number', $phone_number)
                              ->get('users')
                              ->row_array();

    if (!$data['member']) {
        set_pesan('Data tidak ditemukan.', false);
        redirect('member/indexKasir');
    }

    // Set validation rules
    $this->form_validation->set_rules('namamember', 'Nama Member', 'required|trim');
    $this->form_validation->set_rules('nomor', 'Nomor HP', 'required|trim');
    $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
    $this->form_validation->set_rules('jeniskelamin', 'Jenis Kelamin', 'required|trim');
    $this->form_validation->set_rules('tanggallahir', 'Tanggal Lahir', 'required');
    $this->form_validation->set_rules('tempatlahir', 'Kota', 'required|trim');

    if ($this->form_validation->run() == false) {
        $this->template->load('templates/kasir', 'member/editKasir', $data);
    } else {
        $input = $this->input->post(null, true);
        
        // Prepare update data
        $data_update = [
            'name' => $input['namamember'],
            'phone_number' => $input['nomor'],
            'address' => $input['alamat'],
            'email' => $input['email'],
            'gender' => ($input['jeniskelamin'] == 'L' ? 'male' : 'female'),
            'birthdate' => $input['tanggallahir'],
            'city' => $input['tempatlahir']
        ];

        // Handle optional photo upload
        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path']   = '../ImageTerasJapan/ProfPic/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['file_name']     = 'prof_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                // Delete old photo if not default
                if ($data['member']['foto'] != 'profile.jpg' && 
                    file_exists($config['upload_path'] . $data['member']['foto'])) {
                    unlink($config['upload_path'] . $data['member']['foto']);
                }
                $data_update['profile_pic'] = $this->upload->data('file_name');
            } else {
                set_pesan('Gagal mengupload foto: ' . $this->upload->display_errors(), false);
                redirect('member/edit_memberKasir/' . $phone_number);
            }
        }

        // Update user data
        $updated = $this->db->where('phone_number', $phone_number)
                           ->update('users', $data_update);

        if ($updated) {
            set_pesan('Data berhasil diubah.');
        } else {
            set_pesan('Gagal mengubah data.', false);
        }
        redirect('member/indexKasir');
    }
}
        public function edit_memberCabang($phone_number = null)
{
    if (!$phone_number) {
        redirect('member/indexCabang');
    }

    // Get member data from users table
    $data['title'] = "Edit Member";
    $data['member'] = $this->db->select('id, name as namamember, phone_number as nomor, 
                                       address as alamat, email, gender as jeniskelamin, 
                                       birthdate as tanggallahir, city as tempatlahir, 
                                       profile_pic as foto')
                              ->where('phone_number', $phone_number)
                              ->get('users')
                              ->row_array();

    if (!$data['member']) {
        set_pesan('Data tidak ditemukan.', false);
        redirect('member/indexCabang');
    }

    // Set validation rules
    $this->form_validation->set_rules('namamember', 'Nama Member', 'required|trim');
    $this->form_validation->set_rules('nomor', 'Nomor HP', 'required|trim');
    $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
    $this->form_validation->set_rules('jeniskelamin', 'Jenis Kelamin', 'required|trim');
    $this->form_validation->set_rules('tanggallahir', 'Tanggal Lahir', 'required');
    $this->form_validation->set_rules('tempatlahir', 'Kota', 'required|trim');

    if ($this->form_validation->run() == false) {
        $this->template->load('templates/cabang', 'member/editCabang', $data);
    } else {
        $input = $this->input->post(null, true);
        
        // Prepare update data
        $data_update = [
            'name' => $input['namamember'],
            'phone_number' => $input['nomor'],
            'address' => $input['alamat'],
            'email' => $input['email'],
            'gender' => ($input['jeniskelamin'] == 'L' ? 'male' : 'female'),
            'birthdate' => $input['tanggallahir'],
            'city' => $input['tempatlahir']
        ];

        // Handle optional photo upload
        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path']   = '../ImageTerasJapan/ProfPic/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['file_name']     = 'prof_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                // Delete old photo if not default
                if ($data['member']['foto'] != 'profile.jpg' && 
                    file_exists($config['upload_path'] . $data['member']['foto'])) {
                    unlink($config['upload_path'] . $data['member']['foto']);
                }
                $data_update['profile_pic'] = $this->upload->data('file_name');
            } else {
                set_pesan('Gagal mengupload foto: ' . $this->upload->display_errors(), false);
                redirect('member/edit_memberCabang/' . $phone_number);
            }
        }

        // Update user data
        $updated = $this->db->where('phone_number', $phone_number)
                           ->update('users', $data_update);

        if ($updated) {
            set_pesan('Data berhasil diubah.');
        } else {
            set_pesan('Gagal mengubah data.', false);
        }
        redirect('member/indexCabang');
    }
}
}