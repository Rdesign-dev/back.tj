<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Auth_model', 'auth');
        $this->load->model('Admin_model', 'admin');
        $this->load->model('Member_model','member');
    }

    // kela ker ditanya bal
    public function index()
    {
        if ($this->session->userdata('login_session')) {
            $user_role = $this->session->userdata('login_session')['role'];
            if ($user_role == 'cashier') {
                redirect('dashboard/kasir');
            } elseif ($user_role == 'super_admin') {
                redirect('dashboard');
            } elseif($user_role == 'branch_admin'){
                redirect('dashboard/cabang');
            } elseif($user_role == 'admin_central'){
                redirect('dashboard');
            }
        }
        $data['title'] = 'Login Aplikasi';
        $this->template->load('templates/auth', 'auth/login', $data);
    }

    public function login(){
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login Aplikasi';
            $this->template->load('templates/auth', 'auth/login', $data);
        } else {
            $input = $this->input->post(null, true);
            $cek_username = $this->auth->cek_username($input['username']);
            if ($cek_username > 0) {
                $password = $this->auth->get_password($input['username']);
                if (password_verify($input['password'], $password)) {
                    $user_db = $this->auth->userdata($input['username']);
                    if ($user_db['status'] != 'Active') {
                        set_pesan('akun anda belum aktif/dinonaktifkan. Silahkan hubungi admin.', false);
                        redirect('auth');
                    } else {
                        $userdata = [
                            'id' => $user_db['id'],
                            'username' => $user_db['username'],
                            'name' => $user_db['Name'],
                            'photo' => $user_db['photo'],
                            'account_type' => $user_db['account_type'],
                            'branch_id' => $user_db['branch_id']
                        ];
                        
                        $this->session->set_userdata('login_session', $userdata);
                        
                        if ($user_db['account_type'] == 'cashier') {
                            $branch_id = $this->auth->get_branch_id($user_db['id']);
                            $branch_name = $this->auth->get_name_id($user_db['id']);
                            $userdata['idcabang'] = $branch_id;
                            $userdata['namacabang'] = $branch_name;
                            redirect('dashboard/kasir'); // Sesuaikan dengan URL dashboard kasir Anda
                        } else if($user_db['account_type'] == 'branch_admin') {
                            $branch_id = $this->auth->get_branch_id($user_db['id']);
                            $branch_name = $this->auth->get_name_id($user_db['id']);
                            $userdata['idcabang'] = $branch_id;
                            $userdata['namacabang'] = $branch_name;
                            redirect('dashboard/cabang');
                        } else {
                            redirect('dashboard');
                        }
                    }
                } else {
                    set_pesan('password salah', false);
                    redirect('auth');
                }
            } else {
                set_pesan('username belum terdaftar', false);
                redirect('auth');
            }
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('login_session');
        set_pesan('anda telah berhasil logout');
        redirect('auth');
    }

    public function register()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[accounts.username]|alpha_numeric');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|trim');
        $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'matches[password]|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Buat Akun';
            $this->template->load('templates/auth', 'auth/register', $data);
        } else {
            $input = $this->input->post(null, true);
            unset($input['password2']);

            $data = [
                'username' => $input['username'],
                'password' => password_hash($input['password'], PASSWORD_DEFAULT),
                'Name' => $input['nama'],
                'phone_number' => $input['phone_number'],
                'account_type' => 'cashier', // Sesuaikan dengan kebutuhan
                'photo' => 'user.png',
                'status' => 'Inactive'
            ];

            $query = $this->admin->insert('accounts', $data);
            if ($query) {
                set_pesan('daftar berhasil. Selanjutnya silahkan hubungi admin untuk mengaktifkan akun anda.');
                redirect('login');
            } else {
                set_pesan('gagal menyimpan ke database', false);
                redirect('register');
            }
        }
    }
}

