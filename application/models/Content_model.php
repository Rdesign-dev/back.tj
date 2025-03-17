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
    public function insert($data) {
        // Insert data ke tabel 'produk'
        return $this->db->insert($this->table, $data);
    }
    
    public function get_all_content() {
        return $this->db->get('popup')->result_array();
    }
    
    public function get_content_by_id($id) {
        return $this->db->get_where('popup', ['id' => $id])->row_array();
    }
    
    public function insert_content($data) {
        $this->db->insert('popup', $data);
        return $this->db->affected_rows();
    }
    
    public function update_content($id, $data) {
        $this->db->update('popup', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }
    
    public function delete_content($id) {
        $this->db->delete('popup', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function toggle_status($id) {
        $content = $this->get_content_by_id($id);
        $new_status = ($content['status'] == 'Active') ? 'Inactive' : 'Active';
        return $this->update_content($id, ['status' => $new_status]);
    }
}
