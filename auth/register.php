<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    try {
        // Get form data
        $nama_lengkap = trim($_POST['nama_lengkap']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $no_telp = trim($_POST['no_telp']);
        $alamat = trim($_POST['alamat']);
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $role = 'pendaki'; // Default role for new registrations
        
        // Validation
        if (empty($nama_lengkap) || empty($email) || empty($password) || empty($no_telp)) {
            throw new Exception('Semua field wajib diisi');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format email tidak valid');
        }
        
        if (strlen($password) < 6) {
            throw new Exception('Password minimal 6 karakter');
        }
        
        if ($password !== $confirm_password) {
            throw new Exception('Konfirmasi password tidak cocok');
        }
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception('Email sudah terdaftar');
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO users (nama_lengkap, email, password, no_telp, alamat, jenis_kelamin, tanggal_lahir, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $nama_lengkap,
            $email,
            $hashed_password,
            $no_telp,
            $alamat,
            $jenis_kelamin,
            $tanggal_lahir,
            $role
        ]);
        
        $user_id = $pdo->lastInsertId();
        
        // Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_nama'] = $nama_lengkap;
        $_SESSION['user_role'] = $role;
        $_SESSION['logged_in'] = true;
        
        $response['success'] = true;
        $response['message'] = 'Registrasi berhasil!';
        $response['redirect'] = 'dashboard.php';
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?> 