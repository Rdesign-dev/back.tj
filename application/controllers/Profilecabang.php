<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profilecabang extends CI_Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');

        $userId = $this->session->userdata('login_session')['id'];
        $this->user = $this->admin->get('accounts', ['id' => $userId]);
    }

    public function index()
    {
        $login_session = $this->session->userdata('login_session');
        $userId = $login_session['id'];
        
        // Get user data with branch info
        $data['user'] = $this->db->select('a.id, a.username, a.Name, a.phone_number, a.photo, a.account_type, b.branch_name')
                                ->from('accounts a')
                                ->join('branch b', 'b.id = a.branch_id', 'left')
                                ->where('a.id', $userId)
                                ->get()
                                ->row_array();

        // Set default photo if none exists
        if (empty($data['user']['photo'])) {
            $data['user']['photo'] = 'profile_default.png';
        }
        
        $data['title'] = "Profile Admin Cabang";
        $this->template->load('templates/cabang', 'profile/userCabang', $data);
    }

    private function _validasi()
    {
        $login_session_data = $this->session->userdata('login_session');
        $iduser = $login_session_data['id'];
        $db = $this->admin->get('accounts', ['id' => $iduser]);

        if($db !== null){
            $username = $this->input->post('username', true);
            $phone_number = $this->input->post('phone_number', true);

            $uniq_username = $db['username'] == $username ? '' : '|is_unique[accounts.username]';
            $uniq_phone = $db['phone_number'] == $phone_number ? '' : '|is_unique[accounts.phone_number]';

            $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric' . $uniq_username);
            $this->form_validation->set_rules('Name', 'Nama', 'required|trim');
            $this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'required|trim|numeric' . $uniq_phone);
        } else {
            echo "Data user tidak ditemukan";
        }
    }

    private function _config()
    {
        $config['upload_path']      = "../ImageTerasJapan/Profpic";
        $config['allowed_types']    = 'gif|jpg|jpeg|png';
        $config['encrypt_name']     = TRUE;
        $config['max_size']         = '2048000';

        $this->load->library('upload', $config);
    }

    public function settingCabang()
    {
        $this->_validasi();
        $this->_config();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Profile Admin Cabang";
            $data['user'] = $this->user;
            $this->template->load('templates/cabang', 'profile/settingCabang', $data);
        } else {
            $input = $this->input->post(null, true);
            $current_photo = $this->user['Photo'];
            if (empty($_FILES['Photo']['name'])) {
                $insert = $this->admin->update('accounts', 'id', $input['id'], $input);
                if ($insert) {
                    set_pesan('perubahan berhasil disimpan.');
                } else {
                    set_pesan('perubahan tidak disimpan.');
                }
                redirect('profilecabang/settingCabang');
            } else {
                if ($this->upload->do_upload('Photo') == false) {
                    echo $this->upload->display_errors();
                    die;
                } else {
                    if ($current_photo != 'user.png') {
                        $old_image = FCPATH . '../fotouser/' . $current_photo;
                        if (file_exists($old_image) && !unlink($old_image)) {
                            set_pesan('gagal hapus foto lama.');
                            redirect('profilecabang/settingCabang');
                        }
                    }

                    $input['Photo'] = $this->upload->data('file_name');
                    $update = $this->admin->update('accounts', 'id', $input['id'], $input);
                    if ($update) {
                        set_pesan('perubahan berhasil disimpan.');
                    } else {
                        set_pesan('gagal menyimpan perubahan');
                    }
                    redirect('profilecabang/settingCabang');
                }
            }
        }
    }

    public function ubahpassword()
    {
        $login_session = $this->session->userdata('login_session');
        $userId = $login_session['id'];

        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|trim|min_length[3]|differs[password_lama]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'matches[password_baru]');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Ubah Password";
            $this->template->load('templates/cabang', 'profile/ubahpasswordcabang', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Get current user password from database
            $current_user = $this->db->get_where('accounts', ['id' => $userId])->row_array();
            
            if (password_verify($input['password_lama'], $current_user['password'])) {
                $new_pass = [
                    'password' => password_hash($input['password_baru'], PASSWORD_DEFAULT)
                ];
                
                $query = $this->admin->update('accounts', 'id', $userId, $new_pass);

                if ($query) {
                    set_pesan('password berhasil diubah.');
                } else {
                    set_pesan('gagal ubah password', false);
                }
            } else {
                set_pesan('password lama salah.', false);
            }
            redirect('profilecabang/ubahpassword');
        }
    }
}
