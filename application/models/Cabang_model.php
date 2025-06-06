<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang_model extends CI_Model {

    public $table = "branch";  // Ensure the table name is correct
    public function __construct() {
        parent::__construct();
    }
    public function getAllCabang() {
        // Logika untuk mengambil semua data produk dari tabel
        return $this->db->get('branch')->result_array();
    }
    public function find_all(){
        return $this->db->get($this->table)->result_array();
    }
    public function getCabangById($id) {
        // Logika untuk mengambil data produk berdasarkan ID dari tabel
        return $this->db->get_where('branch', array('id' => $id))->row();
    }

    public function insert($data) {
        // Get the maximum ID from the branch table
        $max_id = $this->db->select_max('id')
                           ->get($this->table)
                           ->row()
                           ->id;
        
        // Set the new ID (max + 1)
        $new_id = ($max_id > 0) ? $max_id + 1 : 1;
        
        // Add the ID to the data array
        $data['id'] = $new_id;
        
        // Insert data ke tabel branch
        return $this->db->insert($this->table, $data);
    }
    
    public function updateJumlahTransaksi($branch_code, $totalTransaksi) {
        $this->db->where('branch_code', $branch_code);
        return $this->db->update('branch', array('transaction_count' => $totalTransaksi));
    }
}
