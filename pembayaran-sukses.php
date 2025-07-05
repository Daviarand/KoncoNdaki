<?php
session_start();
// Anda bisa tambahkan logika untuk check_auth jika halaman ini butuh login
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - KoncoNdaki</title>
    <link rel="stylesheet" href="styles/style.css"> <style>
        .success-container {
            text-align: center;
            padding: 80px 20px;
            max-width: 600px;
            margin: 40px auto;
            background-color: #f0fff4;
            border: 1px solid #9ae6b4;
            border-radius: 8px;
        }
        .success-container h1 {
            color: #2f855a;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>✅ Pembayaran Berhasil!</h1>
        <p>Terima kasih! Pemesanan Anda telah kami konfirmasi.</p>
        <p>Anda akan segera dapat melihat detail tiket di dashboard Anda.</p>
        <br>
        <a href="dashboard.php" class="btn-primary">Kembali ke Dashboard</a>
    </div>
</body>
</html>