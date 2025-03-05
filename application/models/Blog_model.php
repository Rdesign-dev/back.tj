<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {
    
    private $table = 'news_event';

    public function __construct() {
        parent::__construct();
    }

    public function find_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_status($id)
    {
        $result = $this->db->select('status')
                          ->where('id', $id)
                          ->get($this->table)
                          ->row();
        return $result ? $result->status : null;
    }

    public function update_status($id, $status)
    {
        return $this->db->where('id', $id)
                        ->update($this->table, ['status' => $status]);
    }

    public function delete($id)
    {
        // Get image name before delete
        $image = $this->db->select('image')
                         ->where('id', $id)
                         ->get($this->table)
                         ->row();
                         
        if ($image) {
            $file_path = 'C:/laragon/www/ImageTerasJapan/news_event/' . $image->image;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        return $this->db->where('id', $id)->delete($this->table);
    }
}
