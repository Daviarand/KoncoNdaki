<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    try {
        // Get form data
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        // Validation
        if (empty($email) || empty($password)) {
            throw new Exception('Email dan password wajib diisi');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format email tidak valid');
        }
        
        // Check user credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception('Email atau password salah');
        }
        
        // Redirect based on role
        switch ($user['role']) {
            case 'pendaki':
                $redirect = 'dashboard.php';
                break;
            case 'layanan':
                $redirect = 'dashboard-layanan.php';
                break;
            case 'admin':
                $redirect = 'dashboard-admin.php';
                break;
            case 'pengelola':
                $redirect = 'dashboard-pengelola.php';
                break;
            default:
                $redirect = 'dashboard.php';
        }
        
        $response['success'] = true;
        $response['message'] = 'Login berhasil!';
        $response['redirect'] = $redirect;
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?> 