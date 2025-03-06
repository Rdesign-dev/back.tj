<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_model extends CI_Model {
    
    public $table = 'brands'; // Sesuaikan dengan nama tabel di database
    
    public function find_all() {
        return $this->db->get($this->table)->result_array();
    }

    // Tambahkan method lain sesuai kebutuhan
}