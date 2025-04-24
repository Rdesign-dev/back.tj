<?php
// filepath: c:\laragon\www\back.tj\application\models\Benefit_model.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Benefit_model extends CI_Model {

    public function get_benefits_by_level_id($level_id) {
        $this->db->where('level_id', $level_id);
        return $this->db->get('user_benefits')->result_array();
    }

    public function get_benefit_by_id($id) {
        return $this->db->get_where('user_benefits', ['id' => $id])->row_array();
    }

    public function update_benefit($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('user_benefits', $data);
    }

    public function delete_benefit($id) {
        $this->db->where('id', $id);
        return $this->db->delete('user_benefits');
    }
}