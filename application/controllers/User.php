<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Admin_model', 'admin');
        $this->load->model('Cabang_model', 'cabang');
        $this->load->library('form_validation');
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
        $data['title'] = "User Management";
        $data['users'] = $this->admin->getUsers();
        $this->template->load('templates/dashboard', 'user/data', $data);
    }

    private function _validasi($mode)
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric');
        $this->form_validation->set_rules('Name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'required|trim');
        $this->form_validation->set_rules('account_type', 'Role', 'required|trim|in_list[super_admin,admin_central,branch_admin,cashier]');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        // Branch validation only for cashier and branch_admin
        if (in_array($this->input->post('account_type'), ['cashier', 'branch_admin'])) {
            $this->form_validation->set_rules('branch_id', 'Cabang', 'required|trim');
        }

        if ($mode == 'add') {
            $this->form_validation->set_rules('username', 'Username', 'is_unique[accounts.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|trim');
            $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'matches[password]|trim');
        }
    }

    public function add()
    {
        $this->_has_login();
        $this->_validasi('add');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah User";
            $data['cabang'] = $this->cabang->find_all(); // Changed from get_all() to find_all()
            $this->template->load('templates/dashboard', 'user/add', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Hash password before saving
            $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
            unset($input['password2']); // Remove password confirmation
            
            // Handle branch_id based on role type
            switch ($input['account_type']) {
                case 'super_admin':
                case 'admin_central': // Added admin_central handling
                    $input['branch_id'] = null;
                    break;
                case 'branch_admin':
                case 'cashier':
                    // Keep branch_id from form input
                    break;
            }

            // Handle photo upload
            if (!empty($_FILES['photo']['name'])) {
                $config['upload_path']   = $_SERVER['DOCUMENT_ROOT'] . '/ImageTerasJapan/ProfPic/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size']      = 10000;
                $config['file_name']     = 'profile_' . time();

                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('photo')) {
                    $input['photo'] = $this->upload->data('file_name');
                } else {
                    set_pesan('gagal mengupload foto: ' . $this->upload->display_errors(), false);
                    $input['photo'] = 'default.jpg';
                }
            } else {
                $input['photo'] = 'default.jpg';
            }
            
            // If not cashier or branch_admin, set branch_id to null
            if (!in_array($input['account_type'], ['cashier', 'branch_admin'])) {
                $input['branch_id'] = null;
            }

            // Debug insert process
            $result = $this->admin->insert('accounts', $input);
            if ($result) {
                set_pesan('data berhasil disimpan.');
                redirect('user');
            } else {
                // Get database error if insert fails
                $error = $this->db->error();
                set_pesan('data gagal disimpan. Error: ' . $error['message'], false);
                redirect('user/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi('edit');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit User";
            $data['user'] = $this->admin->get('accounts', ['id' => $id]);
            $data['cabang'] = $this->cabang->find_all();
            
            // If user not found, redirect
            if (!$data['user']) {
                set_pesan('User tidak ditemukan', false);
                redirect('user');
            }

            $this->template->load('templates/dashboard', 'user/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Handle branch_id based on role type
            switch ($input['account_type']) {
                case 'super_admin':
                case 'admin_central':
                    $input['branch_id'] = null;
                    break;
                case 'branch_admin':
                case 'cashier':
                    // Branch_id from form
                    if (empty($input['branch_id'])) {
                        set_pesan('Cabang harus dipilih untuk Admin Cabang dan Kasir', false);
                        redirect('user/edit/' . $id);
                    }
                    break;
                default:
                    $input['branch_id'] = null;
            }

            // Remove id from input since we're using it in where clause
            $user_id = $input['id'];
            unset($input['id']);

            if ($this->admin->update('accounts', 'id', $id, $input)) {
                set_pesan('data berhasil diubah.');
                redirect('user');
            } else {
                set_pesan('data gagal diubah.', false);
                redirect('user/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        
        if ($this->admin->deleteAccount($id)) {
            set_pesan('User berhasil dihapus.');
        } else {
            set_pesan('User gagal dihapus.', false);
        }
        
        redirect('user');
    }

    public function toggle($getId)
    {
        $id = encode_php_tags($getId);
        
        // Get current user status
        $user = $this->admin->get('accounts', ['id' => $id]);
        
        if ($user) {
            // Toggle status
            $new_status = ($user['status'] == 'Active') ? 'Inactive' : 'Active';
            
            if ($this->admin->update('accounts', 'id', $id, ['status' => $new_status])) {
                set_pesan('Status user berhasil diubah.');
            } else {
                set_pesan('Gagal mengubah status user.', false);
            }
        } else {
            set_pesan('User tidak ditemukan.', false);
        }
        
        redirect('user');
    }
}