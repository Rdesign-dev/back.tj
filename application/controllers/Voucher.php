<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Voucher_model','voucher');
    }

    public function tambahs() {
        $data['title'] = "Tambah Voucher";
        $data['brands'] = $this->voucher->get_all_brands(); // Fetch all brands
        $this->template->load('templates/dashboard', 'voucher/add', $data);
    }

    public function index() {
        // Mengambil data produk dari model
        $data['title'] = "Voucher";
        $data['vouchers'] = $this->voucher->find_all();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'voucher/index', $data);
    }
    public function tambah_save() {
        $this->form_validation->set_rules('title', 'Nama Voucher', 'required');
        $this->form_validation->set_rules('category', 'Kategori', 'required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required');
        $this->form_validation->set_rules('brand_id', 'Brand', 'required');
        $this->form_validation->set_rules('points_required', 'Points Required', 'required|numeric');
        $this->form_validation->set_rules('valid_until', 'Valid Until', 'required');
        $this->form_validation->set_rules('total_days', 'Total Days', 'required|numeric');
        $this->form_validation->set_rules('qty', 'Quantity', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Tambah Voucher";
            $data['brands'] = $this->voucher->get_all_brands();
            $data['validation_errors'] = validation_errors();
            $this->template->load('templates/dashboard', 'voucher/add', $data);
        } else {
            try {
                $brand_id = $this->input->post('brand_id');
                
                // Validate brand
                $brand = $this->voucher->get_brand_by_id($brand_id);
                if (!$brand) {
                    throw new Exception('Brand tidak ditemukan!');
                }

                // Generate voucher code
                $voucher_code = $this->generate_file_name($brand_id);

                // Upload configuration
                $config['upload_path'] = '../ImageTerasJapan/reward';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 5120;
                $config['file_name'] = $voucher_code;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('image_name')) {
                    throw new Exception($this->upload->display_errors());
                }

                $foto = $this->upload->data('file_name');
                $data = array(
                    'title' => $this->input->post('title'),
                    'image_name' => $foto,
                    'points_required' => $this->input->post('points_required'),
                    'category' => $this->input->post('category'),
                    'description' => $this->input->post('description'),
                    'valid_until' => $this->input->post('valid_until'),
                    'total_days' => $this->input->post('total_days'),
                    'qty' => $this->input->post('qty'),
                    'brand_id' => $brand_id
                );

                // Insert data using model
                if ($this->voucher->insert($data)) {
                    $this->session->set_flashdata('pesan', 
                        '<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan dengan kode: ' . $voucher_code . '</div>');
                    redirect(base_url('voucher'));
                } else {
                    throw new Exception('Gagal menyimpan data voucher');
                }

            } catch (Exception $e) {
                $this->session->set_flashdata('pesan', 
                    '<div class="alert alert-danger" role="alert">Error: ' . $e->getMessage() . '</div>');
                redirect(base_url('voucher/tambahs'));
            }
        }
    }
    public function edit_voucher($id) {
        $data['title'] = "Edit Voucher";
        // Mengambil data voucher berdasarkan ID dari tabel rewards
        $data['voucher'] = $this->voucher->get_by_id($id);
        // Tambahkan data brands
        $data['brands'] = $this->voucher->get_all_brands();
        
        if (!$data['voucher']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data tidak ditemukan!</div>');
            redirect('voucher');
        }
    
        $this->template->load('templates/dashboard', 'voucher/edit', $data);
    }

    public function delete($id) {
        if ($this->voucher->delete($this->voucher->table, 'id', $id)) {
            $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Dihapus</div>');
        } else {
            $this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">Data Gagal Dihapus</div>');
        }
        redirect('voucher');
    }

    public function update() {
        $id = $this->input->post('id');
        $data = array(
            'title' => $this->input->post('title'),
            'points_required' => $this->input->post('points_required'),
            'category' => $this->input->post('category'),
            'description' => $this->input->post('description'),
            'valid_until' => $this->input->post('valid_until'),
            'total_days' => $this->input->post('total_days'),
            'qty' => $this->input->post('qty'),
            'brand_id' => $this->input->post('brand_id') // Update brand_id
        );

        if (!empty($_FILES['image_name']['name'])) {
            $config['upload_path'] = '../ImageTerasJapan/reward';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image_name')) {
                $data['image_name'] = $this->upload->data('file_name');
            }
        }

        if ($this->voucher->update($this->voucher->table, 'id', $id, $data)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>');
        }

        redirect('voucher');
    }

    private function generate_file_name($brand_id) {
        // Get brand name from database
        $brand = $this->voucher->get_brand_by_id($brand_id);
        $brand_name = $brand ? preg_replace('/[^A-Za-z0-9]/', '', $brand->name) : 'UNKNOWN';
        
        $timestamp = time();
        $random_number = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        return "VC{$brand_name}{$timestamp}{$random_number}";
    }
}
