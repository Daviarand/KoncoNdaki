<?php
// File: update_status.php
session_start();
require_once 'config/database.php';

// Keamanan: Pastikan yang mengakses adalah pengelola yang sedang login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pengelola_gunung') {
    die('Akses ditolak.');
}

// Hanya proses jika metodenya POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pemesanan_id = intval($_POST['pemesanan_id']);
    $new_status = $_POST['new_status'];
    $gunung_id_pengelola = $_SESSION['gunung_id'];

    // Daftar status yang diizinkan untuk diubah
    $allowed_statuses = ['in progress', 'complete', 'rejected'];
    if (!in_array($new_status, $allowed_statuses)) {
        die('Status tidak valid.');
    }

    try {
        // Keamanan Tambahan:
        // Cek apakah pemesanan ini benar-benar milik gunung yang dikelola oleh admin ini.
        // Ini mencegah admin Bromo mengubah pesanan Semeru jika mereka tahu ID-nya.
        $stmt_check = $pdo->prepare("SELECT tiket_id FROM pemesanan WHERE id = ?");
        $stmt_check->execute([$pemesanan_id]);
        $booking = $stmt_check->fetch();

        if ($booking && $booking['tiket_id'] == $gunung_id_pengelola) {
            // Jika valid, lanjutkan update
            $stmt_update = $pdo->prepare("UPDATE pemesanan SET status_pemesanan = ? WHERE id = ?");
            $stmt_update->execute([$new_status, $pemesanan_id]);

            // Jika status diubah menjadi 'in progress', simulasikan pengiriman notifikasi
            if ($new_status === 'in progress') {
                // Di dunia nyata, di sinilah Anda akan memanggil API, mengirim email, atau SMS
                // ke partner (guide, porter, dll.)
                // Untuk sekarang, kita bisa buat log atau catatan sederhana.
                // file_put_contents('notifikasi_partner.log', "Notifikasi terkirim untuk pesanan ID: $pemesanan_id pada " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            }

            // Redirect kembali ke dashboard pengelola
            header('Location: dashboard-pengelola.php#kelolaBooking');
            exit;
        } else {
            die('Anda tidak memiliki izin untuk mengubah pesanan ini.');
        }
    } catch (PDOException $e) {
        die("Gagal memperbarui status: " . $e->getMessage());
    }
} else {
    // Jika diakses langsung, kembalikan ke dashboard
    header('Location: dashboard-pengelola.php');
    exit;
}
?>