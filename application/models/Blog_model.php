<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {

    public $table = "blog";
    public function __construct() {
        parent::__construct();
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
    
}
