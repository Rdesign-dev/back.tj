<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Feedback_model', 'feedback');
    }

    public function index() {
        $data['title'] = "Feedback Member";
        $data['feedback'] = $this->feedback->getAllFeedback();
        $this->template->load('templates/dashboard', 'feedback/index', $data);
    }

    public function delete($id) 
    {
        $this->feedback->deleteFeedback($id);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Feedback berhasil dihapus</div>');
        redirect('feedback');
    }
}