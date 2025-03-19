<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback_model extends CI_Model {
    
    public function getAllFeedback() {
        return $this->db->select('id, feedback_text, category, rating, created_at')
                    ->from('feedback')
                    ->order_by('created_at', 'DESC')
                    ->get()
                    ->result();
    }

    public function deleteFeedback($id) {
        return $this->db->where('id', $id)
                    ->delete('feedback');
    }
}