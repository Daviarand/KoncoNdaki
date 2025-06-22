<?php
require_once 'auth/check_auth.php';

$currentUser = null;
$isLoggedIn = false;

if (isLoggedIn()) {
    $currentUser = getCurrentUser();
    $isLoggedIn = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Pemesanan - KoncoNdaki</title>
    <meta name="description" content="Panduan lengkap cara memesan tiket pendakian gunung di KoncoNdaki.">
    <link rel="stylesheet" href="styles/style.css">
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
                    <img src="images/logo.png" alt="KoncoNdaki Logo">
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links desktop-nav">
                    <a href="<?php echo $isLoggedIn ? 'dashboard.php' : 'login.php'; ?>" class="nav-link">Home</a>
                    <a href="info-gunung.php" class="nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link active">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link">Diskusi</a>
                    <a href="tentang.php" class="nav-link">Tentang</a>
                </div>

                <?php if ($isLoggedIn): ?>
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
                                <a href="auth/logout.php" class="profile-menu-item logout" id="logoutBtn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Keluar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <!-- Auth Buttons -->
                <div class="auth-buttons desktop-nav">
                    <a href="login.php" class="btn-login">Masuk</a>
                    <a href="register.php" class="btn-register">Daftar</a>
                </div>
                <?php endif; ?>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="mobile-nav" id="mobile-nav">
                <div class="mobile-nav-content">
                    <?php if ($isLoggedIn): ?>
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
                    <?php endif; ?>
                    
                    <a href="<?php echo $isLoggedIn ? 'dashboard.php' : 'login.php'; ?>" class="mobile-nav-link">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link active">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>
                    
                    <?php if ($isLoggedIn): ?>
                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-user-circle"></i>
                            Profile Saya
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
                        <a href="auth/logout.php" class="mobile-nav-link logout" id="mobileLogoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="mobile-auth-buttons">
                        <a href="login.php" class="mobile-nav-link">Masuk</a>
                        <a href="register.php" class="mobile-nav-link">Daftar</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Cara <span class="text-green">Pemesanan Tiket</span></h1>
                    <p>Panduan lengkap cara memesan tiket pendakian gunung di KoncoNdaki. Proses yang mudah, cepat, dan aman untuk petualangan Anda.</p>
                    <div class="hero-buttons">
                        <button class="btn btn-primary btn-large" onclick="location.href='<?php echo $isLoggedIn ? 'form-pemesanan.php' : 'login.php'; ?>'">
                            <i class="fas fa-ticket-alt"></i>
                            Pesan Tiket Sekarang
                        </button>
                        <button class="btn btn-secondary btn-large" onclick="scrollToSteps()">
                            <i class="fas fa-list-ol"></i>
                            Lihat Langkah-langkah
                        </button>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop" alt="Pendakian Gunung">
                </div>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="steps" id="steps">
        <div class="container">
            <div class="section-header">
                <h2>Langkah-langkah Pemesanan</h2>
                <p>Ikuti langkah-langkah berikut untuk memesan tiket pendakian</p>
            </div>
            
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3>Daftar/Login</h3>
                    <p>Buat akun baru atau login ke akun yang sudah ada untuk memulai proses pemesanan tiket pendakian.</p>
                    <div class="step-actions">
                        <a href="register.php" class="btn-step">Daftar</a>
                        <a href="login.php" class="btn-step">Login</a>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <h3>Pilih Gunung</h3>
                    <p>Pilih gunung yang ingin didaki dari daftar gunung yang tersedia. Lihat informasi detail dan harga tiket.</p>
                    <div class="step-actions">
                        <a href="info-gunung.php" class="btn-step">Lihat Gunung</a>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Pilih Tanggal</h3>
                    <p>Tentukan tanggal pendakian yang diinginkan. Pastikan tanggal tersedia dan sesuai dengan jadwal Anda.</p>
                    <div class="step-actions">
                        <a href="<?php echo $isLoggedIn ? 'form-pemesanan.php' : 'login.php'; ?>" class="btn-step">Pilih Tanggal</a>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Isi Data Pendaki</h3>
                    <p>Lengkapi data diri dan informasi pendaki lainnya yang akan ikut dalam pendakian.</p>
                    <div class="step-actions">
                        <a href="<?php echo $isLoggedIn ? 'form-pemesanan.php' : 'login.php'; ?>" class="btn-step">Isi Data</a>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">5</div>
                    <div class="step-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>Pembayaran</h3>
                    <p>Lakukan pembayaran melalui metode yang tersedia. Pembayaran aman dan terjamin.</p>
                    <div class="step-actions">
                        <a href="<?php echo $isLoggedIn ? 'form-pemesanan.php' : 'login.php'; ?>" class="btn-step">Bayar</a>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">6</div>
                    <div class="step-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>Konfirmasi</h3>
                    <p>Tiket akan dikirim ke email Anda. Simpan tiket dan bawa saat pendakian sebagai bukti pemesanan.</p>
                    <div class="step-actions">
                        <a href="<?php echo $isLoggedIn ? 'form-pemesanan.php' : 'login.php'; ?>" class="btn-step">Lihat Tiket</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Requirements Section -->
    <section class="requirements">
        <div class="container">
            <div class="section-header">
                <h2>Persyaratan Pendakian</h2>
                <p>Pastikan Anda memenuhi persyaratan sebelum melakukan pendakian</p>
            </div>
            
            <div class="requirements-grid">
                <div class="requirement-card">
                    <div class="requirement-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h3>KTP/Identitas</h3>
                    <p>Bawa KTP atau identitas resmi lainnya yang masih berlaku untuk pendaftaran di pos pendakian.</p>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Kondisi Fisik</h3>
                    <p>Pastikan kondisi fisik sehat dan siap untuk melakukan aktivitas pendakian yang melelahkan.</p>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">
                        <i class="fas fa-suitcase"></i>
                    </div>
                    <h3>Peralatan</h3>
                    <p>Siapkan peralatan pendakian yang diperlukan seperti sepatu, jaket, tenda, dan perlengkapan lainnya.</p>
                </div>
                
                <div class="requirement-card">
                    <div class="requirement-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Pendakian Berkelompok</h3>
                    <p>Minimal 2 orang untuk pendakian dan maksimal sesuai kuota yang ditentukan untuk setiap gunung.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <div class="section-header">
                <h2>Pertanyaan Umum</h2>
                <p>Jawaban untuk pertanyaan yang sering diajukan</p>
            </div>
            
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Berapa lama proses pemesanan tiket?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Proses pemesanan tiket dapat diselesaikan dalam waktu 5-10 menit, tergantung kelengkapan data yang diisi.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Apakah bisa membatalkan tiket yang sudah dipesan?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Ya, pembatalan dapat dilakukan maksimal 7 hari sebelum tanggal pendakian dengan biaya administrasi 10%.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Bagaimana jika cuaca buruk pada hari pendakian?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Jika pendakian dibatalkan karena cuaca buruk, tiket dapat digunakan kembali pada tanggal lain yang tersedia.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Apakah ada asuransi untuk pendaki?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Ya, setiap tiket sudah termasuk asuransi dasar. Anda juga dapat menambahkan asuransi tambahan saat pemesanan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Siap Memulai Pendakian?</h2>
                <p>Ikuti langkah-langkah di atas dan mulai petualangan pendakian Anda bersama KoncoNdaki</p>
                <button class="btn-cta" onclick="location.href='<?php echo $isLoggedIn ? 'form-pemesanan.php' : 'login.php'; ?>'">Pesan Tiket Sekarang</button>
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
                        <li><a href="<?php echo $isLoggedIn ? 'form-pemesanan.php' : 'login.php'; ?>">Pemesanan Tiket</a></li>
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
    <script src="scripts/cara-pemesanan.js"></script>
    <script>
        function scrollToSteps() {
            document.getElementById('steps').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }
    </script>
</body>
</html>