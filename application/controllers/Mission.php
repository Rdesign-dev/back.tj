<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mission extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mission_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Data Missions';
        $data['missions'] = $this->Mission_model->get_all();
        $data['contents'] = $this->load->view('mission/index', $data, true);
        $this->load->view('templates/dashboard', $data);
    }

    public function edit($id) {
        $mission = $this->Mission_model->get_by_id($id);
        if (!$mission) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Mission tidak ditemukan.</div>');
            redirect('mission');
        }

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('point_reward', 'Point Reward', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            $data['mission'] = $mission;
            $data['title'] = 'Edit Mission';
            $data['contents'] = $this->load->view('mission/edit', $data, true);
            $this->load->view('templates/dashboard', $data); // <-- panggil lewat dashboard
        } else {
            $update = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'point_reward' => $this->input->post('point_reward')
            ];
            $this->Mission_model->update($id, $update);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Mission berhasil diupdate.</div>');
            redirect('mission');
        }
    }

    public function delete($id) {
        $mission = $this->Mission_model->get_by_id($id);
        if ($mission) {
            $this->Mission_model->delete($id);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Mission berhasil dihapus.</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Mission tidak ditemukan.</div>');
        }
        redirect('mission');
    }

    public function add() {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('point_reward', 'Point Reward', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Mission';
            $data['contents'] = $this->load->view('mission/add', $data, true);
            $this->load->view('templates/dashboard', $data);
        } else {
            $insert = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'point_reward' => $this->input->post('point_reward')
            ];
            $this->Mission_model->insert($insert);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Mission berhasil ditambahkan.</div>');
            redirect('mission');
        }
    }
}