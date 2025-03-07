<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_banners() {
        return $this->db->get('banner')->result_array();
    }

    public function insert_banner($data) {
        return $this->db->insert('banner', $data);
    }

    public function toggle_status($id) {
        $this->db->where('id', $id);
        $banner = $this->db->get('banner')->row_array();

        if ($banner) {
            $new_status = ($banner['status'] == 'Active') ? 'inactive' : 'Active';
            $this->db->where('id', $id);
            $this->db->update('banner', ['status' => $new_status]);
        }
    }

    public function get_banner_by_id($id) {
        return $this->db->get_where('banner', ['id' => $id])->row_array();
    }

    public function delete_banner($id) {
        return $this->db->delete('banner', ['id' => $id]);
    }

    public function update_banner($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('banner', $data);
    }
}