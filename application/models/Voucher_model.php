<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends CI_Model {

    public $table = "rewards"; // Sudah benar menggunakan tabel rewards
    
    public function __construct() {
        parent::__construct();
    }

    public function find_all(){
        return $this->db->get($this->table)->result_array();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function delete($table, $pk, $id) {
        return $this->db->delete($table, [$pk => $id]);
    }

    public function update($table, $pk, $id, $data) {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function get_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row_array(); // Ubah ke row_array() agar konsisten dengan format data
    }
}
