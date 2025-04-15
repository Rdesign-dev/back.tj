<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Banner_model');
    }

    public function index() {
        $data['title'] = "Banner";
        $data['iklans'] = $this->Banner_model->get_all_banners();
        $data['contents'] = $this->load->view('banner/index', $data, TRUE);
        $this->load->view('templates/dashboard', $data);
    }

    public function add() {
        $data['title'] = "Tambah Banner";
        $data['contents'] = $this->load->view('banner/add', $data, TRUE);
        $this->load->view('templates/dashboard', $data);
    }

    public function save() {
        $config['upload_path'] = FCPATH . 'ImageTerasJapan/banner/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            $data = [
                'title' => $this->input->post('title'),
                'link' => $this->input->post('link'),
                'image' => $upload_data['file_name'],
                'status' => $this->input->post('status')
            ];

            $this->Banner_model->insert_banner($data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data Banner berhasil ditambahkan!</div>');
            redirect('banner');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
            redirect('banner/add');
        }
    }

    public function toggle_status($id) {
        $this->Banner_model->toggle_status($id);
        redirect('banner');
    }

    public function delete($id) {
        // Get banner info before delete
        $banner = $this->Banner_model->get_banner_by_id($id);
        
        if ($banner) {
            // Delete image file if exists
            $image_path = '../ImageTerasJapan/banner/' . $banner['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            
            // Delete database record
            $this->Banner_model->delete_banner($id);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data Banner berhasil dihapus!</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data Banner tidak ditemukan!</div>');
        }
        
        redirect('banner');
    }

    public function edit($id) {
        $data['title'] = "Edit Banner";
        $data['banner'] = $this->Banner_model->get_banner_by_id($id);
        
        if (!$data['banner']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data Banner tidak ditemukan!</div>');
            redirect('banner');
        }
        
        $data['contents'] = $this->load->view('banner/edit', $data, TRUE);
        $this->load->view('templates/dashboard', $data);
    }

    public function update($id) {
        $banner = $this->Banner_model->get_banner_by_id($id);
        if (!$banner) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data Banner tidak ditemukan!</div>');
            redirect('banner');
        }

        $data = [
            'title' => $this->input->post('title'),
            'link' => $this->input->post('link'),
            'status' => $this->input->post('status')
        ];

        // Handle image upload if new image is provided
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = FCPATH . 'ImageTerasJapan/banner/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                // Delete old image
                $old_image = $banner['image'];
                if ($old_image && file_exists($config['upload_path'] . $old_image)) {
                    unlink($config['upload_path'] . $old_image);
                }

                $upload_data = $this->upload->data();
                $data['image'] = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
                redirect('banner/edit/' . $id);
            }
        }

        $this->Banner_model->update_banner($id, $data);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data Banner berhasil diupdate!</div>');
        redirect('banner');
    }
}