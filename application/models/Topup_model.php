<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topup_model extends CI_Model {

    public $table = "topup";

    public function __construct() {
        parent::__construct();
    }

    public function getAllTopup() {
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
        // Insert data into the 'transaksi' table
        return $this->db->insert($this->table, $data);
    }
}
