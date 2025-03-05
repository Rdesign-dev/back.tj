<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iklan_model extends CI_Model {

    public $table = "iklan";
    public function __construct() {
        parent::__construct();
    }
    public function getAllCabang() {
        // Logika untuk mengambil semua data produk dari tabel
        return $this->db->get('cabang')->result_array();
    }
    public function find_all(){
        return $this->db->get($this->table)->result_array();
    }
    public function insert($data) {
        // Insert data ke tabel 'produk'
        return $this->db->insert($this->table, $data);
    }
    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }
    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }
    public function cari_detail_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get('iklan'); // replace 'your_member_table_name' with your actual table name

        return $query->row();
    }
    
}
