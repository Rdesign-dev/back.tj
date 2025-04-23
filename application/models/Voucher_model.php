<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends CI_Model {

    public $table = "rewards"; // Sudah benar menggunakan tabel rewards
    
    public function __construct() {
        parent::__construct();
    }

    public function find_all() {
        $this->db->select('rewards.*, brands.name as brand_name');
        $this->db->from('rewards');
        $this->db->join('brands', 'brands.id = rewards.brand_id', 'left');
        $this->db->order_by('rewards.id', 'DESC'); // Urutkan berdasarkan id terbesar (terbaru)
        return $this->db->get()->result_array();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function is_voucher_used($id) {
        $this->db->where('reward_id', $id);
        $query = $this->db->get('redeem_voucher'); // Replace 'redeem_voucher' with your actual table name
        return $query->num_rows() > 0;
    }

    public function delete($table, $pk, $id) {
        if ($this->is_voucher_used($id)) {
            throw new Exception("Voucher sedang digunakan dan tidak bisa dihapus.");
        }

        $this->db->where($pk, $id);
        $this->db->delete($table);

        return true;
    }

    public function update($table, $pk, $id, $data) {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function get_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row_array(); // Ubah ke row_array() agar konsisten dengan format data
    }

    public function get_all_brands() {
        return $this->db->select('id, name')
                        ->from('brands')
                        ->get()
                        ->result_array();
    }
    public function get_brand_by_id($brand_id) {
        return $this->db->get_where('brands', ['id' => $brand_id])->row();
    }
}
