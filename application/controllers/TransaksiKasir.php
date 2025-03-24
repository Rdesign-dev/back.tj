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
        $this->load->database(); // Add this line to ensure DB is loaded
    }

    public function cari_member_kasir()
    {
        $this->form_validation->set_rules('nomor', 'NomorHp', 'required|numeric');
        if ($this->form_validation->run() == false) {
            $data['title'] = "Transaksi Member";
            $this->template->load('templates/kasir', 'transaksi/addKasir', $data);
        } else {
            $nomor = $this->input->post('nomor');
            
            // First get the member data
            $member_data = $this->db->get_where('users', ['phone_number' => $nomor])->row();

            if (empty($member_data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
                redirect('transaksikasir/tambahTransaksiKasir');
                return;
            }

            // Then get available vouchers for this user
            $unused_vouchers = $this->db->select('redeem_id, kode_voucher')
                                ->from('redeem_voucher')
                                ->where('status', 'Available')
                                ->where('expires_at >', date('Y-m-d H:i:s'))
                                ->where('user_id', $member_data->id)
                                ->get()
                                ->result_array();

            // Save member data to session
            $this->session->set_userdata('member_data', $member_data);

            $data['title'] = "Transaksi Member";
            $data['member'] = $member_data;
            $data['unused_vouchers'] = $unused_vouchers;

            $this->template->load('templates/kasir', 'transaksi/transaksiMemberKasir', $data);
            // $this->load->view('transaksi/transaksiMemberKasir', $data);
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

        try {
            // Start transaction
            $this->db->trans_start();

            // Handle file upload
            $config['upload_path'] = '../ImageTerasJapan/transaction_proof/Payment';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = 'TRX_' . time();

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('fotobill')) {
                throw new Exception($this->upload->display_errors());
            }

            $upload_data = $this->upload->data();
            $is_voucher = $this->input->post('tukarVoucher') == 'on';
            $total = $this->input->post('total');
            $split_bill = $this->input->post('splitBill') == 'on';
            
            // Validate voucher if used
            if ($is_voucher) {
                $voucher_id = $this->input->post('kodevouchertukar');
                $voucher = $this->db->where('redeem_id', $voucher_id)
                                   ->where('user_id', $member_data->id)
                                   ->where('status', 'Available')
                                   ->get('redeem_voucher')
                                   ->row();
                
                if (!$voucher) {
                    throw new Exception('Voucher tidak valid atau sudah digunakan');
                }
            }

            // Generate transaction code
            $sequence = $this->transaksi->getNextSequence();
            $transaction_code = "TX" . $login_session['branch_id'] . 
                              $login_session['id'] . 
                              "TJP" . 
                              date('dmy') . 
                              sprintf('%04d', $sequence);

            // Prepare transaction data
            $transaction_data = [
                'transaction_codes' => $transaction_code,
                'user_id' => $member_data->id,
                'transaction_type' => 'Teras Japan Payment',
                'amount' => $total,
                'branch_id' => $login_session['branch_id'],
                'account_cashier_id' => $login_session['id'],
                'transaction_evidence' => $upload_data['file_name'],
                'voucher_id' => $is_voucher ? $voucher_id : null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert transaction
            $this->db->insert('transactions', $transaction_data);
            $transaction_id = $this->db->insert_id();

            // Handle payments
            if ($split_bill) {
                // Primary payment
                $primary_amount = $this->input->post('primary_amount');
                $primary_method = $this->input->post('primary_payment_method');
                
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $primary_method,
                    'amount' => $primary_amount
                ]);

                // Secondary payment
                $secondary_amount = $total - $primary_amount;
                $secondary_method = $this->input->post('secondary_payment_method');
                
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $secondary_method,
                    'amount' => $secondary_amount
                ]);

                // Update balance if using Balance payment method
                if ($primary_method == 'Balance' || $secondary_method == 'Balance') {
                    $balance_amount = ($primary_method == 'Balance' ? $primary_amount : 0) +
                                    ($secondary_method == 'Balance' ? $secondary_amount : 0);
                    
                    if ($member_data->balance < $balance_amount) {
                        throw new Exception('Saldo tidak mencukupi');
                    }

                    $this->db->where('id', $member_data->id)
                             ->update('users', [
                                 'balance' => $member_data->balance - $balance_amount
                             ]);
                }
            } else {
                // Single payment
                $payment_method = $this->input->post('primary_payment_method');
                
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $payment_method,
                    'amount' => $total
                ]);

                // Update balance if using Balance
                if ($payment_method == 'Balance') {
                    if ($member_data->balance < $total) {
                        throw new Exception('Saldo tidak mencukupi');
                    }

                    $this->db->where('id', $member_data->id)
                             ->update('users', [
                                 'balance' => $member_data->balance - $total
                             ]);
                }
            }

            // Update voucher status if used
            if ($is_voucher) {
                $this->db->where('redeem_id', $voucher_id)
                         ->update('redeem_voucher', ['status' => 'Used']);
            }

            // Calculate and update points
            $points_earned = floor($total / 10000);
            if ($points_earned > 0) {
                $this->db->where('id', $member_data->id)
                         ->set('poin', 'poin + ' . $points_earned, FALSE)
                         ->update('users');
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaksi gagal');
            }

            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil</div>');
            redirect('transaksikasir/historyTransaksiKasir'); // Changed redirect

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>');
            redirect('transaksikasir/tambahTransaksiKasir'); // Changed redirect
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
        // Change to use getTopupByIdCabang instead
        $data['trans'] = $this->topup->getTopupByIdCabang($login_session['branch_id']);
        
        $this->template->load('templates/kasir', 'topup/dataKasir', $data);
    }
}