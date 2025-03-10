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
        $this->form_validation->set_rules('branch_code','Kode Cabang','required');
        $this->form_validation->set_rules('branch_name','Nama Cabang','required');
        $this->form_validation->set_rules('address','Alamat','required');
        if($this->form_validation->run() == FALSE){
            $data['title'] = "Tambah cabang";
            $this->template->load('templates/dashboard', 'cabang/add', $data);
        } else {
                $data = array(
                    'branch_code' => $this->input->post('branch_code'),
                    'branch_name' => $this->input->post('branch_name'),
                    'address' => $this->input->post('address'),
                    'transaction_count' => 0,
                );
                $this->db->insert('branch',$data);
                $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                redirect(base_url('cabang'));
            }
        }
        
        public function getTransaksiCabang($branch_id)
        {
            $data['title'] = "Riwayat Transaksi Cabang";
            
            $this->db->select('t.transaction_codes, t.created_at, b.branch_name, t.amount, a.Name as cashier_name')
                     ->from('transactions t')
                     ->join('branch b', 'b.id = t.branch_id')
                     ->join('accounts a', 'a.id = t.account_cashier_id')
                     ->where('t.branch_id', $branch_id)
                     ->where('t.transaction_type', 'Teras Japan Payment')
                     ->order_by('t.created_at', 'DESC');
                     
            $data['trans'] = $this->db->get()->result_array();
            $data['branch'] = $this->db->get_where('branch', ['id' => $branch_id])->row_array();
            
            $this->template->load('templates/dashboard', 'cabang/history', $data);
        }
}
