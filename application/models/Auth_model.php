<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function cek_username($username)
    {
        $query = $this->db->get_where('accounts', ['username' => $username]);
        return $query->num_rows();
    }

    public function get_password($username)
    {
        $data = $this->db->get_where('accounts', ['username' => $username])->row_array();
        return $data['password'];
    }

    public function userdata($username)
    {
        return $this->db->get_where('accounts', ['username' => $username])->row_array();
    }

    public function get_branch_id($user_id)
    {
        $this->db->select('branch_id');
        $this->db->where('id', $user_id);
        $result = $this->db->get('accounts');
        if ($result->num_rows() > 0) {
            return $result->row()->branch_id;
        } else {
            return null;
        }
    }

    public function get_name_id($user_id)
    {
        // Pertama kita ambil branch_id dari user
        $this->db->select('branch_id, account_type');
        $this->db->where('id', $user_id);
        $user_result = $this->db->get('accounts');

        if ($user_result->num_rows() > 0) {
            $user_data = $user_result->row();

            // Jika user adalah super_admin atau admin_central, mereka tidak memiliki cabang
            if ($user_data->account_type == 'super_admin' || $user_data->account_type == 'admin_central') {
                return null;
            }

            // Jika punya branch_id, ambil nama cabang dari tabel branch
            if ($user_data->branch_id) {
                $this->db->select('branch_name');
                $this->db->where('id', $user_data->branch_id);
                $branch_result = $this->db->get('branch');

                if ($branch_result->num_rows() > 0) {
                    return $branch_result->row()->branch_name;
                }
            }
        }
        return null;
    }

    public function get_cabang_by_id($idcabang) {
        $this->db->select('branch_name');
        $this->db->from('branch');
        $this->db->where('id', $idcabang);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->branch_name;
        } else {
            return 'Unknown branch';
        }
    }

    public function get_user_by_id($id)
    {
        $this->db->where('branch_id', $id);
        $query = $this->db->get('accounts'); // Menggunakan tabel accounts

        return $query->row(); // Assuming you expect only one row
    }

    public function getUserDetail($idUser) {
        $this->db->select('accounts.*, branch.branch_name');
        $this->db->from('accounts');
        $this->db->join('branch', 'branch.id = accounts.branch_id', 'left'); // Left join karena super_admin dan admin_central tidak punya branch_id
        $this->db->where('accounts.id', $idUser);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
}

