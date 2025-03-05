    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang_model extends CI_Model {

    public $table = "cabang";
    public function __construct() {
        parent::__construct();
    }
    public function getAllCabang() {
        // Logika untuk mengambil semua data produk dari tabel
        return $this->db->get('cabang')->result_array();
    }
    public function find_all(){
        return $this->db->get($this->table)->result_array();
    }
    public function getCabangById($id) {
        // Logika untuk mengambil data produk berdasarkan ID dari tabel
        return $this->db->get_where('cabang', array('id' => $id))->row();
    }

    public function insert($data) {
        // Insert data ke tabel 'produk'
        return $this->db->insert($this->table, $data);
    }
    public function updateJumlahTransaksi($nocabang, $totalTransaksi) {
        $this->db->where('nocabang', $nocabang);
        return $this->db->update('cabang', array('jumlahtransaksi' => $totalTransaksi));
    }
}
