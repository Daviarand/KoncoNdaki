<?php
// File: proses_pembayaran.php
session_start();
require_once 'config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$pemesanan_id = intval($_GET['id']);

try {
    // Ubah status menjadi 'pending' untuk diverifikasi oleh pengelola
    $stmt = $pdo->prepare(
        "UPDATE pemesanan SET status_pemesanan = 'pending' WHERE id = ?"
    );
    $stmt->execute([$pemesanan_id]);

    // Arahkan ke halaman konfirmasi sukses
    header('Location: pembayaran-sukses.php');
    exit;

} catch (PDOException $e) {
    die("Terjadi kesalahan saat memproses pembayaran: " . $e->getMessage());
}
?>