<?php
// filepath: c:\laragon\www\back.tj\application\controllers\Benefit.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Benefit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Benefit_model');
        $this->load->model('Level_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Data Benefit Level';
        $data['levels'] = $this->Level_model->get_all_levels();
        $data['benefits'] = array();
        foreach ($data['levels'] as $level) {
            $data['benefits'][$level['id']] = $this->Benefit_model->get_benefits_by_level_id($level['id']);
        }
        $data['contents'] = $this->load->view('benefit/index', $data, true);
        $this->load->view('templates/dashboard', $data);
    }

    public function edit($id) {
        $benefit = $this->Benefit_model->get_benefit_by_id($id);
        if (!$benefit) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Benefit tidak ditemukan.</div>');
            redirect('benefit');
        }

        $this->form_validation->set_rules('benefit_title', 'Benefit Title', 'required');
        $this->form_validation->set_rules('benefit_description', 'Benefit Description', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['benefit'] = $benefit;
            $data['title'] = 'Edit Benefit';
            // Panggil lewat template utama agar style ter-load
            $data['contents'] = $this->load->view('benefit/edit', $data, true);
            $this->load->view('templates/dashboard', $data);
        } else {
            $update = [
                'benefit_title' => $this->input->post('benefit_title'),
                'benefit_description' => $this->input->post('benefit_description')
            ];
            $this->Benefit_model->update_benefit($id, $update);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Benefit berhasil diupdate.</div>');
            redirect('benefit');
        }
    }

    public function delete($id) {
        $benefit = $this->Benefit_model->get_benefit_by_id($id);
        if ($benefit) {
            $this->Benefit_model->delete_benefit($id);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Benefit berhasil dihapus.</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Benefit tidak ditemukan.</div>');
        }
        redirect('benefit');
    }

    public function add() {
        $this->form_validation->set_rules('level_id', 'Level', 'required');
        $this->form_validation->set_rules('benefit_title', 'Benefit Title', 'required');
        $this->form_validation->set_rules('benefit_description', 'Benefit Description', 'required');

        $data['levels'] = $this->Level_model->get_all_levels();

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Benefit';
            $data['contents'] = $this->load->view('benefit/add', $data, true);
            $this->load->view('templates/dashboard', $data);
        } else {
            $insert = [
                'level_id' => $this->input->post('level_id'),
                'benefit_title' => $this->input->post('benefit_title'),
                'benefit_description' => $this->input->post('benefit_description')
            ];
            $this->Benefit_model->db->insert('user_benefits', $insert);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Benefit berhasil ditambahkan.</div>');
            redirect('benefit');
        }
    }
}