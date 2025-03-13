<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransaksiKasir extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Member_model', 'member');
        $this->load->model('Transaksi_model', 'transaksi');
        $this->load->model('auth_model', 'user'); 
        $this->load->model('transaksi_model', 'transaksi');
        $this->load->model('topup_model', 'topup');
        $this->load->model('member_model','member');
        $this->load->model('cabang_model','cabang');
        $this->load->library('template');  // Add this line
        $this->load->library('form_validation');
    }

    public function cari_member_kasir()
    {
        $this->form_validation->set_rules('nomor', 'NomorHp', 'required|numeric');
        if ($this->form_validation->run() == false) {
            $data['title'] = "Transaksi Member";
            $this->template->load('templates/kasir', 'transaksi/addKasir', $data);
        } else {
            $nomor = $this->input->post('nomor');
            
            // Cari user berdasarkan phone_number di tabel users
            $member_data = $this->db->get_where('users', ['phone_number' => $nomor])->row();

            if (empty($member_data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
                redirect('transaksikasir/tambahTransaksiKasir');
                return;
            }

            $this->session->set_userdata('member_data', $member_data);
            
            // Get available vouchers for this user
            $unused_vouchers = $this->db->select('redeem_id, kode_voucher')
                                   ->from('redeem_voucher')
                                   ->where('user_id', $member_data->id)
                                   ->where('status', 'Available')
                                   ->where('expires_at >', date('Y-m-d H:i:s'))
                                   ->get()
                                   ->result();

            $data['title'] = "Transaksi Member";
            $data['member'] = $member_data;
            $data['unused_vouchers'] = $unused_vouchers;

            $this->template->load('templates/kasir', 'transaksi/transaksiMemberKasir', $data);
        }
    }

    public function tambahTransaksiKasir() {
        $data['title'] = "Transaksi Member";
        $this->template->load('templates/kasir', 'transaksi/addKasir', $data);
    }

    public function convert_and_updateKasir() 
    {
        $login_session = $this->session->userdata('login_session');
        $member_data = $this->session->userdata('member_data');

        // Form validation
        if ($this->input->post('tukarVoucher')) {
            $this->form_validation->set_rules('kodevouchertukar', 'Voucher', 'required');
        } else {
            $this->form_validation->set_rules('total', 'Total', 'required|numeric');
            $this->form_validation->set_rules('payment_method', 'Metode Pembayaran', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Transaksi Member";
            $data['member'] = $member_data;
            $this->template->load('templates/kasir', 'transaksi/transaksiMemberKasir', $data);
        } else {
            // Upload configuration and process
            $config['upload_path'] = '../ImageTerasJapan/transaction_proof/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = 'TRX_' . time();

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('fotobill')) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>');
                redirect('transaksikasir/tambahTransaksiKasir');
                return;
            }

            $upload_data = $this->upload->data();
            $is_voucher = $this->input->post('tukarVoucher') === 'on'; // Will be true if checkbox is checked
            $amount = $this->input->post('total');
            $payment_method = $this->input->post('payment_method');

            // Check balance if using BM payment method
            if (!$is_voucher && $payment_method === 'BM') {
                if ($member_data->balance < $amount) {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Saldo member tidak mencukupi</div>');
                    redirect('transaksikasir/tambahTransaksiKasir');
                    return;
                }
            }

            // Generate transaction code
            $sequence = $this->transaksi->getNextSequence();
            $transaction_code = "TX" . $login_session['branch_id'] . 
                              $login_session['id'] . 
                              ($is_voucher ? "RV" : "TJP") . 
                              ($payment_method ?? "NULL") . 
                              date('dmy') . 
                              sprintf('%04d', $sequence);

            // Konversi kode payment method ke nilai enum yang sesuai
            $payment_method_mapping = [
                'CSH' => 'Cash',
                'TFB' => 'Transfer Bank',
                'BM' => 'Balance Member'
            ];

            // Prepare transaction data with fixed logic
            $data = [
                'transaction_codes' => $transaction_code,
                'user_id' => $member_data->id,
                'transaction_type' => $is_voucher ? 'Redeem Voucher' : 'Teras Japan Payment', // Fixed: use full names
                'amount' => $is_voucher ? null : $amount,
                'branch_id' => $login_session['branch_id'],
                'account_cashier_id' => $login_session['id'],
                'payment_method' => $is_voucher ? null : $payment_method_mapping[$payment_method],
                'transaction_evidence' => $upload_data['file_name'],
                'voucher_id' => $is_voucher ? $this->input->post('kodevouchertukar') : null,
                'created_at' => $this->input->post('tanggaltransaksi')
            ];

            // Add debug to verify
            // echo "<pre>";
            // echo "Data to be inserted:\n";
            // var_dump($data);
            // echo "</pre>";
            // die();

            // Begin transaction
            $this->db->trans_start();

            // Insert transaction
            $this->db->insert('transactions', $data);

            // Update balance if using balance payment
            if (!$is_voucher && $payment_method === 'BM') {
                $new_balance = $member_data->balance - $amount;
                $this->db->where('id', $member_data->id)
                         ->update('users', ['balance' => $new_balance]);
            }

            // Update voucher status if redeeming
            if ($is_voucher) {
                $this->db->where('redeem_id', $data['voucher_id'])
                         ->update('redeem_voucher', ['status' => 'Used']);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Transaksi gagal</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil</div>');
            }

            redirect('transaksikasir/tambahTransaksiKasir');
        }
    }

    public function historyTransaksiKasir()
    {
        $login_session = $this->session->userdata('login_session');
        
        $data['title'] = 'Riwayat Transaksi';
        $data['trans'] = $this->transaksi->getTransaksiByIdCabangWithDetails($login_session['branch_id']);
        
        $this->template->load('templates/kasir', 'transaksi/historyTransaksiKasir', $data);
    }

    public function getHistorysaldoKasir()
    {
        $login_session = $this->session->userdata('login_session');
        
        $data['title'] = 'Riwayat Top Up Saldo';
        $data['trans'] = $this->topup->getTopupByIdCabang($login_session['branch_id']);
        
        $this->template->load('templates/kasir', 'topup/dataKasir', $data);
    }
}