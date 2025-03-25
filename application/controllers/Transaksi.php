<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Member_model', 'member');
        $this->load->model('Transaksi_model', 'transaksi');
        $this->load->model('auth_model', 'user'); 
        $this->load->model('topup_model', 'topup');
        $this->load->model('cabang_model','cabang');
        $this->load->model('voucher_model','voucher');
        $this->load->library('template');
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
        log_message('debug', 'Starting convert_and_update method');
        
        // Get login session data
        $login_session = $this->session->userdata('login_session');
        
        // Check if user is logged in
        if (!$login_session) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Silahkan login terlebih dahulu</div>');
            redirect('auth');
            return;
        }
    
        // Load required models
        $this->load->model('Transaksi_model');
        $this->load->model('Member_model');
    
        // Get user_id by phone number
        $phone_number = $this->input->post('nomor');
        $user = $this->db->get_where('users', ['phone_number' => $phone_number])->row();
    
        if (!$user) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
            redirect('transaksi/add');
            return;
        }
    
        // Start transaction
        $this->db->trans_start();
    
        try {
            $total = $this->input->post('total');
            $payment_method = $this->input->post('primary_payment_method');
            $branch_id = $this->input->post('nocabang');
            $is_redeem_voucher = $this->input->post('tukarVoucher') === 'on'; // Changed from '1' to 'on'
            $voucher_code = $this->input->post('kode_voucher'); // Changed from 'kodevouchertukar' to 'kode_voucher'
            
            // Validate voucher if used
            if ($is_redeem_voucher && $voucher_code) {
                // Check if voucher exists and belongs to user
                $voucher = $this->db->select('rv.redeem_id, rv.user_id, rv.status')
                                ->from('redeem_voucher rv')
                                ->where('rv.kode_voucher', $voucher_code) // Changed from 'kodevouchertukar' to 'kode_voucher'
                                ->where('rv.user_id', $user->id)
                                ->where('rv.status', 'Available')
                                ->get()
                                ->row();
    
                if (!$voucher) {
                    throw new Exception('Voucher tidak valid atau bukan milik member ini');
                }

                // Debug voucher query
                log_message('debug', 'Voucher query: ' . $this->db->last_query());
                log_message('debug', 'Voucher data: ' . print_r($voucher, true));
            }
    
            // Handle file upload
            $config['upload_path'] = '../ImageTerasJapan/transaction_proof/Payment/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 5048;
            $config['file_name'] = $this->generate_struk_filename();
            $this->load->library('upload', $config);
    
            $transaction_evidence = 'struk.png'; // default value
            if ($this->upload->do_upload('fotobill')) {
                $uploaded_data = $this->upload->data();
                $transaction_evidence = $uploaded_data['file_name'];
            }
    
            // Prepare transaction data with current timestamp
            $transaction_data = [
                'transaction_codes' => $this->generate_transaction_code('Teras Japan Payment'),
                'user_id' => $user->id,
                'transaction_type' => 'Teras Japan Payment',
                'amount' => $total,
                'branch_id' => $branch_id,
                'account_cashier_id' => $login_session['id'],
                'transaction_evidence' => $transaction_evidence,
                'created_at' => date('Y-m-d H:i:s'), // Menggunakan timestamp saat ini
                'voucher_id' => $is_redeem_voucher && isset($voucher->redeem_id) ? $voucher->redeem_id : null
            ];

            // Debug: Print POST data
            // echo "<pre>POST Data:";
            // var_dump($_POST);
            
            // echo "\nVoucher Query:";
            // echo $this->db->last_query();
            
            // echo "\nTransaction Data to Insert:";
            // var_dump($transaction_data);
    
            // die('Debug output - stopping here'); // Stop execution to see the debug output
    
            // Insert transaction
            $this->db->insert('transactions', $transaction_data);
            $transaction_id = $this->db->insert_id();
    
            // Handle payments
            if ($this->input->post('splitBill')) {
                // Get amounts from the correct POST fields
                $primary_amount = (int)preg_replace('/[^\d]/', '', $this->input->post('primary_amount_display'));
                $secondary_amount = (int)preg_replace('/[^\d]/', '', $this->input->post('secondary_amount_display'));
                
                // Get payment methods
                $primary_payment_method = $this->input->post('primary_payment_method');
                $secondary_payment_method = $this->input->post('secondary_payment_method');
                
                // Validate amounts
                if ($primary_amount <= 0 || $secondary_amount <= 0) {
                    throw new Exception('Jumlah pembayaran tidak valid');
                }
            
                // Check balance if either payment uses Balance
                if ($primary_payment_method == 'Balance') {
                    if ($user->balance < $primary_amount) {
                        throw new Exception('Saldo tidak mencukupi untuk pembayaran pertama');
                    }
                }
                
                if ($secondary_payment_method == 'Balance') {
                    if ($user->balance < $secondary_amount) {
                        throw new Exception('Saldo tidak mencukupi untuk pembayaran kedua');
                    }
                }
            
                // Insert primary payment
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $primary_payment_method,
                    'amount' => $primary_amount
                ]);
            
                // Insert secondary payment
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $secondary_payment_method,
                    'amount' => $secondary_amount
                ]);
            
                // Update balance for each Balance payment
                if ($primary_payment_method == 'Balance') {
                    $this->db->set('balance', "balance - {$primary_amount}", FALSE)
                             ->where('id', $user->id)
                             ->update('users');
                }
                
                if ($secondary_payment_method == 'Balance') {
                    $this->db->set('balance', "balance - {$secondary_amount}", FALSE)
                             ->where('id', $user->id)
                             ->update('users');
                }
            } else {
                // Single payment
                $this->db->insert('transaction_payments', [
                    'transaction_id' => $transaction_id,
                    'payment_method' => $payment_method,
                    'amount' => $total
                ]);
            
                // Update balance if using Balance
                if ($payment_method == 'Balance') {
                    if ($user->balance < $total) {
                        throw new Exception('Saldo tidak mencukupi');
                    }
                    $this->db->set('balance', "balance - {$total}", FALSE)
                             ->where('id', $user->id)
                             ->update('users');
                }
            }
            
            // Add debugging before the payment processing
            // echo "<pre>Payment Data:";
            // var_dump([
            //     'splitBill' => $this->input->post('splitBill'),
            //     'primary_amount_display' => $this->input->post('primary_amount_display'),
            //     'secondary_amount_display' => $this->input->post('secondary_amount_display'),
            //     'primary_payment_method' => $payment_method,
            //     'secondary_payment_method' => $this->input->post('secondary_payment_method'),
            //     'total' => $total
            // ]);
            // die();
    
            // Update balance if using it
            if ($payment_method == 'Balance') {
                $amount_to_deduct = $this->input->post('splitBill') ? $primary_amount : $total;
                $this->db->set('balance', 'balance - ' . $amount_to_deduct, FALSE)
                         ->where('id', $user->id)
                         ->update('users');
            }
    
            // Update voucher status if used
            if ($is_redeem_voucher && $voucher) {
                $this->db->where('redeem_id', $voucher->redeem_id)
                         ->update('redeem_voucher', ['status' => 'Used']);
            }
    
            // Increment branch transaction count
            $this->db->set('transaction_count', 'transaction_count + 1', FALSE)
                     ->where('id', $branch_id)
                     ->update('branch');
    
            $this->db->trans_complete();
    
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan transaksi');
            }
    
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil disimpan</div>');
            redirect('transaksi/historyTransaksi');
    
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>');
            redirect('transaksi/add');
        }
    }
        // public function file_check($str)
        // {
        //     // Check if a file is uploaded
        //     if (empty($_FILES['bukti']['name'])) {
        //         $this->form_validation->set_message('file_check', 'The {field} field is required.');
        //         return false;
        //     }

        //     // Additional checks if needed (e.g., file type, size, etc.)

        //     return true;
        // }
        public function dataTopup()
	    {
        $data['title'] = "Data TopUp Saldo Member";
        $data['tops'] = $this->topup->getTopupDetails();
		$this->template->load('templates/dashboard', 'topup/index', $data);
	    }
        public function historyTransaksi() {
    $data['title'] = "History Transaksi";
    
    // Modified query to filter and order by transaction_id
    $data['trans'] = $this->db->select('t.*, b.branch_name, u.name as member_name, 
                    a.Name as cashier_name, rv.kode_voucher,
                    GROUP_CONCAT(CONCAT(tp.payment_method, " (", tp.amount, ")") SEPARATOR " & ") as payment_details,
                    SUM(tp.amount) as total_amount')
             ->from('transactions t')
             ->join('branch b', 'b.id = t.branch_id', 'left')
             ->join('users u', 'u.id = t.user_id', 'left')
             ->join('accounts a', 'a.id = t.account_cashier_id', 'left')
             ->join('redeem_voucher rv', 'rv.redeem_id = t.voucher_id', 'left')
             ->join('transaction_payments tp', 'tp.transaction_id = t.transaction_id', 'left')
             ->where('t.transaction_type', 'Teras Japan Payment')
             ->group_by('t.transaction_id')
             ->order_by('t.transaction_id', 'DESC')
             ->get()
             ->result();
    
    $this->template->load('templates/dashboard', 'transaksi/historyTransaksi', $data);
}
        public function historyTransaksiKasir()
{
    $login_session = $this->session->userdata('login_session');
    $branch_id = $login_session['branch_id'];
    
    $data['title'] = "History Transaksi";
    
    // Menggunakan model yang sudah ada dengan tambahan where clause
    $data['trans'] = $this->db->select('t.*, b.branch_name, u.name as member_name, 
                    a.Name as cashier_name, rv.kode_voucher')
             ->from('transactions t')
             ->join('branch b', 'b.id = t.branch_id', 'left')
             ->join('users u', 'u.id = t.user_id', 'left')
             ->join('accounts a', 'a.id = t.account_cashier_id', 'left')
             ->join('redeem_voucher rv', 'rv.redeem_id = t.voucher_id', 'left')
             ->where('t.branch_id', $branch_id)
             ->where_in('t.transaction_type', ['Teras Japan Payment', 'Redeem Voucher'])
             ->order_by('t.created_at', 'DESC')
             ->get()
             ->result();
    
    $this->template->load('templates/kasir', 'transaksi/historyTransaksiKasir', $data);
}


public function saldo()
{
    $data['title'] = "Top Up Saldo";
    $data['member'] = $this->member->find_all();
    $this->template->load('templates/dashboard', 'transaksi/addSaldo', $data);
}

public function cari_memberSaldo(){
    $this->form_validation->set_rules('nohp','NomorHp','required');
    if($this->form_validation->run() == false){
        $data['title'] = "Top Up Saldo";
        $this->template->load('templates/dashboard', 'transaksi/addSaldo', $data);
    } else {
        $nohp = $this->input->post('nohp');
        // Get member data from database
        $member = $this->db->get_where('users', ['phone_number' => $nohp])->row();
        
        if($member){
            $this->session->set_userdata('member_data', $member);
            $data['member'] = $member;
            $data['title'] = "Topup Saldo Member";
            $this->template->load('templates/dashboard', 'transaksi/transaksiMemberSaldo', $data);
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Member tidak ditemukan</div>');
            redirect('transaksi/saldo');
        }
    }
}
public function convert_and_updateSaldoMember() 
{
    $login_session = $this->session->userdata('login_session');
    $account_id = $login_session['id'];
    
    // Set validation rules
    $this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric|greater_than_equal_to[10000]');
    $this->form_validation->set_rules('metode', 'Metode Pembayaran', 'required|in_list[cash,transferBank]');
    
    if ($this->form_validation->run() == FALSE) {
        $data['title'] = "Top Up Saldo";
        $data['member'] = $this->session->userdata('member_data');
        $this->template->load('templates/dashboard', 'transaksi/transaksiMemberSaldo', $data);
        return;
    }

    // Start transaction
    $this->db->trans_start();

    try {
        $nominal = str_replace(',', '', $this->input->post('nominal'));
        $phone_number = $this->input->post('nomor');
        
        // Get user data
        $user = $this->db->select('id, balance')
                        ->where('phone_number', $phone_number)
                        ->get('users')
                        ->row();

        if (!$user) {
            throw new Exception('Member tidak ditemukan');
        }

        // Handle file upload for transfer
        $evidence_filename = 'struk.png';
        if ($this->input->post('metode') == 'transferBank') {
            $config['upload_path'] = '../ImageTerasJapan/transaction_proof/Topup';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 10240; // 10MB
            $config['file_name'] = $this->generate_evidence_filename($user->id);

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('bukti')) {
                throw new Exception('Gagal upload bukti: ' . $this->upload->display_errors('',''));
            }

            $upload_data = $this->upload->data();
            $evidence_filename = $upload_data['file_name'];
        }

        // Prepare transaction data
        $transaction_data = [
            'transaction_codes' => $this->generate_transaction_code('Balance Top-up'),
            'user_id' => $user->id,
            'transaction_type' => 'Balance Top-up',
            'amount' => $nominal,
            'branch_id' => null, // Set null for admin pusat
            'account_cashier_id' => $account_id,
            'transaction_evidence' => $evidence_filename,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert transaction
        $this->db->insert('transactions', $transaction_data);
        $transaction_id = $this->db->insert_id();

        // Insert payment detail
        $payment_data = [
            'transaction_id' => $transaction_id,
            'payment_method' => $this->input->post('metode'),
            'amount' => $nominal
        ];
        $this->db->insert('transaction_payments', $payment_data);

        // Update user balance
        $new_balance = $user->balance + $nominal;
        $this->db->where('id', $user->id)
                 ->update('users', ['balance' => $new_balance]);

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
    $data['title'] = "History Top Up Saldo";
    
    $this->db->select('t.transaction_codes, 
                       t.created_at, 
                       t.amount,  
                       t.transaction_evidence,
                       COALESCE(b.branch_name, "Pusat") as branch_name, 
                       u.name as member_name, 
                       a.Name as cashier_name,
                       GROUP_CONCAT(CONCAT(tp.payment_method, " (Rp ", FORMAT(tp.amount, 0), ")") SEPARATOR " & ") as payment_method')
             ->from('transactions t')
             ->join('users u', 'u.id = t.user_id')
             ->join('accounts a', 'a.id = t.account_cashier_id')
             ->join('branch b', 'b.id = t.branch_id', 'left')  // Ubah menjadi LEFT JOIN
             ->join('transaction_payments tp', 'tp.transaction_id = t.transaction_id')
             ->where('t.transaction_type', 'Balance Top-up')
             ->group_by('t.transaction_id, t.transaction_codes, t.created_at, t.amount, 
                        t.transaction_evidence, b.branch_name, u.name, a.Name')
             ->order_by('t.created_at', 'DESC');
             
    $data['trans'] = $this->db->get()->result();
    
    $this->template->load('templates/dashboard', 'topup/index', $data);
}

// Add this function to each controller (Transaksi, TransaksiKasir, TransaksiCabang)
private function generate_transaction_code($transaction_type) {
    // Get type code based on transaction type
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

private function generate_evidence_filename($user_id) {
    $timestamp = date('YmdHis');
    $random = substr(str_shuffle("0123456789"), 0, 3);
    return "{$user_id}-SU-{$timestamp}-{$random}";
}

public function add()
{
    $data['title'] = "Transaksi Member";
    // Fix the query to use correct column name 'id' instead of 'branch_id'
    $data['cabang'] = $this->db->select('id, branch_code, branch_name')
                               ->from('branch')
                               ->get()
                               ->result_array();
    $data['member'] = $this->session->userdata('member_data');
    $this->template->load('templates/dashboard', 'transaksi/transaksiMember', $data);
}

// Add this new method to increment transaction count
private function incrementBranchTransactionCount($branch_id) {
    $this->db->set('transaction_count', 'transaction_count + 1', FALSE);
    $this->db->where('id', $branch_id);
    return $this->db->update('branch');
}

public function dashboardKasir()
{
    $login_session = $this->session->userdata('login_session');
    $branch_id = $login_session['idcabang'];
    
    // Get transaction count for the branch
    $this->db->select('transaction_count');
    $this->db->from('branch');
    $this->db->where('id', $branch_id);
    $query = $this->db->get();
    $result = $query->row();
    
    $data['transaksi'] = $result->transaction_count;
    $this->template->load('templates/kasir', 'dashboardKasir', $data);
}

public function getHistorytopupKasir()
{
    $login_session = $this->session->userdata('login_session');
    $branch_id = $login_session['branch_id'];
    
    $data['title'] = "History Top Up Saldo";
    
    $data['trans'] = $this->db->select('
            t.transaction_codes,
            t.created_at,
            t.amount as nominal,
            t.payment_method as metode,
            t.transaction_evidence as bukti,
            u.name as namamember,
            a.Name as nama_kasir
        ')
        ->from('transactions t')
        ->join('users u', 'u.id = t.user_id', 'left')
        ->join('accounts a', 'a.id = t.account_cashier_id', 'left')
        ->where('t.branch_id', $branch_id)
        ->where('t.transaction_type', 'Balance Top-up')
        ->order_by('t.created_at', 'DESC')
        ->get()
        ->result();
    
    $this->template->load('templates/kasir', 'topup/dataKasir', $data);
    }

    private function generate_struk_filename() {
        $timestamp = date('YmdHis'); // Format: 20240324153000 (tahun bulan tanggal jam menit detik)
        $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6); // 6 karakter random
        return "TRX{$timestamp}{$random}";
}

}