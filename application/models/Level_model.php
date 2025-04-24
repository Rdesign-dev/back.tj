<?php
// filepath: c:\laragon\www\back.tj\application\models\Level_model.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Level_model extends CI_Model {

    public function get_all_levels() {
        return $this->db->get('user_levels')->result_array();
    }

    public function get_level_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('user_levels')->row_array();
    }

    public function update_level($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_levels', $data);
        return $this->db->affected_rows();
    }

    public function recalculate_member_level($user_id) {
        // 1. Ambil data user untuk mendapatkan status deleted dan time_deleted
        $this->db->where('id', $user_id);
        $user = $this->db->get('users')->row();

        $total_spending = 0; // Inisialisasi total_spending

        // Periksa apakah user tidak di-delete atau di-delete dan time_deleted-nya ada
        if (!$user->deleted || ($user->deleted && $user->time_deleted !== null)) {
            // 1. Hitung total pengeluaran member dari tabel transaksi
            $this->db->select_sum('amount');
            $this->db->where('user_id', $user_id);
            $this->db->where('transaction_type', 'Teras Japan Payment');

            // Jika user di-delete, hanya hitung transaksi setelah time_deleted
            if ($user->deleted && $user->time_deleted !== null) {
                $this->db->where('created_at >=', $user->time_deleted);
            }

            $query = $this->db->get('transactions');
            $total_spending = $query->row()->amount;
        }


        // 2. Ambil semua level dari tabel user_levels yang diurutkan berdasarkan min_spending
        $this->db->order_by('min_spending', 'ASC');
        $levels = $this->get_all_levels();

        // 3. Tentukan level yang sesuai berdasarkan total pengeluaran
        $new_level_id = 1; // Default ke level terendah (misalnya, level ID 1)
        foreach ($levels as $level) {
            if ($total_spending >= $level['min_spending']) {
                $new_level_id = $level['id'];
            } else {
                // Jika total_spending kurang dari min_spending level saat ini, hentikan loop
                break;
            }
        }

        // 4. Update level_id member di tabel users
        $data = array('level_id' => $new_level_id);
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);

        return $new_level_id; // Mengembalikan ID level yang baru
    }
}