<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Faq_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'FAQ Management';
        $data['faqs'] = $this->Faq_model->get_all_faqs();
        $data['contents'] = $this->load->view('faq/index', $data, TRUE);
        $this->load->view('templates/dashboard', $data);
    }

    public function toggle_status($id) {
        $this->Faq_model->toggle_status($id);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Status FAQ berhasil diubah!</div>');
        redirect('faq');
    }

    public function delete($id) {
        $this->Faq_model->delete_faq($id);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">FAQ berhasil dihapus!</div>');
        redirect('faq');
    }

    public function add() {
        $data['title'] = 'Tambah FAQ';

        $this->form_validation->set_rules('question', 'Pertanyaan', 'required|trim');
        $this->form_validation->set_rules('answer', 'Jawaban', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['contents'] = $this->load->view('faq/add', $data, TRUE);
            $this->load->view('templates/dashboard', $data);
        } else {
            $input = $this->input->post(null, true);
            $insert = $this->Faq_model->insert_faq($input);
            if ($insert) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">FAQ berhasil ditambahkan!</div>');
                redirect('faq');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">FAQ gagal ditambahkan!</div>');
                redirect('faq/add');
            }
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit FAQ';
        $data['faq'] = $this->Faq_model->get_faq_by_id($id);

        if (!$data['faq']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data FAQ tidak ditemukan!</div>');
            redirect('faq');
        }

        $this->form_validation->set_rules('question', 'Pertanyaan', 'required|trim');
        $this->form_validation->set_rules('answer', 'Jawaban', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['contents'] = $this->load->view('faq/edit', $data, TRUE);
            $this->load->view('templates/dashboard', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->Faq_model->update_faq($id, $input);
            if ($update) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">FAQ berhasil diupdate!</div>');
                redirect('faq');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">FAQ gagal diupdate!</div>');
                redirect('faq/edit/' . $id);
            }
        }
    }
}