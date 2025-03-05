<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Undian_model extends CI_Model {

    public $table = "undian";
    public function __construct() {
        parent::__construct();
    }
    public function find_all(){
        return $this->db->query("SELECT * from undian ORDER by tanggalpenukaran DESC")->result_array();
    }
    public function find_poin(){
        return $this->db->query("SELECT * from poinundian")->result_array();
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
    public function get_current_points() {
        $query = $this->db->get('undian');
        if ($query->num_rows() > 0) {
            return $query->row()->poin;
        } else {
            // Return a default value or handle the case as needed
            return 0; // Replace 0 with a default value or handle the case accordingly
        }
    }
    public function update_points($poin) {
        $data = array(
            'poin' => $poin,
            // You may need to add additional fields or conditions depending on your database structure
        );
    
        // Assuming you have a field named 'undian_id' as the unique identifier
        $this->db->where('id', 1); // Replace 'undian_id' and '1' with your actual identifier and value
        $this->db->update('undian', $data);
    }
    public function updatePoin($undian_id, $data) {
        $this->db->where('id', $undian_id);
        $this->db->update('poinundian', $data);
    }

    public function insertPoin($data) {
        $this->db->insert('poinundian', $data);
    }
    
}
