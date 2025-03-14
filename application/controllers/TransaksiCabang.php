<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransaksiCabang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Member_model', 'member');
        $this->load->model('Transaksi_model', 'transaksi');
        $this->load->model('auth_model', 'user'); 
        $this->load->model('transaksi_model', 'transaksi');
        $this->load->model('topup_model', 'topup');
        $this->load->model('member_model','member');
        $this->load->model('cabang_model','cabang');
        $this->load->library('template');
        $this->load->library('form_validation');
    }

    public function cari_member_cabang()
    {
        $this->form_validation->set_rules('nomor', 'NomorHp', 'required|numeric');
        if ($this->form_validation->run() == false) {
            $data['title'] = "Transaksi Member";
            $this->template->load('templates/cabang', 'transaksi/addCabang', $data);
        } else {
            $nomor = $this->input->post('nomor');
            
            $member_data = $this->db->get_where('users', ['phone_number' => $nomor])->row();

            if (empty($member_data)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
                redirect('transaksicabang/tambahTransaksiCabang');
                return;
            }

            $this->session->set_userdata('member_data', $member_data);
            
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

            $this->template->load('templates/cabang', 'transaksi/transaksiMemberCabang', $data);
        }
    }

    public function tambahTransaksiCabang() {
        $data['title'] = "Transaksi Member";
        $this->template->load('templates/cabang', 'transaksi/addCabang', $data);
    }

    public function convert_and_updateCabang() 
    {
        $login_session = $this->session->userdata('login_session');
        $member_data = $this->session->userdata('member_data');

        // // Debug data before insert
        // var_dump([
        //     'POST Data' => $this->input->post(),
        //     'Session Data' => [
        //         'login_session' => $login_session,
        //         'member_data' => $member_data
        //     ],
        //     'Files' => $_FILES
        // ]);
        // die(); // Stop execution to see the dump

        if ($this->input->post('tukarVoucher')) {
            $this->form_validation->set_rules('kodevouchertukar', 'Voucher', 'required');
        } else {
            $this->form_validation->set_rules('total', 'Total', 'required|numeric');
            $this->form_validation->set_rules('payment_method', 'Metode Pembayaran', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Transaksi Member";
            $data['member'] = $member_data;
            $this->template->load('templates/cabang', 'transaksi/transaksiMemberCabang', $data);
        } else {
            $config['upload_path'] = '../ImageTerasJapan/transaction_proof/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = 'TRX_' . time();

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('fotobill')) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>');
                redirect('transaksicabang/tambahTransaksiCabang');
                return;
            }

            $upload_data = $this->upload->data();
            $is_voucher = $this->input->post('tukarVoucher') === 'on';
            $amount = $this->input->post('total');
            $payment_method = $this->input->post('payment_method');

            if (!$is_voucher && $payment_method === 'BM') {
                if ($member_data->balance < $amount) {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Saldo member tidak mencukupi</div>');
                    redirect('transaksicabang/tambahTransaksiCabang');
                    return;
                }
            }

            $sequence = $this->transaksi->getNextSequence();
            $transaction_code = "TX" . $login_session['branch_id'] . 
                            $login_session['id'] . 
                            ($is_voucher ? "RV" : "TJP") . 
                            ($payment_method ?? "NULL") . 
                            date('dmy') . 
                            sprintf('%04d', $sequence);

            $payment_method_mapping = [
                'CSH' => 'cash',
                'TFB' => 'transferBank',
                'BM' => 'Balance'
            ];

            $data = [
                'transaction_codes' => $transaction_code,
                'user_id' => $member_data->id,
                'transaction_type' => $is_voucher ? 'Redeem Voucher' : 'Teras Japan Payment', // Match enum exactly
                'amount' => $is_voucher ? null : $amount,
                'branch_id' => $login_session['branch_id'],
                'account_cashier_id' => $login_session['id'],
                'payment_method' => $is_voucher ? null : $payment_method_mapping[$payment_method], // Use lowercase values
                'transaction_evidence' => $upload_data['file_name'],
                'voucher_id' => $is_voucher ? $this->input->post('kodevouchertukar') : null,
                'created_at' => $this->input->post('tanggaltransaksi')
            ];

            // Debug data
            // echo "<pre>";
            // echo "Data to be inserted:\n";
            // var_dump($data);
            // echo "</pre>";
            // die();

            $this->db->trans_start();

            // Add debug logging
            log_message('debug', 'Data to be inserted: ' . json_encode($data));

            $insert_result = $this->db->insert('transactions', $data);
            
            if (!$insert_result) {
                log_message('error', 'DB Error: ' . $this->db->error()['message']);
                echo "DB Error: " . $this->db->error()['message'];
                die();
            }

            if (!$is_voucher && $data['transaction_type'] === 'Teras Japan Payment') {
                $points_earned = floor($amount / 10000);
                $new_points = $member_data->poin + $points_earned;

                $this->db->where('id', $member_data->id)
                         ->update('users', ['poin' => $new_points]);
            }

            if (!$is_voucher && $payment_method === 'BM') {
                $new_balance = $member_data->balance - $amount;
                $this->db->where('id', $member_data->id)
                         ->update('users', ['balance' => $new_balance]);
            }

            if ($is_voucher) {
                $this->db->where('redeem_id', $data['voucher_id'])
                         ->update('redeem_voucher', ['status' => 'Used']);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $error = $this->db->error();
                log_message('error', 'Transaction failed: ' . json_encode($error));
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Transaksi gagal: ' . $error['message'] . '</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil</div>');
            }

            redirect('transaksicabang/tambahTransaksiCabang');
        }
    }

    public function historyTransaksiCabang()
    {
        $login_session = $this->session->userdata('login_session');
        
        $data['title'] = 'Riwayat Transaksi';
        $data['trans'] = $this->transaksi->getTransaksiByIdCabangWithDetails($login_session['branch_id']);
        
        $this->template->load('templates/cabang', 'transaksi/historyTransaksiCabang', $data);
    }

    public function getHistorysaldoCabang()
    {
        $login_session = $this->session->userdata('login_session');
        
        $data['title'] = 'Riwayat Top Up Saldo';
        $data['trans'] = $this->topup->getTopupByIdCabang($login_session['branch_id']);
        
        $this->template->load('templates/cabang', 'topup/dataCabang', $data);
    }

    public function saldoCabang() 
    {
        $data['title'] = "Top Up Saldo";
        $this->template->load('templates/cabang', 'transaksi/transaksiMemberSaldoCabang', $data);
    }
}