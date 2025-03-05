<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content_model extends CI_Model {

    public $table = "content";
    public function __construct() {
        parent::__construct();
    }
    public function find_all(){
        return $this->db->get($this->table)->result_array();
    }
    public function getKontenByActive() {
        return $this->db->get_where('content', array('isActive' => 1))->row();
    }
    public function insert($data) {
        // Insert data ke tabel 'produk'
        return $this->db->insert($this->table, $data);
    }
}
