<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model{

    public $table = "member";

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
        return $this->db->insert('users', $data);
    }

    public function find_all(){
        return $this->db->select('id, name, phone_number, email, balance, poin, registration_time')
                    ->from('users')
                    ->order_by('registration_time', 'DESC')
                    ->get()
                    ->result_array();
    }

    public function update($id,$data){
        $this->db->where('id',$id);
        return $this->db->update('users', $data);
    }

    public function delete($id){
        $this->db->where('id',$id);
        $this->db->delete($this->table);
    }

    public function tambahPoin($idmember, $poin) {
        $this->db->where('id', $idmember);
        $this->db->set('poin', 'poin+' . $poin, FALSE);
        $this->db->update('member');
    }

    public function get_by_nomor($nomor)
    {
        return $this->db->get_where('member',array('nomor' => $nomor))->row();
    }

    public function get_by_email($email)
    {
        return $this->db->get_where('member',array('email' => $email))->row();
    }

    public function find_by_nohp($nohp){
        $result = $this->db->query("SELECT * from member where nomor = $nohp")->result_array();
        return $result;
    }

    public function cari_detail_id($nomor){
        $this->db->where('nomor', $nomor);
        $query = $this->db->get('member');

        return $query->row();
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
        $this->db->like('namamember', $keyword);
        $this->db->or_like('nomor', $keyword);
        $this->db->or_like('email', $keyword);
    
        return $this->db->get('member')->result_array();
    }

    public function updateMemberSaldo($nomor, $nominal) {
        $saldo_sekarang = $this->db->get_where('member', array('nomor' => $nomor))->row()->saldo;
        
        $saldo_baru = $saldo_sekarang + $nominal;

        $this->db->where('nomor', $nomor);
        $this->db->update('member', array('saldo' => $saldo_baru));

        return $saldo_baru;
    }

    public function get_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row_array();
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

