<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Blog_model','blog');
        $this->load->model('admin_model','admin');
    }

    public function tambahs(){
        $data['title'] = "Tambah Voucher";
        $this->template->load('templates/dashboard', 'blog/add', $data);
    }

    public function index() {
        // Mengambil data produk dari model
        $data['title'] = "Blog";
        $data['blogs'] = $this->blog->find_all();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'blog/index', $data);
    }

    public function tambah_save(){
        $this->form_validation->set_rules('judul','Judul','required');
        $this->form_validation->set_rules('konten','Konten','required');
        if($this->form_validation->run() == FALSE){
            $data['title'] = "Tambah Blog";
            $this->template->load('templates/dashboard', 'blog/add', $data);
        } else {
                $config['upload_path'] = '../fotoblog/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
                $config['max_size'] = 1048576;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('gambar')){
                    $data['title'] = "Tambah Blog";
                    $this->template->load('templates/dashboard', 'blog/add', $data);
                }else{
                    $gambar = $this->upload->data();
                    $gambar = $gambar['file_name'];
                    $judul= $this->input->post('judul');
                    $konten= $this->input->post('konten');
                    $data = array(
                        'gambar' => $gambar,
                        'judul' => $judul,
                        'konten' => $konten
                    );
                    var_dump($data);
                    $this->db->insert('blog',$data);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                    redirect(base_url('blog'));
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
            public function edit_blog($id){
                $id = encode_php_tags($id);
                $this->form_validation->set_rules('judul','Judul','required');
                $this->form_validation->set_rules('konten','Konten','required');
                if($this->form_validation->run() == FALSE){
                    $data['title'] = "Edit Blog";
                    $data['blog'] = $this->admin->get('blog', ['id' => $id]);
                    $this->template->load('templates/dashboard', 'blog/edit', $data);
                }else{
                    $config['upload_path'] = '../fotoblog/';
                    $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
                    $config['max_size'] = 2048000;
                    $config['max_width'] = 10000;
                    $config['max_height'] = 10000;
                    $this->load->library('upload', $config);
                    if(!$this->upload->do_upload('gambar')){
                        $judul= $this->input->post('judul');
                        $konten= $this->input->post('konten');
                        $data = array(
                            'judul' => $judul,
                            'konten' => $konten,
                        );
                        var_dump($data);
                        $this->db->where('id',$id);
                        $this->db->update('blog',$data);
                        $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                        redirect('blog');
                    }else{
                        $gambar = $this->upload->data();
                        $gambar = $gambar['file_name'];
                        $judul= $this->input->post('judul');
                        $konten= $this->input->post('konten');
                        $data = array(
                            'gambar' => $gambar,
                            'judul' => $judul,
                            'konten' => $konten,
                        );
                        var_dump($data);
                        $this->db->where('id',$id);
                        $this->db->update('blog',$data);
                        $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                        redirect('blog');
                    }
                    
                }
            }
        public function delete($getId)
        {
        $id= encode_php_tags($getId);
        if ($this->blog->delete('blog', 'id', $id)) {
            $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Dihapus</div>');
        } else {
            $this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">Data Gagal Dihapus</div>');
        }
        redirect('blog');
        }
        public function toggle($getId)
        {
        $id = encode_php_tags($getId);
        $status = $this->admin->get('blog', ['id' => $id])['isActive'];
        $toggle = $status ? 0 : 1; //Jika user aktif maka nonaktifkan, begitu pula sebaliknya
        $pesan = $toggle ? 'blog diaktifkan.' : 'blog dinonaktifkan.';

        if ($this->admin->update('blog', 'id', $id, ['isActive' => $toggle])) {
            set_pesan($pesan);
        }
        redirect('blog');
        }
}
