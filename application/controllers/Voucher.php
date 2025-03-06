<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Voucher_model','voucher');
        $this->load->model('admin_model','admin');
    }

    public function tambahs(){
        $data['title'] = "Tambah Voucher";
        $this->template->load('templates/dashboard', 'voucher/add', $data);
    }

    public function index() {
        // Mengambil data produk dari model
        $data['title'] = "Voucher";
        $data['vouchers'] = $this->voucher->find_all();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'voucher/index', $data);
    }
    public function tambah_save(){
        //validasi server side
        $this->form_validation->set_rules('title','Nama Voucher','required');
        $this->form_validation->set_rules('category','Kategori','required');
        $this->form_validation->set_rules('description','Deskripsi','required');
        
        // Conditional validation based on category
        if ($this->input->post('category') != 'newmember') {
            $this->form_validation->set_rules('points_required','Poin','required|numeric');
            $this->form_validation->set_rules('valid_until','Berlaku Sampai','required');
            $this->form_validation->set_rules('total_days','Total Hari','required|numeric');
            $this->form_validation->set_rules('qty','Quantity','required|numeric');
        }
        
        if($this->form_validation->run() == FALSE){
            $data['title'] = "Tambah Voucher";
            $this->template->load('templates/dashboard', 'voucher/add', $data);
        } else {
            $config['upload_path'] = '../ImageTerasJapan/reward';
            $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
            $config['max_size'] = 1073741824;
            $config['max_width'] = 10000;
            $config['max_height'] = 10000;
            $this->load->library('upload', $config);
            
            if(!$this->upload->do_upload('image_name')){
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">'.$error.'</div>');
                redirect(base_url('voucher/tambahs'));
            } else {
                $foto = $this->upload->data();
                $foto = $foto['file_name'];
                
                // Set default values for newmember category
                $points_required = ($this->input->post('category') == 'newmember') ? 0 : $this->input->post('points_required');
                $valid_until = ($this->input->post('category') == 'newmember') ? null : date('Y-m-d H:i:s', strtotime($this->input->post('valid_until')));
                $total_days = ($this->input->post('category') == 'newmember') ? 0 : $this->input->post('total_days');
                $qty = ($this->input->post('category') == 'newmember') ? 0 : $this->input->post('qty');
                
                $data = array(
                    'title' => $this->input->post('title'),
                    'image_name' => $foto,
                    'points_required' => $points_required,
                    'category' => $this->input->post('category'),
                    'description' => $this->input->post('description'),
                    'valid_until' => $valid_until,
                    'total_days' => $total_days,
                    'qty' => $qty
                );
                $this->db->insert('rewards', $data);
                $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
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
            'qty' => $this->input->post('qty')
        );
    
        // Handle image upload if there's new image
        if (!empty($_FILES['image_name']['name'])) {
            $config['upload_path'] = '../ImageTerasJapan/reward';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = 'voucher-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);
    
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
}
