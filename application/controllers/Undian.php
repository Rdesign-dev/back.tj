<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Undian extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Undian_model','undian');
        $this->load->model('Admin_model','admin');
    }

    public function index() {
        // Mengambil data produk dari model
        $data['title'] = "Daftar Undian";
        $data['undians'] = $this->undian->find_all();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'undian/index', $data);
    }
    public function inputPoinUndian(){
        $data['title'] = "Poin Undian";
        $data['undians'] = $this->undian->find_poin();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'undian/indexUndian', $data);
    }
    public function inputPoin(){
            //validasi server side
            $this->form_validation->set_rules('poin','poin','required');
            if($this->form_validation->run() == FALSE){
                $data['title'] = "Poin Undian";
                $this->template->load('templates/dashboard', 'undian/inputPoinUndian', $data);
            } else {
                    $config['upload_path'] = '../fotoundian/';
                    $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg|webp';
                    $config['max_size'] = 262144;
                    $config['max_width'] = 10000;
                    $config['max_height'] = 10000;
                    $this->load->library('upload', $config);
                    if(!$this->upload->do_upload('gambar')){
                        $data['title'] = "Poin Undian";
                        $this->template->load('templates/dashboard', 'undian/inputPoinUndian', $data);
                    }else{
                        $gambar = $this->upload->data();
                        $gambar = $gambar['file_name'];
                        $poin= $this->input->post('poin');
                        $data = array(
                            'poin' => $poin,
                            'gambar' => $gambar,
                        );
                        var_dump($data);
                        $this->db->insert('poinundian',$data);
                        $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                        redirect(base_url('undian/inputPoinUndian'));
                    }
                
                
            }
    }
    public function edit_undian($id){
        $id = encode_php_tags($id);
        $this->form_validation->set_rules("poin","poin","required");
        if($this->form_validation->run() == FALSE){
            $data['title'] = "Edit Undian";
            $data['undian'] = $this->admin->get('poinundian', ['id' => $id]);
            $this->template->load('templates/dashboard', 'undian/edit', $data);
        }else{
            $config['upload_path'] = '../fotoundian/';
            $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg|webp';
            $config['max_size'] = 262144;
            $config['max_width'] = 10000;
            $config['max_height'] = 10000;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('gambar')){
                $link = $this->input->post("link");
                $data = array(
                    'poin' => $poin,
                );
                var_dump($data);
                $this->db->where('id',$id);
                $this->db->update('poinundian',$data);
                $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                redirect('undian/inputPoinUndian');
            }else{
                $gambar = $this->upload->data();
                $gambar = $gambar['file_name'];
                $poin = $this->input->post("poin");
                $data = array(
                    'poin' => $poin,
                    'gambar' => $gambar
                );
                var_dump($data);
                $this->db->where('id',$id);
                $this->db->update('poinundian',$data);
                $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Diupdate</div>');
                redirect('undian/inputPoinUndian');
            }
            
        }
    }
    public function delete($getId)
        {
        $id = encode_php_tags($getId);
        if ($this->undian->delete('poinundian', 'id', $id)) {
            $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Dihapus</div>');
        } else {
            $this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">Data Gagal Dihapus</div>');
        }
        redirect('undian/inputPoinUndian');
        }

    }

