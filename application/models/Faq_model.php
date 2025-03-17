<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_model extends CI_Model {
    
    private $table = 'faq';

    public function get_all_faqs() {
        return $this->db->get($this->table)->result_array();
    }

    public function get_faq_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function insert_faq($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update_faq($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_faq($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function toggle_status($id) {
        $faq = $this->get_faq_by_id($id);
        if ($faq) {
            $new_status = ($faq['status'] == 'Active') ? 'inactive' : 'Active';
            return $this->update_faq($id, ['status' => $new_status]);
        }
        return false;
    }
}