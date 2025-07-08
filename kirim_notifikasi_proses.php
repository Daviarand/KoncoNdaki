<?php
session_start();
require_once 'config/database.php';

// Keamanan: Hanya pengelola gunung yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pengelola_gunung' || !isset($_SESSION['gunung_id'])) {
    header('Location: login.php');
    exit;
}

// Pastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard-pengelola.php');
    exit;
}

// Ambil data dari form
$penerima_id = $_POST['partner_id'] ?? null; // Ini adalah ID user dari partner yang dipilih
$tipe_partner = $_POST['tipe_partner'] ?? '';
$pesan = trim($_POST['pesan'] ?? '');

// Ambil data dari sesi pengelola
$pengirim_id = $_SESSION['user_id'];
$gunung_id = $_SESSION['gunung_id'];

// Validasi sederhana
if (empty($penerima_id) || empty($pesan) || empty($tipe_partner)) {
    $_SESSION['error_message'] = "Harap lengkapi semua field untuk mengirim notifikasi.";
    header('Location: dashboard-pengelola.php#kirimNotifikasi');
    exit;
}

// Buat judul notifikasi secara otomatis
$judul_notifikasi = "Tugas Baru untuk Anda (" . ucfirst($tipe_partner) . ")";

try {
    // Masukkan notifikasi ke dalam tabel `notifikasi`
    $sql = "INSERT INTO notifikasi (pengirim_id, penerima_id, gunung_id, tipe, judul, pesan) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$pengirim_id, $penerima_id, $gunung_id, $tipe_partner, $judul_notifikasi, $pesan])) {
        // Jika berhasil, kirim pesan sukses
        $_SESSION['success_message'] = "Notifikasi berhasil dikirim ke partner!";
    } else {
        // Jika gagal
        $_SESSION['error_message'] = "Gagal mengirim notifikasi.";
    }

} catch (PDOException $e) {
    // Tangani error database
    $_SESSION['error_message'] = "Terjadi kesalahan database saat mengirim notifikasi.";
}

// Arahkan kembali ke halaman dashboard pengelola, ke tab kirim notifikasi
header('Location: dashboard-pengelola.php#kirimNotifikasi');
exit;
?>