<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Brand_model', 'brand');
    }

    public function index() {
        $data['title'] = "Brand Detail";
        $data['brands'] = $this->brand->find_all();
        $this->template->load('templates/dashboard', 'brand/index', $data);
    }

    public function get_brand_details($id) {
        $brand = $this->brand->get_by_id($id);
        if ($brand) {
            echo json_encode($brand);
        } else {
            echo json_encode(['error' => 'Brand not found']);
        }
    }

    public function edit($id) {
        $data['title'] = "Edit Brand";
        $data['brand'] = $this->brand->get_by_id($id);

        if (!$data['brand']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data tidak ditemukan!</div>');
            redirect('brand');
        }

        $this->template->load('templates/dashboard', 'brand/edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('name', 'Nama Brand', 'required|trim');
        $this->form_validation->set_rules('desc', 'Deskripsi', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit Brand";
            $data['brand'] = $this->brand->get_by_id($id);
            $this->template->load('templates/dashboard', 'brand/edit', $data);
        } else {
            $data = [
                'name' => $this->input->post('name', true),
                'desc' => $this->input->post('desc', true),
                'instagram' => $this->input->post('instagram'),
                'tiktok' => $this->input->post('tiktok'),
                'wa' => $this->input->post('wa'),
                'web' => $this->input->post('web')
            ];

            // Handle logo upload
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/logo/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['file_name'] = 'logo-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('image')) {
                    // Delete old image
                    $old_image = $this->brand->get_by_id($id)['image'];
                    if ($old_image != null && file_exists('../ImageTerasJapan/logo/' . $old_image)) {
                        unlink('../ImageTerasJapan/logo/' . $old_image);
                    }
                    $data['image'] = $this->upload->data('file_name');
                }
            }

            // Handle banner upload
            if (!empty($_FILES['banner']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/banner/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['file_name'] = 'banner-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('banner')) {
                    // Delete old banner
                    $old_banner = $this->brand->get_by_id($id)['banner'];
                    if ($old_banner != null && file_exists('../ImageTerasJapan/banner/' . $old_banner)) {
                        unlink('../ImageTerasJapan/banner/' . $old_banner);
                    }
                    $data['banner'] = $this->upload->data('file_name');
                }
            }

            if ($this->brand->update($id, $data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Brand berhasil diupdate!</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Brand gagal diupdate!</div>');
            }
            redirect('brand');
        }
    }

    public function add() {
        $data['title'] = "Tambah Brand";
        $this->template->load('templates/dashboard', 'brand/add', $data);
    }

    public function save() {
        $this->form_validation->set_rules('name', 'Nama Brand', 'required|trim');
        $this->form_validation->set_rules('desc', 'Deskripsi', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Brand";
            $this->template->load('templates/dashboard', 'brand/add', $data);
        } else {
            $data = [
                'name' => $this->input->post('name', true),
                'desc' => $this->input->post('desc', true),
                'instagram' => $this->input->post('instagram'),
                'tiktok' => $this->input->post('tiktok'),
                'wa' => $this->input->post('wa'),
                'web' => $this->input->post('web')
            ];

            // Handle logo upload
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/logo/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['file_name'] = 'logo-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('image')) {
                    $data['image'] = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
                    redirect('brand/add');
                }
            }

            // Handle banner upload
            if (!empty($_FILES['banner']['name'])) {
                $config['upload_path'] = '../ImageTerasJapan/banner/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['file_name'] = 'banner-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);

                $this->upload->initialize($config);

                if ($this->upload->do_upload('banner')) {
                    $data['banner'] = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>');
                    redirect('brand/add');
                }
            }

            if ($this->brand->insert($data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Brand berhasil ditambahkan!</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Brand gagal ditambahkan!</div>');
            }
            redirect('brand');
        }
    }
}