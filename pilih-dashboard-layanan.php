<?php
session_start();

// Cek keamanan dasar
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['layanan', 'porter', 'guide', 'ojek', 'basecamp'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['pilihan_gunung']) || empty($_SESSION['pilihan_gunung'])) {
    session_destroy();
    header("Location: login.php?error=not_assigned");
    exit();
}

$pilihan_gunung = $_SESSION['pilihan_gunung'];
$first_name = $_SESSION['first_name'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Dashboard Gunung</title>
    <link rel="stylesheet" href="styles/auth-styles.css"> <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .selection-container { max-width: 500px; margin: 4rem auto; background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); text-align: center; }
        .selection-container h2 { font-size: 1.75rem; color: #1f2937; margin-bottom: 1rem; }
        .selection-container p { color: #4b5563; margin-bottom: 2rem; }
        .selection-list { list-style: none; padding: 0; }
        .selection-list li { margin: 1rem 0; }
        .selection-list a { display: block; padding: 1rem; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s ease; }
        .selection-list a:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3); }
    </style>
</head>
<body>
    <div class="selection-container">
        <h2>Pilih Dashboard Layanan</h2>
        <p>Halo, <strong><?php echo htmlspecialchars($first_name); ?></strong>! Anda terdaftar di beberapa gunung. Silakan pilih dashboard yang ingin Anda akses.</p>
        <ul class="selection-list">
            <?php foreach ($pilihan_gunung as $gunung): ?>
                <li>
                    <a href="masuk-dashboard-layanan.php?gunung_id=<?php echo $gunung['id_gunung']; ?>">
                        <i class="fas fa-mountain"></i>
                        Akses Dashboard <?php echo htmlspecialchars($gunung['nama_gunung']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
         <a href="auth/logout.php" style="color: #6b7280; font-size: 0.875rem; margin-top: 2rem; display: inline-block;">Keluar</a>
    </div>
</body>
</html>