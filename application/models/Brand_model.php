<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_model extends CI_Model {
    
    public $table = 'brands';
    
    public function find_all() {
        return $this->db->get($this->table)->result_array();
    }

    public function get_by_id($id) {
        try {
            $query = $this->db
                ->select('id, name, `desc`, image, banner, instagram, tiktok, wa, web')
                ->where('id', $id)
                ->get($this->table); // Using the table property which is already set to 'brands'

            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
            return null;
        } catch (Exception $e) {
            log_message('error', 'Database error: ' . $e->getMessage());
            return null;
        }
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get_brand_promos($brand_id) {
        try {
            $query = $this->db
                ->select('id, promo_name, promo_desc, promo_image, status, qty, points_required, valid_until')
                ->where('id_brand', $brand_id)
                ->get('brand_promo');
            
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Database error: ' . $e->getMessage());
            return [];
        }
    }
}