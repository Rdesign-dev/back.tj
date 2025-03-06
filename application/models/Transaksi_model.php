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
    public function getTransaksiByIdMemberWithDetails($idcabang) {
        $this->db->select('transaksi.*, cabang.namacabang, member.namamember,user.nama');
        $this->db->from('transaksi');
        $this->db->join('cabang', 'cabang.id = transaksi.nocabang');
        $this->db->join('member', 'member.nomor = transaksi.nomor');
        $this->db->join('user', 'user.id_user = transaksi.iduser');
        $this->db->where('transaksi.nocabang', $idcabang);
        $this->db->order_by('transaksi.tanggaltransaksi', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function getTransaksiDetails($nomor) {
        $this->db->select('transaksi.*, cabang.namacabang,user.nama');
        $this->db->from('transaksi');
        $this->db->join('cabang', 'cabang.id = transaksi.nocabang');
        $this->db->join('user', 'user.id_user = transaksi.iduser');
        $this->db->where('transaksi.nomor', $nomor);
        $this->db->order_by('transaksi.tanggaltransaksi', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function getHistoryTransaksiDetails() {
        $this->db->select('transaksi.*, cabang.namacabang, member.namamember,user.nama');
        $this->db->from('transaksi');
        $this->db->join('cabang', 'cabang.id = transaksi.nocabang');
        $this->db->join('member', 'member.nomor = transaksi.nomor');
        $this->db->join('user', 'user.id_user = transaksi.iduser');
        $this->db->order_by('transaksi.tanggaltransaksi', 'DESC');
        
        $query = $this->db->get();
        $result = $query->result();
        return $result;
        
    }
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
            transactions.amount as total
        ');
        $this->db->from('transactions');
        $this->db->join('branch', 'branch.id = transactions.branch_id', 'left');
        $this->db->join('users', 'users.id = transactions.user_id', 'left');
        $this->db->join('accounts', 'accounts.id = transactions.account_cashier_id', 'left');
        $this->db->where('transactions.transaction_type', 'Teras Japan Payment');
        $this->db->where('DATE(transactions.created_at) >=', $range['mulai']);
        $this->db->where('DATE(transactions.created_at) <=', $range['akhir']);
        
        if ($branch_id !== 'all') {
            $this->db->where('transactions.branch_id', $branch_id);
        }
        
        $this->db->order_by('transactions.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
}
