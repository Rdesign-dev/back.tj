<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('Blog_model','blog');
    }

    public function index() {
        $data['title'] = "News & Event";
        $data['blogs'] = $this->blog->find_all();
        $this->template->load('templates/dashboard', 'blog/index', $data);
    }

    public function tambah() 
    {
        $data['title'] = "Tambah News & Event";
        $this->template->load('templates/dashboard', 'blog/add', $data);
    }

    public function tambah_save()
    {
        $data['title'] = "Tambah News & Event";
        
        $this->form_validation->set_rules('title', 'Judul', 'required|trim');
        $this->form_validation->set_rules('captions', 'Caption', 'required|trim');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->template->load('templates/dashboard', 'blog/add', $data);
        } else {
            $config['upload_path'] = 'C:/laragon/www/ImageTerasJapan/news_event/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048; // 2MB
            $config['encrypt_name'] = TRUE;
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);
            
            if(!$this->upload->do_upload('image')){
                set_pesan($this->upload->display_errors(), false);
                redirect('blog/tambah');
            } else {
                $uploaded_data = $this->upload->data();
                
                $data = [
                    'title' => $this->input->post('title', true),
                    'captions' => $this->input->post('captions', true),
                    'description' => $this->input->post('description', true),
                    'image' => $uploaded_data['file_name'],
                    'status' => 'inactive'
                ];
                
                if($this->blog->insert($data)){
                    set_pesan('Data berhasil disimpan');
                    redirect('blog');
                } else {
                    set_pesan('Gagal menyimpan data', false);
                    redirect('blog/tambah');
                }
            }
        }
    }

    public function edit_blog($getId) {
        $id = encode_php_tags($getId);
        $this->form_validation->set_rules('title', 'Judul', 'required|trim');
        $this->form_validation->set_rules('captions', 'Caption', 'required|trim');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit News & Event";
            $data['blog'] = $this->db->get_where('news_event', ['id' => $id])->row_array();
            $this->template->load('templates/dashboard', 'blog/edit', $data);
        } else {
            $data = [
                'title' => $this->input->post('title', true),
                'captions' => $this->input->post('captions', true),
                'description' => $this->input->post('description', true)
            ];

            // Cek jika ada gambar yang akan diupload
            if ($_FILES['image']['name']) {
                $config['upload_path']   = 'C:/laragon/www/ImageTerasJapan/news_event/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = 2048;
                $config['encrypt_name']  = TRUE;

                // Buat direktori jika belum ada
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    // Hapus file lama
                    $old_image = $this->db->get_where('news_event', ['id' => $id])->row_array()['image'];
                    if ($old_image != 'default.png') {
                        $old_file = $config['upload_path'] . $old_image;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                    // Set nama file baru
                    $data['image'] = $this->upload->data('file_name');
                } else {
                    set_pesan($this->upload->display_errors(), false);
                    redirect('blog/edit_blog/' . $id);
                }
            }

            $this->db->where('id', $id);
            $this->db->update('news_event', $data);
            set_pesan('Data berhasil diubah');
            redirect('blog');
        }
    }

    public function delete($getId) {
        $id = encode_php_tags($getId);
        
        // Ambil info file gambar
        $image = $this->db->get_where('news_event', ['id' => $id])->row_array()['image'];
        
        // Hapus file gambar
        if ($image != 'default.png') {
            $file_path = 'C:/laragon/www/ImageTerasJapan/news_event/' . $image;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Hapus data dari database
        $this->db->delete('news_event', ['id' => $id]);
        set_pesan('Data berhasil dihapus');
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

    public function toggle_status($getId)
    {
        $id = encode_php_tags($getId);
        $current_status = $this->blog->get_status($id);
        $new_status = ($current_status == 'Active') ? 'inactive' : 'Active';

        if ($this->blog->update_status($id, $new_status)) {
            set_pesan('Status berhasil diubah');
        } else {
            set_pesan('Gagal mengubah status', false);
        }
        redirect('blog');
    }
}
