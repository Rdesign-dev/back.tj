<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Voucher_model','voucher');
        $this->load->model('admin_model','admin');
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

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Tambah Voucher";
            $data['brands'] = $this->admin->get_all_brands(); // Fetch all brands
            $this->template->load('templates/dashboard', 'voucher/add', $data);
        } else {
            $kode_cabang = $this->input->post('brand_id'); // Assuming brand_id is used as kode_cabang
            $file_name = $this->generate_file_name($kode_cabang);

            $config['upload_path'] = '../ImageTerasJapan/reward';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 5120; // 5 MB
            $config['file_name'] = $file_name;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image_name')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">' . $error . '</div>');
                redirect(base_url('voucher/tambahs'));
            } else {
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
                    'brand_id' => $this->input->post('brand_id') // Save brand_id
                );
                $this->db->insert('rewards', $data);
                $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                redirect(base_url('voucher'));
            }
        }
    }
    public function edit_voucher($id) {
        $data['title'] = "Edit Voucher";
        // Mengambil data voucher berdasarkan ID dari tabel rewards
        $data['voucher'] = $this->voucher->get_by_id($id);
        
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

    private function generate_file_name($kode_cabang) {
        $timestamp = time(); // Current timestamp
        $random_number = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // 3 random digits
        return "VC{$kode_cabang}{$timestamp}{$random_number}";
    }
}
