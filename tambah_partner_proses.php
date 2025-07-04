<?php
session_start();
require_once 'config/database.php';

// Keamanan: Pastikan yang mengakses adalah pengelola yang sedang login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pengelola_gunung') {
    die('Akses ditolak.');
}

// Hanya proses jika metodenya POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role_partner = trim($_POST['tipe_partner']); // Ini akan menjadi role-nya
    $gunung_id = intval($_SESSION['gunung_id']); // Ambil ID gunung dari session pengelola

    // Validasi
    if (empty($first_name) || empty($email) || empty($phone) || empty($password) || empty($role_partner)) {
        die("Semua field kecuali Nama Belakang wajib diisi.");
    }
    
    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Cek duplikasi email
        $stmt_check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->execute([$email]);
        if ($stmt_check->fetch()) {
            die("Email sudah terdaftar. Gunakan email lain.");
        }

        // Siapkan query INSERT ke tabel USERS
        $stmt = $pdo->prepare(
            "INSERT INTO users (first_name, last_name, email, phone, password, role, gunung_id, created_at) 
             VALUES (:first_name, :last_name, :email, :phone, :password, :role, :gunung_id, NOW())"
        );
        
        // Bind parameter
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role_partner); // Menyimpan 'ojek', 'porter', dll.
        $stmt->bindParam(':gunung_id', $gunung_id);
        
        $stmt->execute();
        
        // Redirect dengan pesan sukses
        echo "<script>
                alert('Partner baru berhasil ditambahkan ke dalam daftar user!');
                window.location.href = 'dashboard-pengelola.php#tambahPartner';
              </script>";
        exit;

    } catch (PDOException $e) {
        die("Gagal menyimpan data partner: " . $e->getMessage());
    }
} else {
    header('Location: dashboard-pengelola.php');
    exit;
}
?>