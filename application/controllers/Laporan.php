<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . 'libraries/fpdf/fpdf.php');
class Laporan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        cek_login();
        $this->load->model('cabang_model','cabang');
        $this->load->model('transaksi_model','transaksi');
        $this->load->library('form_validation');

    }
    public function index() {
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('nocabang', ' Cabang', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            // Mengambil data produk dari model
            $data['title'] = "Laporan Transaksi";
            $data['cabang'] = $this->cabang->find_all();

            // Memuat tampilan daftar produk
            $this->template->load('templates/dashboard', 'laporan/form', $data);
        } else {
            $input = $this->input->post(null, true);
            $tanggal = $input['tanggal'];
            $nocabang = $input['nocabang'];
            $pecah = explode(' - ', $tanggal);
            $mulai = date('Y-m-d', strtotime($pecah[0]));
            $akhir = date('Y-m-d', strtotime(end($pecah)));
    
            $pdf_data = $this->transaksi->generate_pdf_data($nocabang,['mulai' => $mulai,'akhir' => $akhir]);
            $this->generate_pdf($pdf_data);
             
        }
        
    }
    private function generate_pdf($data) {
        $pdf = new FPDF();
        $pdf->AddPage('L');

        // Tambahkan judul
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Laporan Transaksi', 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'PT. Amigos Mulia Indonesia', 0, 1, 'C');    

        // Tambahkan header tabel
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Kode Transaksi', 1);
        $pdf->Cell(50, 10, 'Tanggal Transaksi', 1);
        $pdf->Cell(50, 10, 'Cabang', 1);
        $pdf->Cell(50, 10, 'Nama Member', 1);
        $pdf->Cell(40, 10, 'Kasir', 1);
        $pdf->Cell(40, 10, 'Total', 1);
        // Tambahkan kolom lain sesuai kebutuhan

        $pdf->Ln(); // Pindah ke baris berikutnya

        // Tambahkan data ke tabel
        foreach ($data as $row) {
            $pdf->Cell(50, 10, $row['kodetransaksi'], 1);
            $pdf->Cell(50, 10, $row['tanggaltransaksi'], 1);
            $pdf->Cell(50, 10, $row['namacabang'], 1);
            $pdf->Cell(50, 10, $row['namamember'], 1);
            $pdf->Cell(40, 10, $row['nama'], 1);
            $formatted_total = 'Rp ' . number_format($row['total'], 0, ',', '.');
            $pdf->Cell(40, 10, $formatted_total, 1);
            // Tambahkan kolom lain sesuai kebutuhan
            $pdf->Ln(); // Pindah ke baris berikutnya
        }

        // Output PDF ke browser
        $pdf->Output('laporan_transaksi.pdf', 'I'); // 'D' untuk tampilkan sebagai unduhan
    }
    
}