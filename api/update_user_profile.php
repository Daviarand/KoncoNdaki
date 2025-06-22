<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    try {
        $user_id = $_SESSION['user_id'];
        
        // Get form data
        $nama_lengkap = trim($_POST['nama_lengkap']);
        $no_telp = trim($_POST['no_telp']);
        $alamat = trim($_POST['alamat']);
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        
        // Validation
        if (empty($nama_lengkap) || empty($no_telp)) {
            throw new Exception('Nama lengkap dan nomor telepon wajib diisi');
        }
        
        // Update user data
        $stmt = $pdo->prepare("
            UPDATE users 
            SET nama_lengkap = ?, no_telp = ?, alamat = ?, jenis_kelamin = ?, tanggal_lahir = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        $stmt->execute([
            $nama_lengkap,
            $no_telp,
            $alamat,
            $jenis_kelamin,
            $tanggal_lahir,
            $user_id
        ]);
        
        // Update session
        $_SESSION['user_nama'] = $nama_lengkap;
        
        $response['success'] = true;
        $response['message'] = 'Profil berhasil diperbarui!';
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?> 