<?php
session_start();
require_once 'auth/check_auth.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Pemesanan - KoncoNdaki</title>
    <meta name="description" content="Panduan lengkap cara pemesanan tiket pendakian gunung di KoncoNdaki dengan langkah-langkah yang mudah.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/cara-pemesanan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Logo -->
                <div class="logo">
                    <img src="images/logo.png" alt="logo">
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links desktop-nav">
                    <a href="dashboard.php" class="nav-link">Home</a>
                    <a href="info-gunung.php" class="nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link active">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link">Diskusi</a>
                    <a href="tentang.php" class="nav-link">Tentang</a>
                </div>

                <!-- User Profile -->
                <div class="user-profile desktop-nav">
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn">
                        <div class="profile-avatar">
                                <?php if (!empty($_SESSION['profile_picture'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Foto Profil" class="profile-img">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <span class="profile-name" id="profileName">
                                <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                            </span>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </button>

                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar large">
                                    <?php if (!empty($_SESSION['profile_picture'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Foto Profil" class="profile-img">
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="profile-info">
                                    <h4 id="menuProfileName">
                                        <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                                    </h4>
                                    <p id="menuProfileEmail">
                                        <?php echo htmlspecialchars($_SESSION['email']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="profile-menu-items">
                                <a href="profile.php" class="profile-menu-item">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Profile Saya</span>
                                </a>
                                <a href="chatbox.php" class="profile-menu-item">
                                    <i class="fas fa-comment-alt"></i>
                                    <span>KoncoNdaki Assistant</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-ticket-alt"></i>
                                    <span>Tiket Saya</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-history"></i>
                                    <span>Riwayat Pemesanan</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Pengaturan</span>
                                </a>
                                <div class="profile-menu-divider"></div>
                                <a href="#" class="profile-menu-item logout" id="logoutBtn">
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
                            <?php if (!empty($_SESSION['profile_picture'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Foto Profil" class="profile-img">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info">
                            <h4 id="mobileProfileName">
                                <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                            </h4>
                            <p id="mobileProfileEmail">
                                <?php echo htmlspecialchars($_SESSION['email']); ?>
                            </p>
                        </div>
                    </div>

                    <a href="dashboard.php" class="mobile-nav-link">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link active">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>

                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-user-circle"></i>
                            Profile Saya
                        </a>
                        <a href="chatbox.php" class="profile-menu-item">
                            <i class="fas fa-comment-alt"></i>
                            <span>KoncoNdaki Assistant</span>
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-ticket-alt"></i>
                            Tiket Saya
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-history"></i>
                            Riwayat Pemesanan
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-cog"></i>
                            Pengaturan
                        </a>
                        <a href="#" class="mobile-nav-link logout" id="mobileLogoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <h1><i class="fas fa-clipboard-list"></i> Cara Pemesanan</h1>
                <p>Panduan lengkap untuk memesan tiket pendakian gunung dengan mudah dan cepat</p>
            </div>
        </div>
    </section>

    <!-- Booking Steps Section -->
    <section class="booking-steps-section">
        <div class="container">
            <div class="section-header">
                <h2>Langkah-langkah Pemesanan</h2>
                <p>Ikuti 4 langkah mudah untuk memesan tiket pendakian gunung</p>
            </div>
            <div class="steps-container">
                <div class="step-item" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Pilih Gunung</h3>
                        <p>Pilih gunung yang ingin Anda daki dari daftar gunung yang tersedia</p>
                    </div>
                </div>
                <div class="step-item" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Pilih Jalur & Tanggal</h3>
                        <p>Tentukan jalur pendakian dan tanggal yang sesuai dengan rencana Anda</p>
                    </div>
                </div>
                <div class="step-item" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Layanan Tambahan</h3>
                        <p>Pilih layanan tambahan seperti guide, porter, atau penyewaan alat</p>
                    </div>
                </div>
                <div class="step-item" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Pembayaran</h3>
                        <p>Lakukan pembayaran dan dapatkan tiket pendakian Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>Pertanyaan Umum</h2>
                <p>Jawaban untuk pertanyaan yang sering diajukan tentang pemesanan</p>
            </div>

            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>Berapa lama sebelum pendakian saya harus memesan tiket?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Kami merekomendasikan untuk memesan tiket minimal 3-7 hari sebelum tanggal pendakian, terutama untuk weekend dan hari libur nasional karena kuota terbatas.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>Apakah bisa membatalkan atau mengubah jadwal pemesanan?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Ya, Anda dapat membatalkan atau mengubah jadwal pemesanan maksimal 24 jam sebelum tanggal pendakian. Biaya pembatalan sebesar 10% dari total pembayaran.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>Apa saja yang termasuk dalam tiket pendakian?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Tiket pendakian sudah termasuk izin masuk kawasan, asuransi dasar, dan akses ke fasilitas umum seperti toilet dan pos kesehatan di basecamp.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>Bagaimana jika cuaca buruk saat hari pendakian?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Jika terjadi cuaca ekstrem yang membahayakan keselamatan, pendakian akan ditunda dan Anda dapat mengubah jadwal tanpa biaya tambahan atau mendapat refund penuh.</p>
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
                        <li><a href="cara-pemesanan.php" class="nav-link">Pemesanan Tiket</a></li>
                        <li><a href="info-gunung.php" class="nav-link">Info Gunung</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="cara-pemesanan.php" class="nav-link">Cara Pemesanan</a></li>
                        <li><a href="cara-pemesanan.php" class="nav-link">FAQ</a></li>
                        <li><a href="tentang.php" class="nav-link">Kontak</a></li>
                        <li><a href="diskusi.php" class="nav-link">Diskusi</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Tentang</h3>
                    <ul>
                        <li><a href="tentang.php" class="nav-link">Tentang Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 KoncoNdaki. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="scripts/script.js"></script>
    <script src="scripts/cara-pemesanan.js"></script>
</body>

</html>