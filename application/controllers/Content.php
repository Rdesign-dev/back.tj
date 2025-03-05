<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Content_model','content');
        $this->load->model('Admin_model','admin');
    }

    public function tambahs(){
        $data['title'] = "Tambah Content";
        $this->template->load('templates/dashboard', 'content/add', $data);
    }

    public function index() {
        // Mengambil data produk dari model
        $data['title'] = "Management Content";
        $data['contents'] = $this->content->find_all();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'content/index', $data);
    }

    public function tambah_save(){
        //validasi server side
        $this->form_validation->set_rules('konten','Konten','required');
        if($this->form_validation->run() == FALSE){
            //validasi menemukan error
            $data['title'] = "Tambah Content";
            $this->template->load('templates/dashboard', 'content/add', $data);
        } else {
                $config['upload_path'] = '../fotokonten/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
                $config['max_size'] = 1048576;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('gambar')){
                    $data['title'] = "Tambah Content";
                    $this->template->load('templates/dashboard', 'content/add', $data);
                }else{
                $gambar = $this->upload->data();
                $gambar = $gambar['file_name'];
                $konten = $this->input->post('konten');
                $data = array(
                    'gambar' => $gambar,
                    'konten' => $konten,
                );
                var_dump($data);
                $this->db->insert('content',$data);
                $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                redirect(base_url('content'));
                }
                
            }
        }
        public function delete($getId)
        {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('content', 'id', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('content');
        }
        public function toggle($getId)
        {
        $id = encode_php_tags($getId);
        $status = $this->admin->get('content', ['id' => $id])['isActive'];
        $toggle = $status ? 0 : 1; //Jika user aktif maka nonaktifkan, begitu pula sebaliknya
        $pesan = $toggle ? 'Konten diaktifkan.' : 'Konten dinonaktifkan.';

        if ($this->admin->update('content', 'id', $id, ['isActive' => $toggle])) {
            set_pesan($pesan);
        }
        redirect('content');
        }
}
