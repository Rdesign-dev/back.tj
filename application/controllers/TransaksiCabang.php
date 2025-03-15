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

    public function convert_and_updateSaldoCabang() {
        $login_session = $this->session->userdata('login_session');
        $account_id = $login_session['id'];
        $branch_id = $login_session['branch_id'];
    
        // Set validation rules
        $this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric|greater_than_equal_to[10000]');
        $this->form_validation->set_rules('metode', 'Metode Pembayaran', 'required|in_list[cash,transferBank]');
        if($this->input->post('metode') == 'transferBank') {
            $this->form_validation->set_rules('bukti', 'Bukti Transfer', 'callback_file_check');
        }
    
        if($this->form_validation->run() == FALSE) {
            $data['title'] = "Top Up Saldo";
            $data['member'] = $this->session->userdata('member_data');
            $this->template->load('templates/cabang', 'transaksi/transaksiMemberSaldoCabang', $data);
            return;
        }
    
        $this->db->trans_start();
    
        try {
            $nominal = str_replace(',', '', $this->input->post('nominal'));
            $nominal = floatval($nominal);
            $phone_number = $this->input->post('nomor');
            
            $user = $this->db->select('id, balance')
                            ->where('phone_number', $phone_number)
                            ->get('users')
                            ->row();
    
            if(!$user) {
                throw new Exception('Member tidak ditemukan');
            }
    
            // Handle file upload if transfer
            $evidence_filename = 'struk.png';
            if($this->input->post('metode') == 'transferBank') {
                $config['upload_path'] = '../ImageTerasJapan/transaction_proof/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 10240;
                $config['file_name'] = 'TRXBT' . $phone_number . mt_rand(1000, 9999);
    
                $this->load->library('upload', $config);
    
                if(!$this->upload->do_upload('bukti')) {
                    throw new Exception('Gagal upload bukti: ' . $this->upload->display_errors('',''));
                }
    
                $upload_data = $this->upload->data();
                $evidence_filename = $upload_data['file_name'];
            }
    
            $sequence = $this->transaksi->getNextSequence();
            $payment_method = $this->input->post('metode');
            $payment_code = ($payment_method == 'cash') ? 'CSH' : 'TFB';
    
            // Generate transaction code using kasir format + BT for Balance Top-up
            $transaction_code = "TX" . $login_session['branch_id'] . 
                              $login_session['id'] . 
                              "BT" . 
                              $payment_code . 
                              date('dmy') . 
                              sprintf('%04d', $sequence);
    
            $transaction_data = [
                'transaction_codes' => $transaction_code,
                'user_id' => $user->id,
                'transaction_type' => 'Balance Top-up',
                'amount' => number_format($nominal, 2, '.', ''),
                'branch_id' => $branch_id,
                'account_cashier_id' => $account_id,
                'payment_method' => $payment_method,
                'transaction_evidence' => $evidence_filename,
                'created_at' => date('Y-m-d H:i:s')
            ];
    
            $this->db->insert('transactions', $transaction_data);
    
            // Update user balance
            $current_balance = floatval($user->balance);
            $new_balance = $current_balance + $nominal;
            
            $this->db->where('id', $user->id)
                     ->update('users', ['balance' => number_format($new_balance, 2, '.', '')]);
    
            $this->db->trans_complete();
    
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal melakukan transaksi');
            }
    
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Top up saldo berhasil</div>');
            redirect('transaksicabang/getHistorysaldoCabang');
    
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>');
            redirect('transaksicabang/saldoCabang');
        }
    }

    public function saldo() {
        $data['title'] = "Top Up Saldo";
        $data['member'] = $this->member->find_all();
        $this->template->load('templates/cabang', 'transaksi/addSaldoCabang', $data);
    }

    public function cari_memberSaldo() {
        $this->form_validation->set_rules('nohp','NomorHp','required');
        if($this->form_validation->run() == false) {
            $data['title'] = "Top Up Saldo";
            $this->template->load('templates/cabang', 'transaksi/addSaldoCabang', $data);
        } else {
            $nohp = $this->input->post('nohp');
            $member = $this->db->get_where('users', ['phone_number' => $nohp])->row();
            
            if($member) {
                $this->session->set_userdata('member_data', $member);
                $data['member'] = $member;
                $data['title'] = "Topup Saldo Member";
                $this->template->load('templates/cabang', 'transaksi/transaksiMemberSaldoCabang', $data);
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
                redirect('transaksicabang/saldoCabang');
            }
        }
    }

    public function convert_and_updateSaldoMember() {
        $login_session = $this->session->userdata('login_session');
        $account_id = $login_session['id'];
        $branch_id = $login_session['branch_id'];

        // Set validation rules
        $this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric|greater_than_equal_to[10000]');
        $this->form_validation->set_rules('metode', 'Metode Pembayaran', 'required|in_list[cash,transferBank]');
        if($this->input->post('metode') == 'transferBank') {
            $this->form_validation->set_rules('bukti', 'Bukti Transfer', 'callback_file_check');
        }

        if($this->form_validation->run() == FALSE) {
            $data['title'] = "Top Up Saldo";
            $data['member'] = $this->session->userdata('member_data');
            $this->template->load('templates/cabang', 'transaksi/transaksiMemberSaldoCabang', $data);
            return;
        }

        // Start transaction
        $this->db->trans_start();

        try {
            $nominal = str_replace(',', '', $this->input->post('nominal')); // Remove thousand separators
            $nominal = floatval($nominal); // Convert to float for decimal handling
            $phone_number = $this->input->post('nomor');
            
            // Get current user data with precise balance
            $user = $this->db->select('id, balance')
                            ->where('phone_number', $phone_number)
                            ->get('users')
                            ->row();

            if(!$user) {
                throw new Exception('Member tidak ditemukan');
            }

            // Handle file upload if transfer
            $evidence_filename = 'struk.png';
            if($this->input->post('metode') == 'transferBank') {
                $config['upload_path'] = '../ImageTerasJapan/transaction_proof/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 10240; // 10MB
                $config['file_name'] = 'TRXBT' . $phone_number . mt_rand(1000, 9999);

                $this->load->library('upload', $config);

                if(!$this->upload->do_upload('bukti')) {
                    throw new Exception('Gagal upload bukti: ' . $this->upload->display_errors('',''));
                }

                $upload_data = $this->upload->data();
                $evidence_filename = $upload_data['file_name'];
            }

            // Prepare transaction data with proper decimal handling
            $transaction_data = [
                'transaction_codes' => $this->generate_transaction_code($account_id, 'Balance Top-up'),
                'user_id' => $user->id,
                'transaction_type' => 'Balance Top-up',
                'amount' => number_format($nominal, 2, '.', ''), // Ensure 2 decimal places
                'branch_id' => $branch_id,
                'account_cashier_id' => $account_id,
                'payment_method' => $this->input->post('metode'),
                'transaction_evidence' => $evidence_filename,
                'created_at' => date('Y-m-d H:i:s')
            ];

                    // Add debug output
            echo "<pre>";
            echo "Data to be inserted:\n";
            var_dump($transaction_data);
            echo "\nUser data:\n";
            var_dump($user);
            echo "\nSession data:\n";
            var_dump($login_session);
            die();

            // Insert transaction
            $this->db->insert('transactions', $transaction_data);

            // Calculate new balance with proper decimal handling
            $current_balance = floatval($user->balance);
            $new_balance = $current_balance + $nominal;

            // Debug output
            echo "<pre>";
            echo "Current balance: " . $current_balance . "\n";
            echo "Nominal: " . $nominal . "\n";
            echo "New balance: " . $new_balance . "\n";
            echo "</pre>";
            die();

            // Update user balance using precise decimal
            $this->db->where('id', $user->id)
                ->update('users', [
                'balance' => number_format($new_balance, 2, '.', '')
                ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal melakukan transaksi');
            }

            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Top up saldo berhasil</div>');
            redirect('transaksi/getHistorysaldo');

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>');
            redirect('transaksi/saldo');
        }
    }

    private function generate_transaction_code($account_id, $transaction_type) {
        $login_session = $this->session->userdata('login_session');
        $sequence = $this->transaksi->getNextSequence();
        
        // Get transaction type code
        $type_code = '';
        switch($transaction_type) {
            case 'Balance Top-up':
                $type_code = 'BT';
                break;
            case 'Teras Japan Payment':
                $type_code = 'TJP';
                break;
            case 'Redeem Voucher':
                $type_code = 'RV';
                break;
        }

        // Get payment method code
        $payment_method = $this->input->post('metode');
        $payment_code = ($payment_method == 'cash') ? 'CSH' : 'TFB';

        // Generate code format: TX{branch_id}{account_id}{type_code}{payment_code}{date}{sequence}
        return "TX" . $login_session['branch_id'] . 
               $login_session['id'] . 
               $type_code . 
               $payment_code . 
               date('dmy') . 
               sprintf('%04d', $sequence);
    }
}

