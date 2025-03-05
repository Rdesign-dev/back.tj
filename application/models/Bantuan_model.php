<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bantuan_model extends CI_Model {

    public $table = "pusatbantuan";
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
    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }
    public function getCurrentMessage()
    {
        $query = $this->db->get('templates');
        return $query->row();
    }
    public function getCurrentMessageId()
    {
    $query = $this->db->get('templates');
    $row = $query->row();
    return ($row) ? $row->id : null;
    
    // Debugging: Add this line to log the ID
    log_message('debug', 'Current Message ID: ' . $id);

    return $id;
    }
    public function updateMessageContent($newContent)
    {
    $currentMessageId = $this->getCurrentMessageId();

    if ($currentMessageId) {
        // Content already exists, perform an update
        $this->db->where('id', $currentMessageId);
        $this->db->update('templates', ['content' => $newContent]);
    } else {
        // Content doesn't exist, perform an insert
        $this->db->insert('templates', ['content' => $newContent]);
    }
    }
     public function isContentExists($content)
    {
        // Check if the content already exists in the 'templates' table
        $this->db->where('content', $content);
        $query = $this->db->get('templates');

        return $query->num_rows() > 0;
    }
    public function insertMessageContent($newContent)
    {
    // Assuming you have a 'content' field in your 'templates' table
    $data = ['content' => $newContent];

    $this->db->insert('templates', $data);
    }
}
