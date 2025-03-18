<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topup_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllTopup() {
        $this->db->select('t.transaction_codes, t.created_at, t.amount, 
                         GROUP_CONCAT(CONCAT(tp.payment_method, " (", tp.amount, ")") SEPARATOR " & ") as payment_details,
                         t.transaction_evidence, u.name as member_name, a.Name as cashier_name')
                 ->from('transactions t')
                 ->join('users u', 'u.id = t.user_id')
                 ->join('accounts a', 'a.id = t.account_cashier_id')
                 ->join('transaction_payments tp', 'tp.transaction_id = t.transaction_id')
                 ->where('t.transaction_type', 'Balance Top-up')
                 ->group_by('t.transaction_id')
                 ->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function getTransaksiById($id) {
        return $this->db->get_where('transactions', array('transaction_id' => $id))->row();
    }

    public function getTransaksiByIdMember($id) {
        // Logic to retrieve transaction data based on ID from the table
        $this->db->where('idmember', $id);
        $query = $this->db->get('transactions'); // Ganti 'nama_tabel_transaksi' dengan nama tabel transaksi Anda
        return $query->result_array();
    }

    public function insert($data) {
        $this->db->trans_start();
        
        // Insert ke tabel transactions
        $this->db->insert('transactions', [
            'transaction_codes' => $data['transaction_codes'],
            'user_id' => $data['user_id'],
            'transaction_type' => 'Balance Top-up',
            'amount' => $data['amount'],
            'branch_id' => $data['branch_id'],
            'account_cashier_id' => $data['account_cashier_id'],
            'transaction_evidence' => $data['transaction_evidence'],
            'created_at' => $data['created_at']
        ]);
        
        // Get transaction_id yang baru dibuat
        $transaction_id = $this->db->insert_id();
        
        // Insert ke tabel transaction_payments
        $this->db->insert('transaction_payments', [
            'transaction_id' => $transaction_id,
            'payment_method' => $data['payment_method'],
            'amount' => $data['amount']
        ]);
        
        // Update balance user
        $this->db->set('balance', 'balance + ' . $data['amount'], FALSE)
                 ->where('id', $data['user_id'])
                 ->update('users');
                 
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function getTopupByIdCabang($branch_id)
    {
        return $this->db->select('t.transaction_codes, 
                                 t.created_at, 
                                 u.name as member_name,
                                 t.amount, 
                                 a.Name as cashier_name,
                                 GROUP_CONCAT(CONCAT(tp.payment_method, " (", tp.amount, ")") SEPARATOR " & ") as payment_details')
                ->from('transactions t')
                ->join('users u', 'u.id = t.user_id')
                ->join('accounts a', 'a.id = t.account_cashier_id')
                ->join('transaction_payments tp', 'tp.transaction_id = t.transaction_id')
                ->where('t.branch_id', $branch_id)
                ->where('t.transaction_type', 'Balance Top-up')
                ->group_by('t.transaction_id')
                ->order_by('t.created_at', 'DESC')
                ->get()
                ->result();
    }

    private function generate_transaction_code($account_id) {
        $date = date('my');
        $random = mt_rand(1000, 9999);
        return "TX-{$account_id}-SU-{$date}-{$random}";
    }

    private function generate_evidence_filename($user_id) {
        $timestamp = date('YmdHis');
        $random = substr(str_shuffle("0123456789"), 0, 3);
        return "{$user_id}-SU-{$timestamp}-{$random}";
    }
}


