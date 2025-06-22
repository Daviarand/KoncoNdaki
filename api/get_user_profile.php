<?php
require_once '../config/database.php';

try {
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("
        SELECT id, nama_lengkap, email, no_telp, alamat, jenis_kelamin, tanggal_lahir, role, created_at 
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        throw new Exception('User tidak ditemukan');
    }
    
    // Format tanggal
    if ($user['tanggal_lahir']) {
        $user['tanggal_lahir'] = date('Y-m-d', strtotime($user['tanggal_lahir']));
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 