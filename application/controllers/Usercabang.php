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
        $login_session = $this->session->userdata('login_session');
        $branch_id = $login_session['branch_id'];
        $data['title'] = "User Management";
        $data['users'] = $this->admin->getUsersCabang($login_session['id'], $branch_id);
        $this->template->load('templates/cabang', 'user/datacabang', $data);
    }

    private function _validasi($mode)
    {
        $this->form_validation->set_rules('Name', 'Nama', 'required|trim');
        $this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'required|trim');
        $this->form_validation->set_rules('account_type', 'Role', 'required|trim');
        
        if ($mode == 'add') {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[accounts.username]|alpha_numeric');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|trim');
            $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'matches[password]|trim');
        } else {
            $id = $this->input->post('id', true);
            $username = $this->input->post('username', true);
            
            // Get current user data
            $current_user = $this->admin->get('accounts', ['id' => $id]);
            
            // Only check username uniqueness if it changed
            if ($current_user && $current_user['username'] != $username) {
                $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[accounts.username]|alpha_numeric');
            } else {
                $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric');
            }
        }
    }

    public function add()
    {
        $this->_validasi('add');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah User";
            $this->template->load('templates/cabang', 'user/addcabang', $data);
        } else {
            $input = $this->input->post(null, true);
            $login_session = $this->session->userdata('login_session');
            $branch_id = $login_session['branch_id'];

            // Generate random 5 digit number
            $random_number = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            
            // Format filename: Prof-{nama}-{5 digit random}
            $filename = 'Prof-' . str_replace(' ', '_', $input['Name']) . '-' . $random_number;

            // Upload configuration
            $config['upload_path']   = '../ImageTerasJapan/Profpic';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 2048;
            $config['file_name']     = $filename;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('photo')) {
                $upload_data = $this->upload->data();
                $photo = $upload_data['file_name'];
            } else {
                $photo = 'profile_default.png';
            }

            $input_data = [
                'username'      => $input['username'],
                'phone_number'  => $input['phone_number'],
                'password'      => password_hash($input['password'], PASSWORD_DEFAULT),
                'Name'          => $input['Name'],
                'account_type'  => $input['account_type'],
                'branch_id'     => $branch_id, // Using branch_id from session
                'status'        => $input['status'],
                'photo'         => $photo
            ];

            // // Debug: View data before insert
            // echo "<pre>";
            // echo "Data to be inserted:\n";
            // var_dump($input_data);
            // echo "</pre>";
            // die();

            if ($this->admin->insert('accounts', $input_data)) {
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
            $data['user'] = $this->admin->get('accounts', ['id' => $id]);
            $this->template->load('templates/cabang', 'user/editcabang', $data);
        } else {
            $input = $this->input->post(null, true);
            $login_session = $this->session->userdata('login_session');
            
            // Get old photo name for potential deletion
            $old_photo = $this->admin->get('accounts', ['id' => $id])['photo'];

            // Handle photo upload if new photo is provided
            if (!empty($_FILES['photo']['name'])) {
                // Generate random 5 digit number for new filename
                $random_number = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                $filename = 'Prof-' . str_replace(' ', '_', $input['Name']) . '-' . $random_number;

                $config['upload_path']   = '../ImageTerasJapan/Profpic';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = 2048;
                $config['file_name']     = $filename;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('photo')) {
                    $upload_data = $this->upload->data();
                    $photo = $upload_data['file_name'];
                    
                    // Delete old photo if it's not the default
                    if ($old_photo != 'profile_default.png') {
                        unlink('../ImageTerasJapan/Profpic/' . $old_photo);
                    }
                } else {
                    $photo = $old_photo;
                }
            } else {
                $photo = $old_photo;
            }

            $input_data = [
                'username'      => $input['username'],
                'phone_number'  => $input['phone_number'],
                'Name'          => $input['Name'],
                'account_type'  => $input['account_type'],
                'branch_id'     => $login_session['branch_id'],
                'status'        => $input['status'],
                'photo'         => $photo
            ];

            if ($this->admin->update('accounts', 'id', $id, $input_data)) {
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
        
        // Get photo name before deleting
        $user = $this->admin->get('accounts', ['id' => $id]);
        
        if ($user) {
            // Delete photo file if it's not the default
            if ($user['photo'] != 'profile_default.png') {
                $photo_path = '../ImageTerasJapan/Profpic/' . $user['photo'];
                if (file_exists($photo_path)) {
                    unlink($photo_path);
                }
            }
            
            // Delete user from database
            $this->db->where('id', $id);
            $delete_result = $this->db->delete('accounts');
            
            if ($delete_result) {
                set_pesan('data berhasil dihapus.');
            } else {
                set_pesan('data gagal dihapus.', false);
            }
        } else {
            set_pesan('data tidak ditemukan.', false);
        }
        
        redirect('usercabang');
    }

    // Add toggle status method
    public function toggleStatus($getId)
    {
        $id = encode_php_tags($getId);
        $current_status = $this->admin->get('accounts', ['id' => $id])['status'];
        $new_status = ($current_status == 'Active') ? 'Inactive' : 'Active';

        if ($this->admin->update('accounts', 'id', $id, ['status' => $new_status])) {
            set_pesan('Status berhasil diubah.');
        } else {
            set_pesan('Status gagal diubah.', false);
        }
        redirect('usercabang');
    }
}
