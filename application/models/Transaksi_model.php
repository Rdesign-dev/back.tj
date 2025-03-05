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
    public function generate_pdf_data($nocabang,$range=null) {
        $this->db->select('transaksi.*, cabang.namacabang, user.nama'); // Include the branch name in the select
        $this->db->from('transaksi');
        $this->db->join('cabang', 'cabang.id = transaksi.nocabang'); // Join with cabang table
        $this->db->join('user', 'user.id_user = transaksi.iduser');
        $this->db->where('transaksi.tanggaltransaksi >=', $range['mulai']);
        $this->db->where('transaksi.tanggaltransaksi <=', $range['akhir']);
    
        if ($nocabang != 'all') {
            $this->db->where('transaksi.nocabang', $nocabang);
        }
        $this->db->order_by('transaksi.tanggaltransaksi', 'DESC');
        $query = $this->db->get();
    
        return $query->result_array();
    }
}
