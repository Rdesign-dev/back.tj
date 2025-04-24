<?php
// filepath: c:\laragon\www\back.tj\application\controllers\Level.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Level extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Level_model');
    }

    public function index() {
        $data['title'] = 'Data Level Member';
        $data['levels'] = $this->Level_model->get_all_levels();
        $data['contents'] = $this->load->view('level/index', $data, true);
        $this->load->view('templates/dashboard', $data);
    }

    public function edit($id) {
        $data['title'] = 'Edit Data Level Member';
        $data['level'] = $this->Level_model->get_level_by_id($id);

        // Validasi form
        $this->form_validation->set_rules('min_spending', 'Minimal Spending', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            // Load form edit jika validasi gagal
            $data['contents'] = $this->load->view('level/edit', $data, TRUE);
            $this->load->view('templates/dashboard', $data);
        } else {
            // Update data jika validasi berhasil
            $data = array('min_spending' => $this->input->post('min_spending'));
            $this->Level_model->update_level($id, $data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data berhasil diupdate.</div>');
            redirect('level');
        }
    }
}