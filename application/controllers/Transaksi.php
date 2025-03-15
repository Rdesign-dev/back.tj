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
                'transaction_type' => $this->input->post('tukarVoucher') ? 'Redeem Voucher' : 'Teras Japan Payment',
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
    
            // Start database transaction
            $this->db->trans_start();

            // Insert transaction data
            if ($this->db->insert('transactions', $data)) {
                // If transaction uses voucher, update voucher status
                if ($this->input->post('tukarVoucher')) {
                    $kode_voucher = $this->input->post('kodevouchertukar');
                    $this->updateVoucherStatus($kode_voucher);
                }

                // Increment transaction count for the selected branch
                $branch_id = $this->input->post('nocabang');
                $this->incrementBranchTransactionCount($branch_id);

                // Complete the transaction
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Transaksi gagal: Error database</div>');
                    redirect('transaksi/add');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil disimpan</div>');
                    redirect('transaksi/historyTransaksi');
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
        public function historyTransaksi() {
    $data['title'] = "History Transaksi";
    
    $data['trans'] = $this->db->select('t.*, 
                                    b.branch_name, 
                                    u.name as member_name, 
                                    a.Name as cashier_name, 
                                    rv.kode_voucher')  // Select kode_voucher dari redeem_voucher
            ->from('transactions t')
            ->join('users u', 'u.id = t.user_id', 'left')
            ->join('branch b', 'b.id = t.branch_id', 'left')
            ->join('accounts a', 'a.id = t.account_cashier_id', 'left')
            ->join('redeem_voucher rv', 'rv.redeem_id = t.voucher_id', 'left')  // Join berdasarkan redeem_id = voucher_id
            ->where_in('t.transaction_type', ['Teras Japan Payment', 'Redeem Voucher'])
            ->order_by('t.created_at', 'DESC')
            ->get()
            ->result();

    // Debug untuk melihat hasil query
    // echo "<pre>";
    // print_r($this->db->last_query());
    // print_r($data['trans']);
    // die();
            
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
        $this->template->load('templates/dashboard', 'transaksi/transaksiMemberSaldo', $data);
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
        // echo "<pre>";
        // echo "Data to be inserted:\n";
        // var_dump($transaction_data);
        // echo "\nUser data:\n";
        // var_dump($user);
        // echo "\nSession data:\n";
        // var_dump($login_session);
        // die();

        // Insert transaction
        $this->db->insert('transactions', $transaction_data);

        // Calculate new balance with proper decimal handling
        $current_balance = floatval($user->balance);
        $new_balance = $current_balance + $nominal;

        // // Debug output
        // echo "<pre>";
        // echo "Current balance: " . $current_balance . "\n";
        // echo "Nominal: " . $nominal . "\n";
        // echo "New balance: " . $new_balance . "\n";
        // echo "</pre>";
        // die();

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

private function generate_transaction_code($account_id, $transaction_type) {
    $date = date('dmy');
    $payment_method = $this->input->post('metode');
    $payment_code = ($payment_method == 'cash') ? 'CSH' : 'TF';
    
    // Add transaction type code
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
    
    $random = mt_rand(1000, 9999);
    return "TRXSU{$type_code}{$account_id}{$random}";
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

}