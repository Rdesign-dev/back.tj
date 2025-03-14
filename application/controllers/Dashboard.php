<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        cek_login();

        $this->load->model('Admin_model', 'admin');
    }

    public function index()
    {
        if(!$this->session->userdata('login_session')){
            redirect(base_url('auth'));
        } else {
            $login_session = $this->session->userdata('login_session');
            
            $data['title'] = "Dashboard";
            $data['user'] = $this->admin->count('user');  // akan menghitung dari tabel accounts
            $data['member'] = $this->admin->count('member'); // akan menghitung dari tabel users
            $data['cabang'] = $this->admin->count('cabang'); // akan menghitung dari tabel branch
            $data['transaksi'] = $this->admin->count('transaksi'); // akan menghitung dari tabel transactions
            
            $monthlyTransactionData = $this->admin->getMonthlyTransactionData();
            $redeemData = $this->admin->getMonthlyRedeemData();
            $data['monthlyTransactionData'] = json_encode($monthlyTransactionData);
            $data['redeemTransactionData'] = json_encode($redeemData);
            
            $this->template->load('templates/dashboard', 'dashboard', $data);
        }
    }

    public function cabang()
    {
        if(!$this->session->userdata('login_session')){
            redirect(base_url('auth'));
        } else {
            $login_session_data = $this->session->userdata('login_session');
            // Mengubah idcabang menjadi branch_id sesuai struktur baru
            $branch_id = $login_session_data['branch_id'];
            
            $data['title'] = "Dashboard Admin Cabang";
            $data['user'] = $this->admin->count('user');
            $data['member'] = $this->admin->count('member');
            $data['cabang'] = $this->admin->count('cabang');
            $data['transaksi'] = $this->admin->count('transaksi');
            
            $monthlyTransactionData = $this->admin->getMonthlyTransactionDataByCabang($branch_id);
            $redeemData = $this->admin->getMonthlyRedeemData();
            $data['monthlyTransactionData'] = json_encode($monthlyTransactionData);
            $data['redeemTransactionData'] = json_encode($redeemData);
            
            $this->template->load('templates/cabang', 'dashboardcabang', $data);
        }
    }

    public function kasir()
    {
        $login_session = $this->session->userdata('login_session');
        $branch_id = $login_session['branch_id'];
        
        $data['title'] = "Dashboard Kasir"; // Menambahkan title untuk fix error

        // Get transaction count from branch table
        $this->db->select('transaction_count');
        $this->db->from('branch');
        $this->db->where('id', $branch_id);
        $result = $this->db->get()->row();
        
        $data['transaksi'] = $result->transaction_count;

        // Get monthly transaction data for chart
        $this->db->select("DATE_FORMAT(created_at, '%M %Y') as month, COUNT(*) as count");
        $this->db->from('transactions');
        $this->db->where('branch_id', $branch_id);
        $this->db->where_in('transaction_type', ['Teras Japan Payment', 'Reedem Voucher']);
        $this->db->group_by("DATE_FORMAT(created_at, '%Y-%m')");
        $this->db->order_by("created_at", "ASC");
        $query = $this->db->get();
        
        $data['monthlyTransactionData'] = json_encode($query->result());
        
        $this->template->load('templates/kasir', 'dashboardKasir', $data);
    }
    
}
