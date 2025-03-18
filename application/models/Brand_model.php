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

    public function get_brand_promos($brand_id)
    {
        return $this->db->select('
            brand_promo.id,
            brand_promo.promo_name,
            brand_promo.promo_desc,
            brand_promo.promo_image,
            brand_promo.status,
            brand_promo.available_from,
            brand_promo.valid_until
        ')
        ->from('brand_promo')
        ->where('brand_promo.id_brand', $brand_id)
        ->order_by('brand_promo.available_from', 'DESC')
        ->get()
        ->result_array();
    }

    public function insert_promo($data)
    {
        $this->db->trans_start();
        $result = $this->db->insert('brand_promo', $data);
        $this->db->trans_complete();
        
        return $this->db->trans_status() && $result;
    }

    public function update_promo_status()
    {
        // Set timezone Jakarta
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        
        // Log current server time
        log_message('info', 'Checking promo status at: ' . $now);

        // Get all promos that need status update
        $promos = $this->db->get('brand_promo')->result_array();
        
        foreach ($promos as $promo) {
            $available_from = strtotime($promo['available_from']);
            $valid_until = strtotime($promo['valid_until']);
            $current_time = strtotime($now);
            $new_status = null;

            // Determine correct status
            if ($current_time < $available_from) {
                $new_status = 'Coming';
            } else if ($current_time >= $available_from && $current_time < $valid_until) {
                $new_status = 'Available';
            } else if ($current_time >= $valid_until) {
                $new_status = 'Expired';
            }

            // Update only if status needs to change
            if ($new_status && $new_status !== $promo['status']) {
                $this->db->where('id', $promo['id'])
                         ->update('brand_promo', ['status' => $new_status]);
                
                // Log status change
                log_message('info', sprintf(
                    'Promo ID: %d changed from %s to %s (Available from: %s, Valid until: %s)', 
                    $promo['id'],
                    $promo['status'],
                    $new_status,
                    date('Y-m-d H:i:s', $available_from),
                    date('Y-m-d H:i:s', $valid_until)
                ));
            }
        }
        
        return true;
    }

    public function get_promo_by_id($id)
    {
        return $this->db->get_where('brand_promo', ['id' => $id])->row_array();
    }

    public function update_promo($id, $data)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $result = $this->db->update('brand_promo', $data);
        $this->db->trans_complete();
        
        return $this->db->trans_status() && $result;
    }

    public function save_edit_promo($id, $data)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $result = $this->db->update('brand_promo', $data);
        $this->db->trans_complete();
        
        // Log the update attempt
        log_message('info', sprintf(
            'Updating promo ID: %d with data: %s. Result: %s', 
            $id,
            json_encode($data),
            $result ? 'success' : 'failed'
        ));
        
        return $this->db->trans_status() && $result;
    }

    public function get_all_vouchers() {
        return $this->db->get('rewards')->result_array();
    }

    public function get_voucher_by_id($id) {
        return $this->db->get_where('rewards', ['id' => $id])->row_array();
    }

    public function insert_voucher($data) {
        return $this->db->insert('rewards', $data);
    }

    public function update_voucher($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('rewards', $data);
    }

    public function delete_voucher($id) {
        return $this->db->delete('rewards', ['id' => $id]);
    }

    public function get_brand_vouchers($brand_id) {
        return $this->db->select('r.id, r.title, r.image_name, r.points_required, 
                                 r.category, r.description, r.valid_until, 
                                 r.total_days, r.qty')
                        ->from('rewards r')
                        ->where('r.brand_id', $brand_id)
                        ->get()
                        ->result_array();
    }
}