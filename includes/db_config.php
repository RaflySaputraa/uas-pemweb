<?php
// Pengaturan koneksi database
// =========================================================================================
// PASTIKAN NILAI-NILAI INI SESUAI DENGAN PENGATURAN DATABASE ANDA DI LARAGON/XAMPP
// =========================================================================================
$servername = "localhost"; // Nama host database Anda. Umumnya 'localhost' jika di server lokal.
$username = "root";      // Username database Anda. Default Laragon/XAMPP adalah 'root'.
$password = "";          // Password database Anda. Default Laragon/XAMPP seringkali KOSONG.
$dbname = "db_kota";     // Nama database yang telah kita buat (misalnya 'db_kota').

// Membuat koneksi baru ke database menggunakan MySQLi (Improved MySQL Extension)
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi. Jika ada kesalahan, hentikan eksekusi script dan tampilkan pesan kesalahan.
if ($conn->connect_error) {
    // die() akan menghentikan eksekusi script dan menampilkan pesan error.
    // __FILE__ dan __LINE__ adalah konstanta magis PHP yang menunjukkan nama file dan nomor baris saat ini.
    // Ini membantu dalam debugging untuk mengetahui di mana error terjadi.
    die("Koneksi gagal: " . $conn->connect_error . " in " . __FILE__ . " on line " . __LINE__);
}

// Opsional: Mengatur charset koneksi ke UTF-8 untuk mendukung berbagai karakter
// Ini penting untuk menghindari masalah tampilan karakter khusus.
$conn->set_charset("utf8");

// Catatan Keamanan Penting untuk Lingkungan Produksi (Live Server):
// 1. Jangan menampilkan pesan error database secara langsung kepada pengguna akhir seperti ini.
//    Sebaiknya log error ke file atau sistem logging dan tampilkan pesan umum.
// 2. Gunakan kredensial database yang lebih kuat (username dan password yang kompleks).
// 3. Pertimbangkan untuk membatasi akses database hanya dari IP server aplikasi.
// 4. Pastikan file ini memiliki izin akses yang aman di server Anda (biasanya 644 atau 640).
?>