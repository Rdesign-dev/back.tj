<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bantuan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Bantuan_model','bantuan');
        $this->load->model('admin_model','admin');
    }

    public function tambahs(){
        $data['title'] = "Pusat Bantuan";
        $this->template->load('templates/dashboard', 'bantuan/add', $data);
    }

    public function index() {
        // Mengambil data produk dari model
        $data['title'] = "Management Pusat Bantuan";
        $data['bantuans'] = $this->bantuan->find_all();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'bantuan/index', $data);
    }

    public function tambah_save(){
        //validasi server side
        $this->form_validation->set_rules('judul','Judul','required');
        $this->form_validation->set_rules('isi','Isi','required');
        $this->form_validation->set_rules('tags','Tags','required');
        if($this->form_validation->run() == FALSE){
            //validasi menemukan error
            $data['title'] = "Pusat Bantuan";
            $this->template->load('templates/dashboard', 'bantuan/add', $data);
        } else {
                $judul = $this->input->post('judul');
                $isi = $this->input->post('isi');
                $tags = $this->input->post('tags');
                $data = array(
                    'judul' => $judul,
                    'isi' => $isi,
                    'tags' => $tags,
                );
                var_dump($data);
                $this->db->insert('pusatbantuan',$data);
                $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                redirect(base_url('bantuan'));
            }
        }
        public function edit_bantuan($id){
            $id = encode_php_tags($id);
            $this->form_validation->set_rules('judul','Judul','required');
            $this->form_validation->set_rules('isi','Isi','required');
            $this->form_validation->set_rules('tags','tags','required');
            if($this->form_validation->run() == FALSE){
                $data['title'] = "Edit Pusat Bantuan";
                $data['bantuan'] = $this->admin->get('pusatbantuan', ['id' => $id]);
                $this->template->load('templates/dashboard', 'bantuan/edit', $data);
            }else{
                $judul= $this->input->post('judul');
                $isi= $this->input->post('isi');
                $tags= $this->input->post('tags');
                $data = array(
                    'judul' => $judul,
                    'isi' => $isi,
                    'tags' => $tags,
                    
                );
                var_dump($data);
                $this->db->where('id',$id);
                $this->db->update('pusatbantuan',$data);
                $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                redirect('bantuan');
        }
}
        public function edit_message_template()
        {
            $data['title'] = "Pesan OTP Wa";
            $data['currentMessage'] = $this->bantuan->getCurrentMessage();
            
            $this->template->load('templates/dashboard','bantuan/editKonten',$data);
        }
        public function update_message() {
        // Handle the form submission to update the message
        if ($this->input->post()) {
            $newContent = $this->input->post('konten');
        if ($this->bantuan->isContentExists($newContent)) {
            // Content already exists, perform an update
            $this->bantuan->updateMessageContent($newContent);
        } else {
            // Content doesn't exist, perform an insert
            $this->bantuan->insertMessageContent($newContent);
        }
            redirect('bantuan/edit_message_template');
        }
    }
}