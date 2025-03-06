<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_model extends CI_Model {
    
    public $table = 'brands'; // Sesuaikan dengan nama tabel di database
    
    public function find_all() {
        return $this->db->get('brands')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    // Tambahkan method lain sesuai kebutuhan
}