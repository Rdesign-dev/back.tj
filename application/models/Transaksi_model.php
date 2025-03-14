<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

    public $table = "transaksi";

    public function __construct() {
        parent::__construct();
    }

    public function getAllTransaksi() {
        // Logic to retrieve all transaction data from the table
        return $this->db->get($this->table)->result();
    }

    public function getTransaksiById($id) {
        // Logic to retrieve transaction data based on ID from the table
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }
    public function getTransaksiByIdMember($id) {
        // Logic to retrieve transaction data based on ID from the table
        $this->db->where('idmember', $id);
        $query = $this->db->get('transaksi'); // Ganti 'nama_tabel_transaksi' dengan nama tabel transaksi Anda
        return $query->result_array();
    }
    public function getTransaksiByIdCabang($id) {
        // Logic to retrieve transaction data based on ID from the table
        $this->db->where('nocabang', $id);
        $query = $this->db->get('transaksi'); // Ganti 'nama_tabel_transaksi' dengan nama tabel transaksi Anda
        return $query->result_array();
    }

    // public function getHistoryTransaksiDetails() 
    // {
    //     $this->db->select('t.*, b.branch_name as namacabang, u.name as member_name, a.Name as cashier_name');
    //     $this->db->from('transactions t');
    //     $this->db->join('branch b', 'b.id = t.branch_id', 'left');
    //     $this->db->join('users u', 'u.id = t.user_id', 'left');
    //     $this->db->join('accounts a', 'a.id = t.account_cashier_id', 'left');
    //     $this->db->where_in('t.transaction_type', ['Teras Japan Payment', 'Reedem Voucher']);
    //     $this->db->order_by('t.created_at', 'DESC');
        
    //     $query = $this->db->get();
    //     return $query->result();
    // }
    public function insert($data) {
        // Insert data into the 'transaksi' table
        return $this->db->insert($this->table, $data);
    }
    public function generate_pdf_data($branch_id, $range)
    {
        $this->db->select('
            transactions.transaction_codes as kodetransaksi,
            transactions.created_at as tanggaltransaksi,
            branch.branch_name as namacabang,
            users.name as namamember,
            accounts.Name as nama,
            transactions.amount as total,
            transactions.transaction_type
        ');
        $this->db->from('transactions');
        $this->db->join('branch', 'branch.id = transactions.branch_id', 'left');
        $this->db->join('users', 'users.id = transactions.user_id', 'left');
        $this->db->join('accounts', 'accounts.id = transactions.account_cashier_id', 'left');
        $this->db->where_in('transactions.transaction_type', ['Teras Japan Payment', 'Reedem Voucher']);
        $this->db->where('DATE(transactions.created_at) >=', $range['mulai']);
        $this->db->where('DATE(transactions.created_at) <=', $range['akhir']);
        
        if ($branch_id !== 'all') {
            $this->db->where('transactions.branch_id', $branch_id);
        }
        
        $this->db->order_by('transactions.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
    public function getNextSequence() {
        $this->db->select('MAX(CAST(SUBSTRING(transaction_codes, -4) AS UNSIGNED)) as last_sequence');
        $this->db->from('transactions');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $query = $this->db->get();
        $result = $query->row();
        
        return ($result->last_sequence ?? 0) + 1;
    }

    public function getTopupHistoryByBranch($branch_id)
    {
        $this->db->select('t.*, u.name as namamember, ac.name as nama_kasir, t.amount as nominal, t.payment_method as metode, t.transaction_evidence as bukti')
                 ->from('transactions t')
                 ->join('users u', 'u.id = t.user_id')
                 ->join('account_cashier ac', 'ac.id = t.account_cashier_id')
                 ->where('t.branch_id', $branch_id)
                 ->where('t.transaction_type', 'Top Up')
                 ->order_by('t.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    public function getTransaksiByIdCabangWithDetails($branch_id) 
    {
        $this->db->select('
            t.transaction_codes,
            t.created_at,
            t.transaction_type,
            t.amount,
            t.payment_method,
            t.transaction_evidence,
            u.name as member_name,
            b.branch_name,
            a.Name as cashier_name,
            rv.kode_voucher
        ')
        ->from('transactions t')
        ->join('users u', 'u.id = t.user_id', 'left')
        ->join('branch b', 'b.id = t.branch_id', 'left')
        ->join('accounts a', 'a.id = t.account_cashier_id', 'left')
        ->join('redeem_voucher rv', 'rv.redeem_id = t.voucher_id', 'left')
        ->where('t.branch_id', $branch_id)
        ->where_in('t.transaction_type', ['Teras Japan Payment', 'Redeem Voucher'])
        ->order_by('t.created_at', 'DESC');

        return $this->db->get()->result();
    }
}
