<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('auth_model', 'user'); 
        $this->load->model('transaksi_model', 'transaksi');
        $this->load->model('topup_model', 'topup');
        $this->load->model('member_model','member');
        $this->load->model('cabang_model','cabang');
        $this->load->library('form_validation');
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
            return $this->db->select('rv.*, u.phone_number')
                            ->from('redeem_voucher rv')
                            ->join('users u', 'u.id = rv.user_id')
                            ->where('u.phone_number', $nomor)
                            ->where('rv.status', 'Available')
                            ->get()
                            ->result_array();
        }
        private function updateVoucherStatus($voucher_code) {
            $data = array('status' => 'Used');
            $this->db->where('kode_voucher', $voucher_code)
                     ->update('redeem_voucher', $data);
        }
    public function convert_and_update() 
    {
        // Get login session data
        $login_session = $this->session->userdata('login_session');
        
        // Check if user is logged in
        if (!$login_session) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Silahkan login terlebih dahulu</div>');
            redirect('auth');
        return;
        }
    
        
        // Id Admin
        $account_id = $login_session['id'];
    
        // Get user_id by phone number
        $phone_number = $this->input->post('nomor');
        $user_id = $this->user->get_user_by_phone($phone_number);
    
        $user_id = intval($user_id);
        
        // Form validation rules
        $this->form_validation->set_rules('tanggaltransaksi', 'Tanggal Transaksi', 'required');
        $this->form_validation->set_rules('nocabang', 'Nama Cabang', 'required');
        $this->form_validation->set_rules('nomor', 'Nomor Member', 'required');
        $this->form_validation->set_rules('nama', 'Nama Member', 'required');
    
        if ($this->input->post('tukarVoucher')) {
            $this->form_validation->set_rules('kodevouchertukar', 'Kode Voucher', 'required');
        } else {
            $this->form_validation->set_rules('total', 'Total', 'required|numeric');
            $this->form_validation->set_rules('payment_method', 'Metode Pembayaran', 'required');
        }
    
        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Transaksi Member";
            $data['cabang'] = $this->db->get('branches')->result_array();
            $data['member'] = $this->session->userdata('member_data');
            $this->template->load('templates/dashboard', 'transaksi/transaksiMember', $data);
        } else {
            // Process transaction
            $config['upload_path'] = '../ImageTerasJapan/transaction_proof/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = $this->generate_evidence_filename($this->input->post('nomor'));
    
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload('fotobill')) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>');
                redirect('transaksi/add');
                return;
            }
    
            $upload_data = $this->upload->data();
            
            $data = [
                'transaction_codes' => $this->generate_transaction_code($account_id),
                'user_id' => $user_id,
                'transaction_type' => $this->input->post('tukarVoucher') ? 'Reedem Voucher' : 'Teras Japan Payment',
                'amount' => $this->input->post('total'),
                'branch_id' => $this->input->post('nocabang'),
                'account_cashier_id' => $account_id,
                'payment_method' => $this->input->post('tukarVoucher') ? null : $this->input->post('payment_method'),
                'transaction_evidence' => $upload_data['file_name'],
                'created_at' => $this->input->post('tanggaltransaksi')
            ];
    
            if ($this->input->post('tukarVoucher')) {
                // Ambil redeem_id berdasarkan kode_voucher
                $kode_voucher = $this->input->post('kodevouchertukar');
                $voucher = $this->db->get_where('redeem_voucher', ['kode_voucher' => $kode_voucher])->row();
                
                if (!$voucher) {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Voucher tidak ditemukan</div>');
                    redirect('transaksi/add');
                    return;
                }
                
                // Set voucher_id dengan redeem_id (bukan kode_voucher)
                $data['voucher_id'] = $voucher->redeem_id;
                
                // Update voucher status
                $this->updateVoucherStatus($kode_voucher);
            }
    
            if ($this->db->insert('transactions', $data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil disimpan</div>');
                redirect('transaksi/historyTransaksi');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Transaksi gagal disimpan: ' . $this->db->error()['message'] . '</div>');
                redirect('transaksi/add');
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
// function convertToPoints($total) {
//     // Lakukan konversi sesuai dengan kriteria yang Anda tentukan
//     // Misalnya, 1 poin untuk setiap 10,000 dan tambahan 1 poin per 10,000
//     return floor($total / 10000);
// }


// function updateMemberPoin($nomor, $poin_baru) {
//     // Ambil poin member saat ini
//     $current_poin = $this->db->get_where('member', array('nomor' => $nomor))->row()->poin;

//     // Tambahkan poin baru
//     $new_poin = $current_poin + $poin_baru;

//     // Update poin member
//     $this->db->where('nomor', $nomor);
//     $this->db->update('member', array('poin' => $new_poin));
// }

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
    public function cari_memberSaldo() {
    $this->form_validation->set_rules('nohp', 'Nomor HP', 'required');
    
    if ($this->form_validation->run() == false) {
        $data['title'] = "Top Up Saldo";
        $this->template->load('templates/dashboard', 'transaksi/addSaldo', $data);
    } else {
        $nohp = $this->input->post('nohp');
        
        // Cari user berdasarkan phone_number
        $member_data = $this->db->get_where('users', ['phone_number' => $nohp])->row_array();
        
        if (!$member_data) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
            redirect('transaksi/saldo');
        }
        
        $this->session->set_userdata('member_data', $member_data);
        $data['member'] = $member_data;
        $data['title'] = "Topup Saldo Member";
        $this->template->load('templates/dashboard', 'transaksi/transaksiMemberSaldo', $data);
    }
}
        public function cari_memberSaldoCabang() {
            $this->form_validation->set_rules('nohp', 'Nomor HP', 'required');
            
            if ($this->form_validation->run() == false) {
                $data['member'] = $this->member->find_all(); // Pastikan method find_all() sudah diupdate untuk tabel users
                $data['title'] = "Top Up Saldo";
                $this->template->load('templates/cabang', 'transaksi/addSaldoCabang', $data);
            } else {
                $nohp = $this->input->post('nohp');
                
                // Query untuk mencari user berdasarkan phone_number
                $member_data = $this->db->get_where('users', ['phone_number' => $nohp])->row_array();
                
                if (!$member_data) {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
                    redirect('transaksi/saldoCabang');
                }
                
                $this->session->set_userdata('member_data', $member_data);
                $data['member'] = $member_data;
                $data['title'] = "Topup Saldo Member";
                
                $this->template->load('templates/cabang', 'transaksi/transaksiMemberSaldoCabang', $data);
            }
        }
        public function convert_and_updateSaldoMember() {
    $login_session = $this->session->userdata('login_session');
    $account_id = $login_session['id'];
    
    // Check if voucher redemption
    $is_voucher_redemption = $this->input->post('use_voucher') === 'true';
    
    if ($is_voucher_redemption) {
        // Handle voucher redemption
        $voucher_code = $this->input->post('voucher_code');
        $user = $this->db->get_where('users', ['phone_number' => $this->input->post('nomor')])->row();
        
        if (!$user) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">User tidak ditemukan</div>');
            redirect('transaksi/saldo');
            return;
        }

        // Get voucher details
        $voucher = $this->db->get_where('redeem_voucher', [
            'kode_voucher' => $voucher_code,
            'user_id' => $user->id,
            'status' => 'Available'
        ])->row();

        if (!$voucher) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Voucher tidak valid</div>');
            redirect('transaksi/saldo');
            return;
        }

        // Prepare transaction data for voucher redemption
        $data = [
            'transaction_codes' => $this->generate_transaction_code($account_id),
            'user_id' => $user->id,
            'transaction_type' => 'Reedem Voucher',
            'amount' => $voucher->points_used,
            'branch_id' => null,
            'account_cashier_id' => $account_id,
            'payment_method' => 'Balance',
            'voucher_id' => $voucher->redeem_id
        ];

        // Begin transaction
        $this->db->trans_start();

        // Insert transaction
        $this->db->insert('transactions', $data);

        // Update voucher status
        $this->updateVoucherStatus($voucher_code);

        $this->db->trans_complete();

    } else {
        // Original top-up logic
        // ... existing top-up code ...
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
                $config['max_size'] = 10240; // 10MB in kilobytes
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
        // public function getHistorySaldo()
	    // {
        // $data['title'] = "History Top Up Saldo";
        // $data['tops'] = $this->topup->getTopupDetails();
		// $this->template->load('templates/dashboard', 'topup/data', $data);
	    // }
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
        public function historyTransaksi() {
    $data['title'] = "History Transaksi";
    
    $this->db->select('t.*, b.branch_name, u.name as member_name, 
                       a.Name as cashier_name, rv.kode_voucher')
             ->from('transactions t')
             ->join('branch b', 'b.id = t.branch_id', 'left')
             ->join('users u', 'u.id = t.user_id', 'left')
             ->join('accounts a', 'a.id = t.account_cashier_id', 'left')
             ->join('redeem_voucher rv', 'rv.redeem_id = t.voucher_id', 'left')
             ->where_in('t.transaction_type', ['Teras Japan Payment', 'Reedem Voucher'])
             ->order_by('t.created_at', 'DESC');
             
    $data['trans'] = $this->db->get()->result();
    
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
    public function getHistoryTopupBalance() {
    $data['title'] = "History Top Up Saldo";
    
    $this->db->select('t.transaction_codes, t.created_at, b.branch_name, u.name as member_name, 
                       a.Name as cashier_name, t.amount, t.transaction_evidence, t.payment_method')
             ->from('transactions t')
             ->join('branch b', 'b.id = t.branch_id')
             ->join('users u', 'u.id = t.user_id')
             ->join('accounts a', 'a.id = t.account_cashier_id')
             ->where('t.transaction_type', 'Balance Top-up')
             ->order_by('t.created_at', 'DESC');
             
    $data['trans'] = $this->db->get()->result();
    
    $this->template->load('templates/dashboard', 'topup/index', $data);
}

public function getHistorysaldo() {
    $data['title'] = "Data TopUp Saldo Member";
    
    $this->db->select('t.transaction_codes, t.created_at, t.amount as nominal, 
                       t.payment_method as metode, t.transaction_evidence as bukti,
                       u.name as namamember, a.Name as nama')
             ->from('transactions t')
             ->join('users u', 'u.id = t.user_id')
             ->join('accounts a', 'a.id = t.account_cashier_id')
             ->where('t.transaction_type', 'Balance Top-up')
             ->order_by('t.created_at', 'DESC');
             
    $data['tops'] = $this->db->get()->result();
    
    $this->template->load('templates/dashboard', 'topup/data', $data);
}

private function generate_transaction_code($account_id) {
    $date = date('dmy'); // Format: tanggal-bulan-tahun (250310)
    $payment_method = $this->input->post('metode');
    $payment_code = ($payment_method == 'cash') ? 'CSH' : 'TF';
    $random = mt_rand(1000, 9999);
    
    return "TX{$account_id}SU{$date}TU{$payment_code}{$random}";
}

private function generate_evidence_filename($user_id) {
    $timestamp = date('YmdHis');
    $random = substr(str_shuffle("0123456789"), 0, 3);
    return "{$user_id}-SU-{$timestamp}-{$random}";
}

public function add()
{
    $data['title'] = "Transaksi Member";
    // Update the branch query to match your database structure
    $data['cabang'] = $this->db->select('branch_id as id, branch_code, branch_name')
                               ->from('branches')
                               ->get()
                               ->result_array();
    $data['member'] = $this->session->userdata('member_data');
    $this->template->load('templates/dashboard', 'transaksi/transaksiMember', $data);
}
}
