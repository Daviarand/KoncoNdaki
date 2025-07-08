<?php
session_start();
require_once 'config/database.php';

// Keamanan: Memastikan hanya user dengan peran layanan yang bisa mengakses
$allowed_roles = ['layanan', 'porter', 'guide', 'ojek', 'basecamp'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: login.php");
    exit;
}

// Mengambil data sesi yang sudah di-set saat login
$user_id = $_SESSION['user_id'];
$gunung_id = $_SESSION['layanan_gunung_id'] ?? 0;
$nama_gunung = $_SESSION['nama_gunung_layanan'] ?? 'Gunung';

// Query untuk mengambil notifikasi yang sesungguhnya dari database
$stmt = $pdo->prepare(
    "SELECT 
        N.id, 
        N.judul, 
        N.pesan, 
        N.tipe, 
        N.created_at, 
        U_pengirim.first_name AS nama_pengirim
    FROM 
        notifikasi N
    JOIN 
        users U_pengirim ON N.pengirim_id = U_pengirim.id
    WHERE 
        N.penerima_id = :penerima_id 
        AND N.gunung_id = :gunung_id
    ORDER BY 
        N.created_at DESC"
);
$stmt->execute(['penerima_id' => $user_id, 'gunung_id' => $gunung_id]);
$notifikasi = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menghitung jumlah notifikasi asli
$total_notif = count($notifikasi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Layanan - <?php echo htmlspecialchars($nama_gunung); ?></title>
    <meta name="description" content="Dashboard untuk pengelola layanan ojek, porter, guide, dan basecamp di KoncoNdaki.">
    <link rel="stylesheet" href="styles/dashboard-layanan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <h1><i class="fas fa-mountain"></i> Dashboard Layanan <?php echo htmlspecialchars($nama_gunung); ?></h1>
                <p>Kelola pesanan dan notifikasi untuk layanan Anda di area <?php echo htmlspecialchars($nama_gunung); ?></p>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            <div class="dashboard-layout">
                <aside class="dashboard-sidebar">
                    <div class="sidebar-header">
                        <h3><i class="fas fa-cogs"></i> Menu Layanan</h3>
                        <div class="admin-info">
                            <span class="admin-name">Layanan: <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></span>
                            <div class="admin-actions">
                                <span class="notification-icon"><i class="fas fa-bell"></i></span>
                                <a href="auth/logout.php" title="Logout" class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-section">
                        <h4>Kategori Notifikasi</h4>
                        <ul class="category-list">
                            <li class="category-item active" data-category="all">
                                <i class="fas fa-list"></i>
                                <span>Semua Notifikasi</span>
                                <span class="count"><?php echo $total_notif; ?></span>
                            </li>
                            </ul>
                    </div>
                </aside>

                <div class="dashboard-content">
                    <div class="content-header">
                        <div class="header-left">
                            <h2 id="currentCategory">Semua Notifikasi</h2>
                            <p id="categoryDescription">Menampilkan semua notifikasi pesanan dari pengelola</p>
                        </div>
                        <div class="header-actions">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Cari notifikasi..." id="searchInput">
                            </div>
                        </div>
                    </div>

                    <div class="notifications-grid" id="notificationsGrid">
                        <?php if (empty($notifikasi)): ?>
                            <div class="no-notification">
                                <i class="fas fa-bell-slash"></i>
                                <p>Tidak ada notifikasi untuk Anda saat ini.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifikasi as $notif): ?>
                                <div class="notification-card" data-category="<?php echo strtolower($notif['tipe']); ?>" data-time="<?php echo $notif['created_at']; ?>">
                                    <div class="notification-header">
                                        <span class="notification-type type-<?php echo htmlspecialchars($notif['tipe']); ?>"><?php echo htmlspecialchars($notif['tipe']); ?></span>
                                        <h3 class="notification-title"><?php echo htmlspecialchars($notif['judul']); ?></h3>
                                        <div class="notification-time">
                                            <i class="fas fa-clock"></i>
                                            <?php echo date('d M Y, H:i', strtotime($notif['created_at'])); ?>
                                        </div>
                                    </div>
                                    <div class="notification-body">
                                        <div class="service-icon <?php echo strtolower($_SESSION['role']); ?>">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <p class="notification-description"><?php echo nl2br(htmlspecialchars($notif['pesan'])); ?></p>
                                        <div class="notification-meta">
                                            <div class="notification-location">
                                                <i class="fas fa-user-shield"></i>
                                                Dari: Pengelola <?php echo htmlspecialchars($notif['nama_pengirim']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> KoncoNdaki. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="scripts/dashboard-layanan.js"></script>
</body>
</html>