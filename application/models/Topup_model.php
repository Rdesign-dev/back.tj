<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topup_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllTopup() {
        $this->db->select('t.transaction_codes, t.created_at, t.amount, 
                         t.payment_method, t.transaction_evidence,
                         u.name as member_name, a.Name as cashier_name')
                 ->from('transactions t')
                 ->join('users u', 'u.id = t.user_id')
                 ->join('accounts a', 'a.id = t.account_cashier_id')
                 ->where('t.transaction_type', 'Balance Top-up')
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

    public function getTopupDetails() {
        $this->db->select('topup.*, member.namamember, user.nama');
        $this->db->from('topup');
        $this->db->join('member', 'member.nomor = topup.nomor');
        $this->db->join('user', 'user.id_user = topup.id_user');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function insert($data) {
        $this->db->trans_start();
        
        // Insert ke tabel transactions
        $this->db->insert('transactions', $data);
        
        // Update balance user
        $this->db->set('balance', 'balance + ' . $data['amount'], FALSE)
                 ->where('id', $data['user_id'])
                 ->update('users');
                 
        $this->db->trans_complete();
        return $this->db->trans_status();
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
