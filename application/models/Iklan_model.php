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
        return $this->db->get('promo')->result_array(); // Changed from iklan to promo
    }
    public function insert($data) {
        if (!isset($data['status'])) {
            $data['status'] = 'Inactive'; // Set default status
        }
        return $this->db->insert('promo', $data);
    }
    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('promo');
    }
    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }
    public function cari_detail_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get('promo'); // Changed from iklan to promo

        return $query->row();
    }
    public function get_active_promos()
    {
        return $this->db->select('id, title, description, image_name, status')
                        ->from('promo')
                        ->where('status', 'Active')
                        ->get()
                        ->result_array();
    }

    public function update_status($id, $status)
    {
        return $this->db->where('id', $id)
                        ->update('promo', ['status' => $status]);
    }
}
