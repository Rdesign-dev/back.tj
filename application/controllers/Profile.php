<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');

        $userId = $this->session->userdata('login_session')['id'];
        $this->user = $this->admin->get_user_by_id($userId);
    }

    public function index()
    {
        $data['title'] = "Profile";
        $data['user'] = $this->user;
        $this->template->load('templates/dashboard', 'profile/user', $data);
    }
    private function _validasi()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('Name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'required|trim');
    }

    // public function setting()
    // {
    //     $this->_validasi();
    //     if ($this->form_validation->run() == false) {
    //         $data['title'] = "Profile Setting";
    //         $data['user'] = $this->user;
    //         $this->template->load('templates/dashboard', 'profile/setting', $data);
    //     } else {
    //         $input = $this->input->post(null, true);
    //         $input['id'] = $this->session->userdata('login_session')['id'];

    //         if ($_FILES['photo']['name']) {
    //             $config['upload_path']   = FCPATH . 'fotouser/';
    //             $config['allowed_types'] = 'gif|jpg|jpeg|png';
    //             $config['max_size']      = 2048;
    //             $config['file_name']     = 'profile_' . time();

    //             $this->load->library('upload', $config);
                
    //             if ($this->upload->do_upload('photo')) {
	// 				$old_image = $this->user['photo'];
    //                 if ($old_image != 'default.jpg') {
    //                     unlink(FCPATH . 'fotouser/' . $old_image);
    //                 }
    //                 $input['photo'] = $this->upload->data('file_name');
    //             } else {
    //                 set_pesan('gagal mengupload foto');
    //                 redirect('profile/setting');
    //             }
    //         }

    //         $update = $this->admin->update('accounts', 'id', $input['id'], $input);
    //         if ($update) {
    //             // Update session data
    //             $newUserData = [
    //                 'id' => $input['id'],
    //                 'username' => $input['username'],
    //                 'name' => $input['Name'],
    //                 'photo' => isset($input['photo']) ? $input['photo'] : $this->user['photo'],
    //                 'account_type' => $this->user['account_type'],
    //                 'branch_id' => $this->user['branch_id']
    //             ];
                
    //             $this->session->set_userdata('login_session', $newUserData);
    //             set_pesan('perubahan berhasil disimpan.');
    //         } else {
    //             set_pesan('gagal menyimpan perubahan');
    //         }
    //         redirect('profile/setting');
    //     }
    // }

    

	public function setting()
{
    $this->_validasi();
    if ($this->form_validation->run() == false) {
        $data['title'] = "Profile Setting";
        $data['user'] = $this->user;
        $this->template->load('templates/dashboard', 'profile/setting', $data);
    } else {
        $input = $this->input->post(null, true);
        $input['id'] = $this->session->userdata('login_session')['id'];

        if ($_FILES['photo']['name']) {
            $admin_name = strtolower(str_replace(' ', '-', $input['Name']));
            $config['upload_path']   = $_SERVER['DOCUMENT_ROOT'] . '/ImageTerasJapan/ProfPic/'; 
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['file_name']     = 'PicA-' . $admin_name . '-' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('photo')) {
                $old_image = $this->user['photo'];
                if ($old_image != 'default.jpg') {
                    $old_image_path = $_SERVER['DOCUMENT_ROOT'] . '/ImageTerasJapan/ProfPic/' . $old_image;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
                $input['photo'] = $this->upload->data('file_name');
            } else {
				echo $this->upload->display_errors();
				echo $config['upload_path'];
				die();
                set_pesan('gagal mengupload foto');
                redirect('profile/setting');
            }
        }

        $update = $this->admin->update('accounts', 'id', $input['id'], $input);
        if ($update) {
            $newUserData = [
                'id' => $input['id'],
                'username' => $input['username'],
                'name' => $input['Name'],
                'photo' => isset($input['photo']) ? $input['photo'] : $this->user['photo'],
                'account_type' => $this->user['account_type'],
                'branch_id' => $this->user['branch_id']
            ];

            $this->session->set_userdata('login_session', $newUserData);
            set_pesan('perubahan berhasil disimpan.');
        } else {
            set_pesan('gagal menyimpan perubahan');
        }
        redirect('profile/setting');
    }
}



	public function ubahpassword()
    {
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|trim|min_length[3]|differs[password_lama]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'matches[password_baru]');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Ubah Password";
            $this->template->load('templates/dashboard', 'profile/ubahpassword', $data);
        } else {
            $input = $this->input->post(null, true);
            $id = $this->session->userdata('login_session')['id'];
            
            // Get current user data from accounts table
            $current_user = $this->admin->get('accounts', ['id' => $id]);

            if (password_verify($input['password_lama'], $current_user['password'])) {
                $new_pass = [
                    'password' => password_hash($input['password_baru'], PASSWORD_DEFAULT)
                ];
                
                // Update password in accounts table
                $query = $this->admin->update('accounts', 'id', $id, $new_pass);

                if ($query) {
                    set_pesan('password berhasil diubah.');
                } else {
                    set_pesan('gagal ubah password', false);
                }
            } else {
                set_pesan('password lama salah.', false);
            }
            redirect('profile/ubahpassword');
        }
    }
}
