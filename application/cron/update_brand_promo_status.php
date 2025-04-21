<?php
// Koneksi database
$mysqli = new mysqli(
    'localhost',
    'u3218221_anakmagang',
    'anakmagang10',
    'u3218221_terasjapan'
);

// Cek koneksi
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Ambil tanggal hari ini (format YYYY-MM-DD)
$today = date('Y-m-d');

// Query update status berdasarkan tanggal
$update = "
    UPDATE brand_promo
    SET status = CASE
        WHEN available_from > '$today' THEN 'Coming'
        WHEN available_from <= '$today' AND valid_until >= '$today' THEN 'Available'
        WHEN valid_until < '$today' THEN 'Expired'
        ELSE status
    END
";

// Eksekusi query
if ($mysqli->query($update) === TRUE) {
    echo "Status promo berhasil diperbarui.\n";
} else {
    echo "Gagal memperbarui status: " . $mysqli->error . "\n";
}

// Tutup koneksi
$mysqli->close();
?>
