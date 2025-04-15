<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profilekasir extends CI_Controller
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
        $data['title'] = "Profile";
        $data['user'] = $this->db->select('a.*, b.branch_name')
                                ->from('accounts a')
                                ->join('branch b', 'b.id = a.branch_id', 'left')
                                ->where('a.id', $this->user['id'])
                                ->get()
                                ->row_array();
        $this->template->load('templates/kasir', 'profile/userKasir', $data);
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
        $admin_name = strtolower(str_replace(' ', '-', $this->input->post('Name')));
        $config['upload_path']      = FCPATH . 'ImageTerasJapan/ProfPic/';
        $config['allowed_types']    = 'gif|jpg|jpeg|png|JPEG|PNG';
        $config['file_name']        = 'PicA-' . $admin_name . '-' . time();
        $config['max_size']         = '2048000';
        $config['max_width']        = 10000;
        $config['max_height']       = 10000;

        // Create directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);
    }

    public function settingKasir()
    {
        $this->_validasi();
        $this->_config();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Profile";
            $data['user'] = $this->user;
            $this->template->load('templates/kasir', 'profile/settingKasir', $data);
        } else {
            $input = $this->input->post(null, true);
            if (empty($_FILES['photo']['name'])) {
                $insert = $this->admin->update('accounts', 'id', $input['id'], $input);
                if ($insert) {
                    set_pesan('perubahan berhasil disimpan.');
                } else {
                    set_pesan('perubahan tidak disimpan.');
                }
                redirect('profilekasir/settingKasir');
            } else {
                if ($this->upload->do_upload('photo') == false) {
                    echo $this->upload->display_errors();
                    die;
                } else {
                    if ($this->user['photo'] != 'profile_default.png') {
                        $old_image = FCPATH . '../ImageTerasJapan/ProfPic/' . $this->user['photo'];
                        if (file_exists($old_image) && !unlink($old_image)) {
                            set_pesan('gagal hapus foto lama.');
                            redirect('profilekasir/settingKasir');
                        }
                    }

                    $input['photo'] = $this->upload->data('file_name');
                    $update = $this->admin->update('accounts', 'id', $input['id'], $input);
                    if ($update) {
                        set_pesan('perubahan berhasil disimpan.');
                    } else {
                        set_pesan('gagal menyimpan perubahan');
                    }
                    redirect('profilekasir/settingKasir');
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
            $this->template->load('templates/kasir', 'profile/ubahpasswordkasir', $data);
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
            redirect('profilekasir/ubahpassword');
        }
    }
}
