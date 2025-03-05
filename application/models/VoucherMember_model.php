<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VoucherMember_model extends CI_Model {

    public $table = "voucher_member";
    public function __construct() {
        parent::__construct();
    }
    public function find_all(){
        return $this->db->get($this->table)->result_array();
    }
    public function insert($data) {
        // Insert data ke tabel 'produk'
        return $this->db->insert($this->table, $data);
    }
    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }
    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }
    public function cari_detail_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get('iklan'); // replace 'your_member_table_name' with your actual table name

        return $query->row();
    }
    public function getVoucherDetails($kodevoucher) {
        $this->db->where('kodevoucher', $kodevoucher);
        $query = $this->db->get('voucher');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false; // Voucher not found
        }
    }
    public function insertVoucherMember($kodevoucher, $nomor,$poin) {
        $expired_date = date('Y-m-d H:i:s', strtotime('+1 week'));
        $dateRedeem = date('Y-m-d H:i:s');
        $vouchergenerate = date('YmdHis');
        $data = array(
            'kodevoucher' => $kodevoucher,
            'tanggalpenukaran' => $dateRedeem,
            'vouchergenerate' => $vouchergenerate . $kodevoucher,
            'voucherexpired' => $expired_date,
            'nomor' => $nomor,
            'poin' => $poin,
            'isUse' => 0, // Set it to 0 initially as it's not used
        );

        $this->db->insert('voucher_member', $data);

        // You might want to add error handling or return a success status based on the insert result
        return $this->db->affected_rows() > 0;
    }
    public function insertVoucherNewMember($kodevoucher, $nomor,$poin, $dateRedeem, $expired_date, $vouchergenerate) {
        $data = array(
            'kodevoucher' => $kodevoucher,
            'tanggalpenukaran' => $dateRedeem,
            'vouchergenerate' => $vouchergenerate,
            'voucherexpired' => $expired_date,
            'nomor' => $nomor,
            'poin' => $poin,
            'isUse' => 0,
        );
    
        $this->db->insert('voucher_member', $data);
    
        // You might want to add error handling or return a success status based on the insert result
        return $this->db->affected_rows() > 0;
    }
    public function markVoucherAsUsed($nomor,$vouchergenerate) {
        // Assuming you have a 'used' field in your voucher table
        $data = array(
            'isUse' => 1,
        );
        $this->db->where('nomor', $nomor);
        $this->db->where('vouchergenerate', $vouchergenerate);
        $this->db->update('voucher_member', $data);

        // You might want to add error handling or return a success status based on the update result
        return $this->db->affected_rows() > 0;
    }
    public function getVoucherByNomorMemberWithDetails($nomor) {
        $this->db->select('voucher_member.*, voucher.*, member.namamember');
        $this->db->from('voucher_member');
        $this->db->join('voucher', 'voucher.kodevoucher = voucher_member.kodevoucher');
        $this->db->join('member', 'member.nomor = voucher_member.nomor');
        $this->db->where('voucher_member.nomor', $nomor);
        $this->db->order_by('voucher_member.voucherexpired', 'DESC');

        $this->db->select("DATE(voucher_member.voucherexpired) as voucherexpired", FALSE);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function deleteExpiredVouchers($nomor) {
        // Menghapus voucher yang telah kadaluwarsa untuk member tertentu dari database
        $this->db->where('voucherexpired <', date('Y-m-d'));
        $this->db->where('nomor', $nomor);
        $this->db->delete('voucher_member');
    }
    public function deleteUsedVouchers($nomor) {
        $this->db->where('isUse', 1);
        $this->db->where('nomor', $nomor);
        $this->db->delete('voucher_member');
    }
    public function deleteUsedVouchersOlderThanTwoHours($nomor) {
    // Menghapus voucher yang telah digunakan dan telah kadaluwarsa lebih dari 2 jam untuk member tertentu dari database
    $expirationTime = date('Y-m-d H:i:s', strtotime('-2 hours'));
    $this->db->where('isUse', 1);
    $this->db->where('voucherexpired <', $expirationTime);
    $this->db->where('nomor', $nomor);
    $this->db->delete('voucher_member');
    }
    public function getRedeemByNomorMemberWithDetails($nomor) {
        $this->db->select('voucher_member.*, voucher.poin as poinVoucher');
        $this->db->from('voucher_member');
        $this->db->join('voucher', 'voucher.kodevoucher = voucher_member.kodevoucher');
        $this->db->join('member', 'member.nomor = voucher_member.nomor');
        $this->db->where('voucher_member.nomor', $nomor);
        $this->db->order_by('voucher_member.voucherexpired', 'ASC');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    
}
