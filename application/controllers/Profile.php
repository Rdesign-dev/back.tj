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

        $userId = $this->session->userdata('login_session')['user'];
        $this->user = $this->admin->get('user', ['id_user' => $userId]);
    }

    public function index()
    {
        $data['title'] = "Profile";
        $data['user'] = $this->user;
        $this->template->load('templates/dashboard', 'profile/user', $data);
    }
    private function _validasi()
    {
        $login_session_data = $this->session->userdata('login_session');
        $iduser = $login_session_data['user'];
        $db = $this->admin->get('user', ['id_user' => $iduser]);
        

        if($db !== null){
        $username = $this->input->post('username', true);

        $uniq_username = $db['username'] == $username ? '' : '|is_unique[user.username]';

        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric' . $uniq_username);
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim|numeric');
        }else{
            echo "Data user tidak ditemukan";
        }
        
    }

    private function _config()
    {
        $config['upload_path']      = "./assets/img/avatar";
        $config['allowed_types']    = 'gif|jpg|jpeg|png|JPEG|PNG';
        $config['encrypt_name']     = TRUE;
        $config['max_size']         = '2048000';
        $config['max_width']        = 10000;
        $config['max_height']       = 10000;

        $this->load->library('upload', $config);
    }

    public function setting()
    {
        $this->_validasi();
        $this->_config();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Profile";
            $data['user'] = $this->user;
            $this->template->load('templates/dashboard', 'profile/setting', $data);
        } else {
            $input = $this->input->post(null, true);
            $foto_saat_ini = $this->user['foto'];
            if (empty($_FILES['foto']['name'])) {
                $insert = $this->admin->update('user', 'id_user', $input['id_user'], $input);
                if ($insert) {
                    set_pesan('perubahan berhasil disimpan.');
                } else {
                    set_pesan('perubahan tidak disimpan.');
                }
                redirect('profile/setting');
            } else {
                if ($this->upload->do_upload('foto') == false) {
                    echo $this->upload->display_errors();
                    die;
                } else {
                    if ($foto_saat_ini != 'user.png') {
                        $old_image = FCPATH . '../fotouser/' . $foto_saat_ini;
                        if (unlink($old_image)) {
                            set_pesan('gagal hapus foto lama.');
                            redirect('profile/setting');
                        }
                    }

                    $input['foto'] = $this->upload->data('file_name');
                    $update = $this->admin->update('user', 'id_user', $input['id_user'], $input);
                    if ($update) {
                        set_pesan('perubahan berhasil disimpan.');
                    } else {
                        set_pesan('gagal menyimpan perubahan');
                    }
                    redirect('profile/setting');
                }
            }
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
            if (password_verify($input['password_lama'], userdata('password'))) {
                $new_pass = ['password' => password_hash($input['password_baru'], PASSWORD_DEFAULT)];
                $query = $this->admin->update('user', 'id_user', userdata('id_user'), $new_pass);

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
