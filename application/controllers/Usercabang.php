<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usercabang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Admin_model', 'admin');
        $this->load->model('Cabang_model', 'cabang');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $login_session_data = $this->session->userdata('login_session');
        $idcabang = $login_session_data['idcabang'];
        $data['title'] = "User Management";
        $data['users'] = $this->admin->getUsersCabang(userdata('id_user'),$idcabang);
        $this->template->load('templates/cabang', 'user/datacabang', $data);
    }

    private function _validasi($mode)
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required|trim');
        
        if ($this->input->post('role') == 'kasir') {
        $this->form_validation->set_rules('idcabang', 'Cabang', 'required|trim');
        }else if($this->input->post('role') == 'admincabang'){
        $this->form_validation->set_rules('idcabang', 'Cabang', 'required|trim');
        }
        if ($mode == 'add') {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]|alpha_numeric');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|trim');
            $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'matches[password]|trim');
        } else {
            $db = $this->admin->get('user', ['id_user' => $this->input->post('id_user', true)]);
            $username = $this->input->post('username', true);
            $uniq_username = '';
            if($db){
            $uniq_username = $db['username'] == $username ? '' : '|is_unique[user.username]';
            }
            

            $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric' . $uniq_username);
        }
    }

    public function add()
    {
        $this->_validasi('add');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah User";
            $data['cabang'] = $this->cabang->find_all();
            $this->template->load('templates/cabang', 'user/addcabang', $data);
        } else {
            $input = $this->input->post(null, true);
            $cabang = null;
            $namacabang = null;
            if($input['role'] == 'kasir'){
                $cabang = $input['idcabang'];
                $namacabang = $input['namacabang'];
            }else if($input['role'] == 'admincabang'){
                $cabang = $input['idcabang'];
                $namacabang = $input['namacabang'];
            }
            $input_data = [
                'nama'          => $input['nama'],
                'username'      => $input['username'],
                'no_telp'       => $input['no_telp'],
                'role'          => $input['role'],
                'idcabang'       => $cabang,
                'namacabang'    => $namacabang,
                'password'      => password_hash($input['password'], PASSWORD_DEFAULT),
                'created_at'    => time(),
                'foto'          => 'user.png'
            ];

            if ($this->admin->insert('user', $input_data)) {
                set_pesan('data berhasil disimpan.');
                redirect('usercabang');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('usercabang/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi('edit');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit User";
            $data['cabang'] = $this->cabang->find_all();
            $data['user'] = $this->admin->get('user', ['id_user' => $id]);
            $this->template->load('templates/cabang', 'user/editcabang', $data);
        } else {
            $input = $this->input->post(null, true);
            $cabang = null;
            $namacabang = null;
            if($input['role'] == 'kasir'){
                $cabang = $input['idcabang'];
                $namacabang = $input['namacabang'];
            }else if($input['role'] == 'admincabang'){
                $cabang = $input['idcabang'];
                $namacabang = $input['namacabang'];
            }
            $input_data = [
                'nama'          => $input['nama'],
                'username'      => $input['username'],
                'no_telp'       => $input['no_telp'],
                'role'          => $input['role'],
                'idcabang'      => $cabang,
                'namacabang'    => $namacabang
            ];

            if ($this->admin->update('user', 'id_user', $id, $input_data)) {
                set_pesan('data berhasil diubah.');
                redirect('usercabang');
            } else {
                set_pesan('data gagal diubah.', false);
                redirect('usercabang/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('user', 'id_user', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('usercabang');
    }

    public function toggle($getId)
    {
        $id = encode_php_tags($getId);
        $status = $this->admin->get('user', ['id_user' => $id])['is_active'];
        $toggle = $status ? 0 : 1; //Jika user aktif maka nonaktifkan, begitu pula sebaliknya
        $pesan = $toggle ? 'user diaktifkan.' : 'user dinonaktifkan.';

        if ($this->admin->update('user', 'id_user', $id, ['is_active' => $toggle])) {
            set_pesan($pesan);
        }
        redirect('usercabang');
    }
}
