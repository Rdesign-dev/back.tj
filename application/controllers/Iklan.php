<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iklan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Iklan_model','iklan');
        $this->load->model('admin_model','admin');
    }

    public function tambahs(){
        $data['title'] = "Tambah Iklan Promosi";
        $this->template->load('templates/dashboard', 'iklan/add', $data);
    }

    public function index() {
        // Mengambil data produk dari model
        $data['title'] = "Iklan Promosi";
        $data['iklans'] = $this->iklan->find_all();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'iklan/index', $data);
    }

    public function tambah_save(){
        //validasi server side
        $this->form_validation->set_rules('link','Link','required');
        if($this->form_validation->run() == FALSE){
            $data['title'] = "Tambah Iklan Promosi";
            $this->template->load('templates/dashboard', 'iklan/add', $data);
        } else {
                $config['upload_path'] = '../fotoiklan/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
                $config['max_size'] = 1073741824;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('foto')){
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">' . $error['error'] . '</div>');
                    redirect('iklan');
                }else{
                    $foto = $this->upload->data();
                    $foto = $foto['file_name'];
                    $link= $this->input->post('link');
                    $data = array(
                        'link' => $link,
                        'foto' => $foto,
                    );
                    var_dump($data);
                    $this->db->insert('iklan',$data);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                    redirect(base_url('iklan'));
                }
            
            }
        }
        public function edit($id){
            $data['title'] = "Edit Iklan Promosi";
            $id = $this->uri->segment('3');
        
            if (!empty($id)) {
                $data['iklan'] = $this->iklan->cari_detail_id($id);
        
                // Pastikan member ditemukan sebelum memuat template
                if ($data['iklan']) {
                    // Load template dengan data yang telah disiapkan
                    $this->template->load('templates/dashboard', 'iklan/edit', $data);
                } else {
                    $this->session->set_flashdata('pesan','<div class="alert alert-error" role="alert">Data Tidak Ditemukan</div>');
                    redirect(base_url('iklan'));
                }
            } else {
                $this->session->set_flashdata('pesan','<div class="alert alert-error" role="alert">Data Tidak Valid</div>');
                    redirect(base_url('iklan'));
            }
            }
            public function edit_iklan($id){
                $id = encode_php_tags($id);
                $this->form_validation->set_rules("link","link","required");
                if($this->form_validation->run() == FALSE){
                    $data['title'] = "Edit Iklan";
                    $data['iklan'] = $this->admin->get('iklan', ['id' => $id]);
                    $this->template->load('templates/dashboard', 'iklan/edit', $data);
                }else{
                    $config['upload_path'] = '../fotoiklan/';
                    $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
                    $config['max_size'] = 1073741824;
                    $config['max_width'] = 10000;
                    $config['max_height'] = 10000;
                    $this->load->library('upload', $config);
                    $upload_status = $this->upload->do_upload('foto');
                    if(!$upload_status && !empty($_FILES['foto']['name'])){
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">' . $error['error'] . '</div>');
                        redirect('iklan');
                    }else{
                        $foto = ($upload_status) ? $this->upload->data('file_name') : '';
                        $link = $this->input->post("link");
                        $data = array(
                            'link' => $link,
                        );
                        if(!empty($foto)){
                            $data['foto'] = $foto;
                        }
                        var_dump($data);
                        $this->db->where('id',$id);
                        $this->db->update('iklan',$data);
                        $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                        redirect('iklan');
                    }
                    
                }
            }
        public function delete($getId)
        {
        $id = encode_php_tags($getId);
        if ($this->iklan->delete('iklan', 'id', $id)) {
            $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Dihapus</div>');
        } else {
            $this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">Data Gagal Dihapus</div>');
        }
        redirect('iklan');
        }
}
