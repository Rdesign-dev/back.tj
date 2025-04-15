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
        try {
            $this->db->trans_start();
            
            // Get member data
            $nomor = $this->input->post('nomor');
            $member_data = $this->db->get_where('users', ['phone_number' => $nomor])->row();
            
            // Get payment amounts
            $primary_amount = (int)preg_replace('/[^0-9]/', '', $this->input->post('primary_amount_display'));
            $secondary_amount = (int)preg_replace('/[^0-9]/', '', $this->input->post('secondary_amount_display'));
            $total = $this->input->post('splitBill') ? ($primary_amount + $secondary_amount) : $primary_amount;

            // Voucher handling
            $voucher_id = null;
            if ($this->input->post('tukarVoucher') === 'on') {
                $voucher_code = $this->input->post('kodevouchertukar');
                
                // Get and validate voucher
                $voucher = $this->db->select('redeem_id, user_id, status')
                                   ->from('redeem_voucher')
                                   ->where('kode_voucher', $voucher_code)
                                   ->where('user_id', $member_data->id)
                                   ->where('status', 'Available')
                                   ->where('expires_at >', date('Y-m-d H:i:s'))
                                   ->get()
                                   ->row();

                if (!$voucher) {
                    throw new Exception('Voucher tidak valid atau sudah digunakan');
                }

                $voucher_id = $voucher->redeem_id;

                // Update voucher status to Used
                $this->db->where('redeem_id', $voucher_id)
                         ->update('redeem_voucher', ['status' => 'Used']);
            }

            // Handle file upload
            $transaction_evidence = 'struk.png';
            if ($_FILES['fotobill']['size'] > 0) {
                $config['upload_path'] = FCPATH . '/ImageTerasJapan/transaction_proof/Payment/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = $this->generate_struk_filename();
                
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('fotobill')) {
                    $uploaded_data = $this->upload->data();
                    $transaction_evidence = $uploaded_data['file_name'];
                }
            }

            // Prepare transaction data
            $transaction_data = [
                'transaction_codes' => $this->generate_transaction_code('Teras Japan Payment'),
                'user_id' => $member_data->id,
                'transaction_type' => 'Teras Japan Payment',
                'amount' => $total,
                'branch_id' => $this->session->userdata('login_session')['branch_id'],
                'account_cashier_id' => $this->session->userdata('login_session')['id'],
                'transaction_evidence' => $transaction_evidence,
                'created_at' => date('Y-m-d H:i:s'),
                'voucher_id' => $voucher_id  // Add voucher_id here
            ];

            // Insert transaction
            $this->db->insert('transactions', $transaction_data);
            $transaction_id = $this->db->insert_id();

            // Increment transaction_count in branch table
            $branch_id = $this->session->userdata('login_session')['branch_id'];
            $this->db->set('transaction_count', 'transaction_count + 1', FALSE)
                     ->where('id', $branch_id)
                     ->update('branch');

            // Calculate and add points (Rp 10.000 = 1 point)
            $points_to_add = floor($total / 10000);
            if ($points_to_add > 0) {
                $this->db->set('poin', "poin + {$points_to_add}", FALSE)
                         ->where('id', $member_data->id)
                         ->update('users');
                
                log_message('debug', "Added {$points_to_add} points to user {$member_data->id}");
            }

            // Handle payments
            if ($this->input->post('splitBill')) {
                // Primary payment
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $this->input->post('primary_payment_method'),
                    'amount' => $primary_amount // Using parsed amount
                ]);

                // Secondary payment
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $this->input->post('secondary_payment_method'),
                    'amount' => $secondary_amount // Using parsed amount
                ]);
            } else {
                // Single payment
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $this->input->post('primary_payment_method'),
                    'amount' => $primary_amount // Using parsed amount
                ]);
            }

            // Handle balance payment if used
            if ($this->input->post('primary_payment_method') === 'Balance') {
                // Check if user has enough balance
                if ($member_data->balance < $primary_amount) {
                    throw new Exception('Saldo tidak mencukupi');
                }
                // Update user balance
                $new_balance = $member_data->balance - $primary_amount;
                $this->db->where('id', $member_data->id)
                         ->update('users', ['balance' => $new_balance]);
            }

            // If split bill and second payment is balance
            if ($this->input->post('splitBill') && $this->input->post('secondary_payment_method') === 'Balance') {
                if ($member_data->balance < $secondary_amount) {
                    throw new Exception('Saldo tidak mencukupi untuk pembayaran kedua');
                }
                $new_balance = $member_data->balance - $secondary_amount;
                $this->db->where('id', $member_data->id)
                         ->update('users', ['balance' => $new_balance]);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal melakukan transaksi');
            }

            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil</div>');
            redirect('transaksikasir/tambahTransaksiKasir');

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>');
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
        // Change to use getTopupByIdCabang instead
        $data['trans'] = $this->topup->getTopupByIdCabang($login_session['branch_id']);
        
        $this->template->load('templates/kasir', 'topup/dataKasir', $data);
    }

    private function generate_struk_filename() {
        $timestamp = date('YmdHis'); // Format: 20240324153000 (tahun bulan tanggal jam menit detik)
        $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8); // 8 karakter random
        return "TRX{$timestamp}{$random}";
    }

    private function generate_transaction_code($transaction_type) {
        // Get transaction type code
        $type_code = '';
        switch($transaction_type) {
            case 'Balance Top-up':
                $type_code = 'BTU';
                break;
            case 'Teras Japan Payment':
                $type_code = 'TJP';
                break;
        }
        
        // Generate timestamp YYYYMMDDHHMISS
        $timestamp = date('YmdHis');
        
        // Generate 6 random characters
        $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);
        
        // Format: TRX{type_code}{timestamp}{random}
        return "TRX{$type_code}{$timestamp}{$random}";
    }
}