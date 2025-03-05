<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi_model', 'transaksi');
        $this->load->model('topup_model', 'topup');
        $this->load->model('member_model','member');
        $this->load->model('cabang_model','cabang');
        $this->load->library('form_validation');
    }

    public function tambah() {
        // Retrieve transaction data from the model
        $data['transaksi'] = $this->transaksi->getAllTransaksi();

        // Load the view to display transaction data
        $this->load->view('transaksi/index', $data);
    }


    public function tambah_save() {
        // Your tambah_save method code here
        //validasi server side
    $this->form_validation->set_rules('kodetransaksi','Kode transaksi','required');
    $this->form_validation->set_rules('tanggaltransaksi','Tanggal Transaksi','required');
    $this->form_validation->set_rules('kodeproduk','Kode Produk','required');
    $this->form_validation->set_rules('harga','Harga','required');
    $this->form_validation->set_rules('jumlahbeli','Jumlah Beli','required');
    $this->form_validation->set_rules('kodeproduk','Kode Produk','required');
    $this->form_validation->set_rules('idmember','Id Member','required');
    $this->form_validation->set_rules('total','Total','required');
    if($this->form_validation->run() == FALSE){
        //validasi menemukan error
        $this->tambahs();
    } else {
            $kodetransaksi = $this->input->post('kodetransaksi');
            $tanggaltransaksi = $this->input->post('tanggaltransaksi');
            $kodeproduk = $this->input->post('kodeproduk');
            $harga = $this->input->post('harga');
            $jumlahbeli = $this->input->post('jumlahbeli');
            $idmember = $this->input->post('idmember');
            $total = $this->input->post('total');
            $data = array(
                'kodetransaksi' => $kodetransaksi,
                'tanggaltransaksi' => $tanggaltransaksi,
                'kodeproduk' => $kodeproduk,
                'harga' => $harga,
                'jumlahbeli' => $jumlahbeli,
                'idmember' => $idmember,
                'total' => $total,
            );
            var_dump($data);
            if ($this->db->insert('transaksi', $data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data Berhasil Ditambahkan</div>');
                redirect(base_url('transaksi/index'));
            } else {
                echo "Error: " . $this->db->error(); // Display the database error
            }
        }
    
    }
    
    public function detail($id) {
        // Your detail method code here
    }
    public function index(){
        $data['member'] = $this->member->find_all();
        $data['cabang'] = $this->cabang->find_all();
        $data['title'] = "Transaksi Member";
        $this->template->load('templates/dashboard', 'transaksi/add', $data);
    }
    public function tambahTransaksiKasir(){
        $data['member'] = $this->member->find_all();
        $data['cabang'] = $this->cabang->find_all();
        $data['title'] = "Cari Member";
        $this->template->load('templates/kasir', 'transaksi/addKasir', $data);
    }
    public function tambahTransaksiCabang(){
        $data['member'] = $this->member->find_all();
        $data['cabang'] = $this->cabang->find_all();
        $data['title'] = "Cari Member";
        $this->template->load('templates/cabang', 'transaksi/addCabang', $data);
    }
    public function cari_member(){
        $this->form_validation->set_rules('nomor','NomorHp','required|numeric');
        if($this->form_validation->run() == false){
            $data['member'] = $this->member->find_all();
            $data['cabang'] = $this->cabang->find_all();
            $data['title'] = "Transaksi Member";
            $this->template->load('templates/dashboard', 'transaksi/add', $data);
        }else{
            $nomor = $this->input->post('nomor');
            $member_data = $this->member->find_by_nohp($nomor);
            $this->session->set_userdata('member_data', $member_data);
            
            if(empty($member_data)){
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data dengan nomor handphone tersebut tidak ditemukan</div>');
                redirect('transaksi'); // Redirect kembali ke halaman pencarian
            }
    
            $data['member'] = $member_data;
            $data['cabang'] = $this->cabang->find_all();
            $data['title'] = "Transaksi Member";
    
            // Load unused voucher codes for the specific member
            $data['unused_vouchers'] = $this->getUnusedVouchers($nomor);
            $this->template->load('templates/dashboard', 'transaksi/transaksiMember', $data);
        }
        }
        private function getUnusedVouchers($nomor) {
            $this->db->where('isUse', 0);
            $this->db->where('nomor', $nomor);
            return $this->db->get('voucher_member')->result_array();
        }
        private function updateVoucherStatus($voucher_code) {
            $data = array('isUse' => 1);
            $this->db->where('vouchergenerate', $voucher_code);
            $this->db->update('voucher_member', $data);
        }
    public function convert_and_update() {
        $login_session_data = $this->session->userdata('login_session');
        $iduser = $login_session_data['user'];
        $this->form_validation->set_rules('kodetransaksi','Kode transaksi','required');
        $this->form_validation->set_rules('tanggaltransaksi','Tanggal Transaksi','required');
        $this->form_validation->set_rules('nocabang','No Cabang','required');
        $this->form_validation->set_rules('nomor','Nomor','required');
        $this->form_validation->set_rules('namamember','Nama Member','required');
        $this->form_validation->set_rules('total','Total','required');
        if($this->form_validation->run() == FALSE){
        //validasi menemukan error\
        $data['cabang'] = $this->cabang->find_all();
        $data['title'] = "Transaksi Member";
        $data['member'] = $this->session->userdata('member_data');
        $this->template->load('templates/dashboard', 'transaksi/transaksiMember', $data);
        
        } else {
            $config['upload_path'] = '../fotobill/';
            $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
            $config['max_size'] = 1073741824;
            $config['max_width'] = 10000;
            $config['max_height'] = 10000;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('fotobill')){
                $data['cabang'] = $this->cabang->find_all();
                $data['title'] = "Transaksi Member";
                $data['member'] = $this->session->userdata('member_data');
                $this->template->load('templates/dashboard', 'transaksi/transaksiMember', $data);
            }else{
            $fotobill = $this->upload->data();
            $fotobill = $fotobill['file_name'];
            $kodetransaksi = $this->input->post('kodetransaksi');
            $tanggaltransaksi = $this->input->post('tanggaltransaksi');
            $nocabang = $this->input->post('nocabang');
            $nomor = $this->input->post('nomor');
            $namamember = $this->input->post('namamember');
            $total = $this->input->post('total');
            $id_user = $iduser;
            $voucher_code = $this->input->post('kodevouchertukar');
            $data = array(
                'kodetransaksi' => $kodetransaksi,
                'tanggaltransaksi' => $tanggaltransaksi,
                'nocabang' => $nocabang,
                'nomor' => $nomor,
                'namamember' => $namamember,
                'total' => $total,
                'fotobill' => $fotobill,
                'iduser' => $id_user,
                'kodevoucher' => $voucher_code
            );
            var_dump($data);
            if ($this->db->insert('transaksi', $data)) {
                    // Get the inserted transaction ID
                    $transaksi_id = $this->db->insert_id();
        
                    // Retrieve the inserted transaction data
                    $transaksi = $this->db->get_where('transaksi', array('id' => $transaksi_id))->row();
                    
                    if(!empty($voucher_code)){
                        $this->updateVoucherStatus($voucher_code);
                    }
        
                    // Convert transaction total to points
                    $poin_baru = $this->convertToPoints($transaksi->total);
        
                    // Update member's points
                    $this->updateMemberPoin($transaksi->nomor, $poin_baru);
        
                    // // Delete the transaction (if needed, based on your logic)
                    // $this->deleteTransaksiMember($transaksi_id);

                    $this->updateCabangJumlahTransaksi($transaksi->nocabang);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Transaksi Berhasil Ditambahkan</div>');
                    // Redirect to the desired page
                    redirect(base_url('transaksi/historyTransaksi'));
                } else {
                    $this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">Transaksi Gagal Ada data yang kosong</div>');
                    // Redirect to the desired page
                    redirect(base_url('transaksi'));
                    
            }
            }
            
            
        }
    
}
    public function cari_member_kasir(){
        $this->form_validation->set_rules('nomor','NomorHp','required|numeric');
        if($this->form_validation->run() == false){
            $data['member'] = $this->member->find_all();
            $data['cabang'] = $this->cabang->find_all();
            $data['title'] = "Transaksi Member";
            $this->template->load('templates/kasir', 'transaksi/addKasir', $data);
        }else{
            $nomor = $this->input->post('nomor');
            $member_data = $this->member->find_by_nohp($nomor);
            if(empty($member_data)){
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data dengan nomor handphone tersebut tidak ditemukan</div>');
                redirect('transaksi/tambahTransaksiKasir'); // Redirect kembali ke halaman pencarian
            }
            $this->session->set_userdata('member_data', $member_data);
    
            $data['member'] = $member_data;
            $data['cabang'] = $this->cabang->find_all();
            $data['title'] = "Transaksi Member";
    
            // Load unused voucher codes for the specific member
            $data['unused_vouchers'] = $this->getUnusedVouchers($nomor);
            $this->template->load('templates/kasir', 'transaksi/transaksiMemberKasir', $data);
        }
        }
    public function convert_and_updateKasir() {
        $login_session_data = $this->session->userdata('login_session');
        // Dapatkan ID cabang dan ID user dari data sesi
        $idcabang = $login_session_data['idcabang'];
        $iduser = $login_session_data['user'];
        // Set aturan validasi
        $this->form_validation->set_rules('kodetransaksi','Kode transaksi','required');
        $this->form_validation->set_rules('tanggaltransaksi','Tanggal Transaksi','required');
        $this->form_validation->set_rules('nomor','Nomor','required');
        $this->form_validation->set_rules('namamember','Nama Member','required');
        $this->form_validation->set_rules('total','Total','required');
        if($this->form_validation->run() == FALSE){
        // Validasi menemukan error
        $data['cabang'] = $this->cabang->find_all();
        $data['title'] = "Transaksi Member";
        $data['member'] = $this->session->userdata('member_data');
        $this->template->load('templates/kasir', 'transaksi/transaksiMemberKasir', $data);
        } else {
            $config['upload_path'] = '../fotobill/';
            $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
            $config['max_size'] = 1073741824;
            $config['max_width'] = 10000;
            $config['max_height'] = 10000;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('fotobill')){
                $data['cabang'] = $this->cabang->find_all();
                $data['title'] = "Transaksi Member";
                $data['member'] = $this->session->userdata('member_data');
                $this->template->load('templates/kasir', 'transaksi/transaksiMemberKasir', $data);
            }else{
            $fotobill = $this->upload->data();
            $fotobill = $fotobill['file_name'];
            $kodetransaksi = $this->input->post('kodetransaksi');
            $tanggaltransaksi = $this->input->post('tanggaltransaksi');
            $nocabang = $idcabang;
            $nomor = $this->input->post('nomor');
            $namamember = $this->input->post('namamember');
            $total = $this->input->post('total');
            $id_user = $iduser;
            $voucher_code = $this->input->post('kodevouchertukar');
            $data = array(
                'kodetransaksi' => $kodetransaksi,
                'tanggaltransaksi' => $tanggaltransaksi,
                'nocabang' => $nocabang,
                'nomor' => $nomor,
                'namamember' => $namamember,
                'total' => $total,
                'fotobill' => $fotobill,
                'iduser' => $id_user,
                'kodevoucher' => $voucher_code
            );
            var_dump($data);
            if ($this->db->insert('transaksi', $data)) {
                    // Get the inserted transaction ID
                    $transaksi_id = $this->db->insert_id();
        
                    // Retrieve the inserted transaction data
                    $transaksi = $this->db->get_where('transaksi', array('id' => $transaksi_id))->row();
                    
                    if(!empty($voucher_code)){
                        $this->updateVoucherStatus($voucher_code);
                    }
        
                    // Convert transaction total to points
                    $poin_baru = $this->convertToPoints($transaksi->total);
        
                    // Update member's points
                    $this->updateMemberPoin($transaksi->nomor, $poin_baru);
        
                    // // Delete the transaction (if needed, based on your logic)
                    // $this->deleteTransaksiMember($transaksi_id);

                    $this->updateCabangJumlahTransaksi($transaksi->nocabang);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Transaksi Berhasil Ditambahkan</div>');
                    // Redirect to the desired page
                    redirect(base_url('transaksi/historyTransaksiKasir'));
                } else {
                    $this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">Transaksi Gagal Ada data yang kosong</div>');
                    // Redirect to the desired page
                    redirect(base_url('transaksi/tambahTransaksiKasir'));
            }
            }
    }
}
    public function cari_member_cabang(){
        $this->form_validation->set_rules('nomor','NomorHp','required|numeric');
        if($this->form_validation->run() == false){
            $data['member'] = $this->member->find_all();
            $data['cabang'] = $this->cabang->find_all();
            $data['title'] = "Transaksi Member";
            $this->template->load('templates/cabang', 'transaksi/addCabang', $data);
        }else{
            $nomor = $this->input->post('nomor');
            $member_data = $this->member->find_by_nohp($nomor);
            if(empty($member_data)){
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data dengan nomor handphone tersebut tidak ditemukan</div>');
                redirect('transaksi/tambahTransaksiCabang'); // Redirect kembali ke halaman pencarian
            }
            $this->session->set_userdata('member_data', $member_data);
    
            $data['member'] = $member_data;
            $data['cabang'] = $this->cabang->find_all();
            $data['title'] = "Transaksi Member";
            
            $data['unused_vouchers'] = $this->getUnusedVouchers($nomor);
            $this->template->load('templates/cabang', 'transaksi/transaksiMemberCabang', $data);
        }
        }
        public function convert_and_updateCabang() {
            $login_session_data = $this->session->userdata('login_session');
            // Dapatkan ID cabang dan ID user dari data sesi
            $idcabang = $login_session_data['idcabang'];
            $iduser = $login_session_data['user'];
            // Set aturan validasi
            $this->form_validation->set_rules('kodetransaksi','Kode transaksi','required');
            $this->form_validation->set_rules('tanggaltransaksi','Tanggal Transaksi','required');
            $this->form_validation->set_rules('nomor','Nomor','required');
            $this->form_validation->set_rules('namamember','Nama Member','required');
            $this->form_validation->set_rules('total','Total','required');
            if($this->form_validation->run() == FALSE){
            // Validasi menemukan error
            $data['cabang'] = $this->cabang->find_all();
            $data['title'] = "Transaksi Member";
            $data['member'] = $this->session->userdata('member_data');
            $this->template->load('templates/cabang', 'transaksi/transaksiMemberCabang', $data);
            } else {
                $config['upload_path'] = '../fotobill/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG|svg';
                $config['max_size'] = 2048000;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('fotobill')){
                    $data['cabang'] = $this->cabang->find_all();
                    $data['title'] = "Transaksi Member";
                    $data['member'] = $this->session->userdata('member_data');
                    $this->template->load('templates/cabang', 'transaksi/transaksiMemberCabang', $data);
                }else{
                $fotobill = $this->upload->data();
                $fotobill = $fotobill['file_name'];
                $kodetransaksi = $this->input->post('kodetransaksi');
                $tanggaltransaksi = $this->input->post('tanggaltransaksi');
                $nocabang = $idcabang;
                $nomor = $this->input->post('nomor');
                $namamember = $this->input->post('namamember');
                $total = $this->input->post('total');
                $id_user = $iduser;
                $voucher_code = $this->input->post('kodevouchertukar');
                $data = array(
                    'kodetransaksi' => $kodetransaksi,
                    'tanggaltransaksi' => $tanggaltransaksi,
                    'nocabang' => $nocabang,
                    'nomor' => $nomor,
                    'namamember' => $namamember,
                    'total' => $total,
                    'fotobill' => $fotobill,
                    'iduser' => $id_user,
                    'kodevoucher' => $voucher_code
                );
                var_dump($data);
                if ($this->db->insert('transaksi', $data)) {
                        // Get the inserted transaction ID
                        $transaksi_id = $this->db->insert_id();
            
                        // Retrieve the inserted transaction data
                        $transaksi = $this->db->get_where('transaksi', array('id' => $transaksi_id))->row();
                        
                        if(!empty($voucher_code)){
                        $this->updateVoucherStatus($voucher_code);
                        }
            
                        // Convert transaction total to points
                        $poin_baru = $this->convertToPoints($transaksi->total);
            
                        // Update member's points
                        $this->updateMemberPoin($transaksi->nomor, $poin_baru);
            
                        // // Delete the transaction (if needed, based on your logic)
                        // $this->deleteTransaksiMember($transaksi_id);
    
                        $this->updateCabangJumlahTransaksi($transaksi->nocabang);
                        $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Transaksi Berhasil Ditambahkan</div>');
                        // Redirect to the desired page
                        redirect(base_url('member/historyTransaksiCabang'));
                    } else {
                        $this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">Transaksi Gagal Ada data yang kosong</div>');
                        // Redirect to the desired page
                        redirect(base_url('transaksi/tambahTransaksiCabang'));
                }
                }
        }
    }
function convertToPoints($total) {
    // Lakukan konversi sesuai dengan kriteria yang Anda tentukan
    // Misalnya, 1 poin untuk setiap 10,000 dan tambahan 1 poin per 10,000
    return floor($total / 10000);
}


function updateMemberPoin($nomor, $poin_baru) {
    // Ambil poin member saat ini
    $current_poin = $this->db->get_where('member', array('nomor' => $nomor))->row()->poin;

    // Tambahkan poin baru
    $new_poin = $current_poin + $poin_baru;

    // Update poin member
    $this->db->where('nomor', $nomor);
    $this->db->update('member', array('poin' => $new_poin));
}

function deleteTransaksiMember($transaksi_id){
    $this->db->where('id', $transaksi_id);
    $this->db->delete('transaksi');
    redirect('member/index');
}
function updateCabangJumlahTransaksi($nocabang){
    $totaltransaksi = $this->db->where('nocabang',$nocabang)->count_all_results('transaksi');
    $this->db->where('id', $nocabang);
    $updateResult = $this->db->update('cabang',array('jumlahtransaksi' => $totaltransaksi));
    if(!$updateResult){
        echo "Error Updating cabang jumlah transaksi";
    }
}

    
    public function saldo(){
        $data['member'] = $this->member->find_all();
        $data['title'] = "Top Up Saldo";
        $this->template->load('templates/dashboard', 'transaksi/addSaldo', $data);
    }
    public function saldoCabang(){
        $data['member'] = $this->member->find_all();
        $data['title'] = "Top Up Saldo";
        $this->template->load('templates/cabang', 'transaksi/addSaldoCabang', $data);
    }
    public function cari_memberSaldo(){
        $this->form_validation->set_rules('nohp','NomorHp','required');
        if($this->form_validation->run() == false){
            $data['member'] = $this->member->find_all();
            $data['title'] = "Top Up Saldo";
            $this->template->load('templates/dashboard', 'transaksi/addSaldo', $data);
        }else{
            $nohp = $this->input->post('nohp');
            $this->session->set_userdata('member_data', $this->member->find_by_nohp($nohp));
            $data['member'] = $this->member->find_by_nohp($nohp);
            $data['title'] = "Topup Saldo Member";
            $this->template->load('templates/dashboard', 'transaksi/transaksiMemberSaldo', $data);
        }
        }
        public function cari_memberSaldoCabang(){
            $this->form_validation->set_rules('nohp','NomorHp','required');
            if($this->form_validation->run() == false){
                $data['member'] = $this->member->find_all();
                $data['title'] = "Top Up Saldo";
                $this->template->load('templates/cabang', 'transaksi/addSaldoCabang', $data);
            }else{
                $nohp = $this->input->post('nohp');
                $this->session->set_userdata('member_data', $this->member->find_by_nohp($nohp));
                $data['member'] = $this->member->find_by_nohp($nohp);
                $data['title'] = "Topup Saldo Member";
                $this->template->load('templates/cabang', 'transaksi/transaksiMemberSaldoCabang', $data);
            }
            }
        public function convert_and_updateSaldoMember() {
            $login_session_data = $this->session->userdata('login_session');
            $iduser = $login_session_data['user'];
            // Set aturan validasi
            $this->form_validation->set_rules('nominal','Nominal','required');
            $this->form_validation->set_rules('metode','Metode','required');
            $this->form_validation->set_rules('bukti','Bukti','callback_file_check');
            $this->form_validation->set_rules('nomor','Nomor','required');
            if($this->form_validation->run() == FALSE){
                // Validasi menemukan error
                $data['title'] = "Topup Saldo Member";
                $data['member'] = $this->session->userdata('member_data');
                $this->template->load('templates/dashboard', 'transaksi/transaksiMemberSaldo', $data);
            } else {
                $config['upload_path'] = '../fotobukti/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG';
                $config['max_size'] = 1073741824;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('bukti')){
                    $data['title'] = "Topup Saldo Member";
                    $data['member'] = $this->session->userdata('member_data');
                    $this->template->load('templates/dashboard', 'transaksi/transaksiMemberSaldo', $data);
                    $data['upload_error'] = $this->upload->display_errors('<span class="text-danger small">', '</span>');
                }else {
                    // Ambil data dari form
                $bukti = $this->upload->data();
                $bukti = $bukti['file_name'];
                $nominal = $this->input->post('nominal');
                $metode = $this->input->post('metode');
                $nomor = $this->input->post('nomor');
                $id_user = $iduser;
                // Set nilai 'nocabang' otomatis dari ID cabang kasir yang sedang login
                // Buat array data untuk dimasukkan ke tabel 'transaksi'
                $data = array(
                    'nominal' => $nominal,
                    'metode' => $metode,
                    'bukti' => $bukti,
                    'nomor' => $nomor,
                    'id_user' => $id_user
                );
        
                // Tampilkan data untuk debug
                var_dump($data);
        
                // Lakukan inser ke tabel 'transaksi'
                if ($this->db->insert('topup', $data)) {
                    $topup_id = $this->db->insert_id();
                    $transaksi = $this->db->get_where('topup', array('id' => $topup_id))->row();
        
                    // Update poin member
                    $this->member->updateMemberSaldo($transaksi->nomor, $transaksi->nominal);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Topup Saldo Member berhasil</div>');
        
                    // Redirect ke halaman yang diinginkan
                    redirect(base_url('transaksi/getHistorySaldo'));
                } else {
                    // Terjadi error saat memasukkan data ke tabel 'transaksi'
                    echo "Error: " . $this->db->error();
                }
                }
                
            }
        }
        public function convert_and_updateSaldoMemberCabang() {
            $login_session_data = $this->session->userdata('login_session');
            $iduser = $login_session_data['user'];
            // Set aturan validasi
            $this->form_validation->set_rules('nominal','Nominal','required');
            $this->form_validation->set_rules('metode','Metode','required');
            $this->form_validation->set_rules('bukti','Bukti','callback_file_check');
            $this->form_validation->set_rules('nomor','Nomor','required');
            if($this->form_validation->run() == FALSE){
                // Validasi menemukan error
                $data['title'] = "Topup Saldo Member";
                $data['member'] = $this->session->userdata('member_data');
                $this->template->load('templates/cabang', 'transaksi/transaksiMemberSaldoCabang', $data);
            } else {
                $config['upload_path'] = '../fotobukti/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg|JPEG';
                $config['max_size'] = 2048000;
                $config['max_width'] = 10000;
                $config['max_height'] = 10000;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('bukti')){
                    $data['title'] = "Topup Saldo Member";
                    $data['member'] = $this->session->userdata('member_data');
                    $this->template->load('templates/cabang', 'transaksi/transaksiMemberSaldoCabang', $data);
                    $data['upload_error'] = $this->upload->display_errors('<span class="text-danger small">', '</span>');
                }else {
                    // Ambil data dari form
                $bukti = $this->upload->data();
                $bukti = $bukti['file_name'];
                $nominal = $this->input->post('nominal');
                $metode = $this->input->post('metode');
                $nomor = $this->input->post('nomor');
                $id_user = $iduser;
                // Set nilai 'nocabang' otomatis dari ID cabang kasir yang sedang login
                // Buat array data untuk dimasukkan ke tabel 'transaksi'
                $data = array(
                    'nominal' => $nominal,
                    'metode' => $metode,
                    'bukti' => $bukti,
                    'nomor' => $nomor,
                    'id_user' => $id_user
                );
        
                // Tampilkan data untuk debug
                var_dump($data);
        
                // Lakukan inser ke tabel 'transaksi'
                if ($this->db->insert('topup', $data)) {
                    $topup_id = $this->db->insert_id();
                    $transaksi = $this->db->get_where('topup', array('id' => $topup_id))->row();
        
                    // Update poin member
                    $this->member->updateMemberSaldo($transaksi->nomor, $transaksi->nominal);
                    $this->session->set_flashdata('pesan','<div class="alert alert-success" role="alert">Topup Saldo Member berhasil</div>');
        
                    // Redirect ke halaman yang diinginkan
                    redirect(base_url('transaksi/getHistorySaldoCabang'));
                } else {
                    // Terjadi error saat memasukkan data ke tabel 'transaksi'
                    echo "Error: " . $this->db->error();
                }
                }
                
            }
        }
        public function file_check($str)
        {
            // Check if a file is uploaded
            if (empty($_FILES['bukti']['name'])) {
                $this->form_validation->set_message('file_check', 'The {field} field is required.');
                return false;
            }

            // Additional checks if needed (e.g., file type, size, etc.)

            return true;
        }
        public function dataTopup()
	    {
        $data['title'] = "Data TopUp Saldo Member";
        $data['tops'] = $this->topup->getTopupDetails();
		$this->template->load('templates/dashboard', 'topup/index', $data);
	    }
        public function getHistorySaldo()
	    {
        $data['title'] = "History Top Up Saldo";
        $data['tops'] = $this->topup->getTopupDetails();
		$this->template->load('templates/dashboard', 'topup/data', $data);
	    }
	    public function getHistorySaldoCabang()
	    {
        $data['title'] = "History Top Up Saldo";
        $data['tops'] = $this->topup->getTopupDetails();
		$this->template->load('templates/cabang', 'topup/dataCabang', $data);
	    }
        public function getHistorySaldoKasir()
	    {
        $data['title'] = "History Top Up Saldo";
        $data['tops'] = $this->topup->getTopupDetails();
		$this->template->load('templates/kasir', 'topup/dataKasir', $data);
	    }
        public function historyTransaksi()
        {
            $data['title'] = "History Transaksi";
            $data['trans'] = $this->transaksi->getHistoryTransaksiDetails();
            $this->template->load('templates/dashboard', 'transaksi/historyTransaksi', $data);
        }
        public function historyTransaksiKasir()
        {
            $login_session_data = $this->session->userdata('login_session');
            $idcabang = $login_session_data['idcabang'];
            $iduser = $login_session_data['user'];
            $data['title'] = "History Transaksi";
            $data['trans'] = $this->transaksi->getTransaksiByIdMemberWithDetails($idcabang);
            $this->template->load('templates/kasir', 'transaksi/historyTransaksiKasir', $data);
        }
        public function historyTransaksiCabang()
        {
            $login_session_data = $this->session->userdata('login_session');
            $idcabang = $login_session_data['idcabang'];
            $iduser = $login_session_data['user'];
            $data['title'] = "History Transaksi";
            $data['trans'] = $this->transaksi->getTransaksiByIdMemberWithDetails($idcabang);
            $this->template->load('templates/cabang', 'transaksi/historyTransaksiCabang', $data);
        }
}