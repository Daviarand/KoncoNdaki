<?php
require_once 'auth/check_auth.php';
requireRole('layanan');

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Layanan - KoncoNdaki</title>
    <meta name="description" content="Dashboard untuk pengelola layanan ojek, porter, guide, dan basecamp di KoncoNdaki.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/dashboard-layanan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Logo -->
                <div class="logo">
                    <img src="images/logo.png" alt="KoncoNdaki Logo">
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links desktop-nav">
                    <a href="dashboard-layanan.php" class="nav-link active">Dashboard</a>
                    <a href="info-gunung.php" class="nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link">Diskusi</a>
                    <a href="tentang.php" class="nav-link">Tentang</a>
                </div>

                <!-- User Profile -->
                <div class="user-profile desktop-nav">
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="profile-name" id="profileName"><?php echo htmlspecialchars($currentUser['nama']); ?></span>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </button>
                        
                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar large">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="profile-info">
                                    <h4 id="menuProfileName"><?php echo htmlspecialchars($currentUser['nama']); ?></h4>
                                    <p id="menuProfileEmail"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                                </div>
                            </div>
                            <div class="profile-menu-items">
                                <a href="profile.php" class="profile-menu-item">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Profile Saya</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-ticket-alt"></i>
                                    <span>Kelola Layanan</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-history"></i>
                                    <span>Riwayat Layanan</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Pengaturan</span>
                                </a>
                                <div class="profile-menu-divider"></div>
                                <a href="auth/logout.php" class="profile-menu-item logout" id="logoutBtn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Keluar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="mobile-nav" id="mobile-nav">
                <div class="mobile-nav-content">
                    <!-- Mobile Profile Header -->
                    <div class="mobile-profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="profile-info">
                            <h4 id="mobileProfileName"><?php echo htmlspecialchars($currentUser['nama']); ?></h4>
                            <p id="mobileProfileEmail"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                        </div>
                    </div>
                    
                    <a href="dashboard-layanan.php" class="mobile-nav-link active">Dashboard</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>
                    
                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-user-circle"></i>
                            Profile Saya
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-ticket-alt"></i>
                            Kelola Layanan
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-history"></i>
                            Riwayat Layanan
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-cog"></i>
                            Pengaturan
                        </a>
                        <a href="auth/logout.php" class="mobile-nav-link logout" id="mobileLogoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Welcome Banner -->
    <section class="welcome-banner">
        <div class="container">
            <div class="welcome-content">
                <h2>Selamat Datang, <span id="welcomeName"><?php echo htmlspecialchars($currentUser['nama']); ?></span>! 🏔️</h2>
                <p>Dashboard layanan pendakian gunung KoncoNdaki</p>
            </div>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="dashboard-content">
        <div class="container">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>150</h3>
                        <p>Total Pendaki</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>45</h3>
                        <p>Tiket Aktif</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rp 2.5M</h3>
                        <p>Pendapatan Bulan Ini</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3>4.8</h3>
                        <p>Rating Layanan</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Layanan Cepat</h3>
                <div class="actions-grid">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h4>Tambah Layanan</h4>
                        <p>Buat layanan baru untuk pendaki</p>
                        <button class="btn-action">Tambah</button>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h4>Edit Layanan</h4>
                        <p>Perbarui informasi layanan</p>
                        <button class="btn-action">Edit</button>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Laporan</h4>
                        <p>Lihat laporan kinerja layanan</p>
                        <button class="btn-action">Lihat</button>
                    </div>
                    
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h4>Pengaturan</h4>
                        <p>Atur preferensi layanan</p>
                        <button class="btn-action">Atur</button>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="recent-activities">
                <h3>Aktivitas Terbaru</h3>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Pendaki Baru Terdaftar</h4>
                            <p>Ahmad Fauzi mendaftar untuk pendakian Gunung Bromo</p>
                            <span class="activity-time">2 jam yang lalu</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Tiket Dipesan</h4>
                            <p>Tiket pendakian Gunung Merapi untuk 3 orang</p>
                            <span class="activity-time">4 jam yang lalu</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Pendakian Selesai</h4>
                            <p>Tim pendakian Gunung Semeru berhasil mencapai puncak</p>
                            <span class="activity-time">1 hari yang lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-mountain"></i>
                        <span>KoncoNdaki</span>
                    </div>
                    <p>Platform terpercaya untuk pemesanan tiket pendakian gunung di seluruh Pulau Jawa.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="form-pemesanan.php">Pemesanan Tiket</a></li>
                        <li><a href="info-gunung.php">Info Gunung</a></li>
                        <li><a href="cara-pemesanan.php">Panduan Pendakian</a></li>
                        <li><a href="#">Peralatan</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="cara-pemesanan.php">Cara Pemesanan</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Kontak</a></li>
                        <li><a href="diskusi.php">Diskusi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Tentang</h3>
                    <ul>
                        <li><a href="tentang.php">Tentang Kami</a></li>
                        <li><a href="#">Tim</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 KoncoNdaki. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="scripts/script.js"></script>
    <script src="scripts/dashboard-layanan.js"></script>
</body>
</html>
