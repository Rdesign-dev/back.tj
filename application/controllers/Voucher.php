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
        $this->form_validation->set_rules('kodevoucher','Kode Voucher','required');
        $this->form_validation->set_rules('namavoucher','Nama Voucher','required');
        $this->form_validation->set_rules('poin','Poin','required');
        $this->form_validation->set_rules('syarat','Syarat','required');
        $this->form_validation->set_rules('syarattukar','Syarat Tukar','required');
        $this->form_validation->set_rules('kategori','Kategori','required');
        if ($this->input->post('kategori') == 'memberbiasa') {
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than[0]', [
                'required' => 'Field ini untuk Kategori Member Biasa.',
                'numeric' => 'Field Quantity ini harus number.',
                'greater_than' => 'Field Quantity ini harus lebih dari 0.'
            ]);
        } else if($this->input->post('kategori') == 'memberbaru') {
        // If the category is "Member Baru," set a default value for Quantity
            $this->input->post('quantity', 0);
        }else if($this->input->post('kategori') == 'kodereferal'){
            $this->input->post('quantity', 0);
        }
        if($this->form_validation->run() == FALSE){
            $data['title'] = "Tambah Voucher";
            $this->template->load('templates/dashboard', 'voucher/add', $data);
        } else {
                $config['upload_path'] = '../fotovoucher/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
                $config['max_size'] = 1073741824;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('foto')){
                    $error = $this->upload->display_errors();
                    echo $error;
                }else{
                    $foto = $this->upload->data();
                    $foto = $foto['file_name'];
                    $kodevoucher= $this->input->post('kodevoucher');
                    $namavoucher= $this->input->post('namavoucher');
                    $poin= $this->input->post('poin');
                    $syarat= $this->input->post('syarat');
                    $syarattukar= $this->input->post('syarattukar');
                    $kategori= $this->input->post('kategori');
                    $quantity= $this->input->post('quantity');
                    $data = array(
                        'title' => $this->input->post('namavoucher'),
                        'image_name' => $foto,
                        'points_required' => $this->input->post('poin'),
                        'category' => $this->input->post('kategori') == 'memberbiasa' ? 'oldmember' : 
                                     ($this->input->post('kategori') == 'memberbaru' ? 'newmember' : 'code'),
                        'description' => $this->input->post('syarat'),
                        'valid_until' => date('Y-m-d H:i:s', strtotime('+30 days')), // contoh 30 hari
                        'total_days' => 30,
                        'qty' => $this->input->post('quantity')
                    );
                    var_dump($data);
                    $this->db->insert('voucher',$data);
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
            $config['upload_path'] = './fotovoucher/';
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
