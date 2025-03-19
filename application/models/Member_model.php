<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model{

    public $table = "users"; // Changed from member to users

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
        return $this->db->insert('users', $data);
    }

    public function find_all(){
        return $this->db->select('
            name as namamember, 
            phone_number as nomor, 
            poin,
            balance as saldo, 
            registration_time as tanggaldaftar
        ')
        ->from('users')
        ->order_by('registration_time', 'DESC')
        ->get()
        ->result_array();
    }

    public function update($data, $id) 
    {
        return $this->db->update('users', $data, ['id' => $id]);
    }

    public function delete($id){
        $this->db->where('id',$id);
        $this->db->delete($this->table);
    }

    public function tambahPoin($idmember, $poin) {
        $this->db->where('id', $idmember);
        $this->db->set('poin', 'poin+' . $poin, FALSE);
        $this->db->update('users');
    }

    public function get_by_nomor($nomor)
    {
        return $this->db->where('phone_number', $nomor)
                        ->get('users')
                        ->row();
    }

    public function get_by_email($email) {
        return $this->db->get_where('users', ['email' => $email])->row();
    }

    public function find_by_nohp($nohp){
        return $this->db->get_where('users', ['phone_number' => $nohp])->result_array();
    }

    public function cari_detail_id($phone_number) 
    {
        return $this->db->select('id, name, phone_number, email, address, gender, 
                                 birthdate, city, profile_pic, balance, poin')
                        ->from('users')
                        ->where('phone_number', $phone_number)
                        ->get()
                        ->row();
    }
    
    public function cari_transaksi_id($id){
        $result =  $this->db->query("SELECT * from transaksi WHERE idmember='$id'")->result_array();
        if($result){
            return $result[0];
        }else{
            return false;
        }
    }

    public function cari_member($keyword) {
        $this->db->like('name', $keyword);
        $this->db->or_like('phone_number', $keyword);
        $this->db->or_like('email', $keyword);
    
        return $this->db->get('users')->result_array();
    }

    public function updateMemberSaldo($nomor, $nominal) {
        $saldo_sekarang = $this->db->get_where('users', ['phone_number' => $nomor])->row()->balance;
        
        $saldo_baru = $saldo_sekarang + $nominal;

        $this->db->where('phone_number', $nomor);
        $this->db->update('users', array('balance' => $saldo_baru));

        return $saldo_baru;
    }

    public function get_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row_array();
    }

    public function get_member($id) {
        return $this->db->select('id, name, phone_number, email, address, gender, 
                                birthdate, city, profile_pic')
                        ->from('users')
                        ->where('id', $id)
                        ->get()
                        ->row_array();
    }

    public function get_login_history() 
    {
        $this->db->select('l.login_id, u.phone_number as nomor, u.name as namamember, l.datetime as tanggallogin')
                 ->from('login_users l')
                 ->join('users u', 'u.id = l.id')
                 ->order_by('l.datetime', 'DESC');
        return $this->db->get()->result();
    }
}

