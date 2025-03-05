<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Cabang_model','cabang');
        $this->load->model('Transaksi_model','transaksi');
    }

    public function tambahs(){
        $data['title'] = "Tambah cabang";
        $this->template->load('templates/dashboard', 'cabang/add', $data);
    }

    public function index() {
        // Mengambil data produk dari model
        $data['title'] = "Management Cabang";
        $data['cabangs'] = $this->cabang->find_all();

        // Memuat tampilan daftar produk
        $this->template->load('templates/dashboard', 'cabang/index', $data);
    }

    public function tambah_save(){
        //validasi server side
        $this->form_validation->set_rules('kodecabang','Kode Cabang','required');
        $this->form_validation->set_rules('namacabang','Nama Cabang','required');
        $this->form_validation->set_rules('alamat','Alamat','required');
        if($this->form_validation->run() == FALSE){
            //validasi menemukan error
            $data['title'] = "Tambah cabang";
            $this->template->load('templates/dashboard', 'cabang/add', $data);
        } else {
                $kodecabang = $this->input->post('kodecabang');
                $namacabang = $this->input->post('namacabang');
                $alamat = $this->input->post('alamat');
                $jumlahtransaksi = 0;
                $data = array(
                    'kodecabang' => $kodecabang,
                    'namacabang' => $namacabang,
                    'alamat' => $alamat,
                    'jumlahtransaksi' => $jumlahtransaksi,
                );
                var_dump($data);
                $this->db->insert('cabang',$data);
                $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                redirect(base_url('cabang'));
            }
        }
        public function getTransaksiCabang()
        {
            $data['title'] = "Riwayat Transaksi Member";
            $idcabang = $this->uri->segment('3');
            $data['trans'] = $this->transaksi->getTransaksiByIdMemberWithDetails($idcabang);
            $this->template->load('templates/dashboard', 'cabang/history', $data);

        }
}
