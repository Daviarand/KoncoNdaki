<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KoncoNdaki</title>
    <meta name="description" content="Dashboard KoncoNdaki - Platform pemesanan tiket pendakian gunung di Pulau Jawa.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar Guest -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Logo -->
                <div class="logo">
                    <img src="images/logo.png" alt="KoncoNdaki Logo">
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links desktop-nav">
                    <a href="dashboard-guest.php" class="nav-link">Home</a>
                    <a href="info-gunung-guest.php" class="nav-link">Info Gunung</a>
                    <a href="cara-pemesanan-guest.php" class="nav-link">Cara Pemesanan</a>
                    <a href="diskusi-guest.php" class="nav-link">Diskusi</a>
                    <a href="tentang-guest.php" class="nav-link">Tentang</a>
                </div>

                <!-- Masuk/Daftar Button -->
                <div class="auth-buttons desktop-nav">
                    <a href="login.php" class="btn btn-login">Masuk</a>
                    <a href="register.php" class="btn btn-register">Daftar</a>
                </div>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- Welcome Banner -->
    <section class="welcome-banner">
        <div class="container">
            <div class="welcome-content">
                <h2>Selamat Datang di KoncoNdaki! 🏔️</h2>
                <p>Siap untuk petualangan pendakian berikutnya?</p>
            </div>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Jelajahi Keindahan <span class="text-green">Gunung di Jawa</span></h1>
                    <p>Pesan tiket pendakian gunung dengan mudah dan aman. Nikmati petualangan tak terlupakan di puncak-puncak tertinggi Pulau Jawa bersama KoncoNdaki.</p>
                    <div class="hero-buttons">
                        <button class="btn btn-primary btn-large" onclick="location.href='login.php'">
                            <i class="fas fa-ticket-alt"></i>
                            Pesan Tiket Sekarang
                        </button>
                        <button class="btn btn-secondary btn-large" onclick="location.href='info-gunung-guest.php'">
                            <i class="fas fa-info-circle"></i>
                            Lihat Info Gunung
                        </button>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop" alt="Pemandangan Gunung Jawa">
                    <div class="hero-badge">
                        <i class="fas fa-users"></i>
                        <span>10,000+ Pendaki Terpuaskan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2>Kenapa Pilih KoncoNdaki?</h2>
                <p>Kami menyediakan layanan terbaik untuk pengalaman pendakian yang aman dan menyenangkan</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon green">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Pemesanan Mudah</h3>
                    <p>Pesan tiket pendakian hanya dalam beberapa klik. Prosesnya cepat, mudah, dan dapat dilakukan kapan saja.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon blue">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Keamanan Terjamin</h3>
                    <p>Sistem keamanan berlapis dan panduan lengkap untuk memastikan pendakian yang aman dan terkontrol.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon orange">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Lokasi Lengkap</h3>
                    <p>Akses ke berbagai gunung di Jawa dengan informasi lengkap jalur, fasilitas, dan tips pendakian.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Mountains Section -->
    <section class="mountains">
        <div class="container">
            <div class="section-header">
                <h2>Gunung Populer</h2>
                <p>Temukan destinasi pendakian terfavorit di Pulau Jawa</p>
            </div>

            <div class="mountains-grid">
                <div class="mountain-card">
                    <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop" alt="Gunung Bromo">
                    <div class="mountain-info">
                        <h3>Gunung Bromo</h3>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Timur</span>
                        </div>
                        <div class="mountain-footer">
                            <span class="price">Rp 35.000</span>
                            <button class="btn-book" onclick="location.href='login.php'">Pesan Sekarang</button>
                        </div>
                    </div>
                </div>

                <div class="mountain-card">
                    <img src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop" alt="Gunung Merapi">
                    <div class="mountain-info">
                        <h3>Gunung Merapi</h3>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Tengah</span>
                        </div>
                        <div class="mountain-footer">
                            <span class="price">Rp 25.000</span>
                            <button class="btn-book" onclick="location.href='login.php'">Pesan Sekarang</button>
                        </div>
                    </div>
                </div>

                <div class="mountain-card">
                    <img src="https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop" alt="Gunung Semeru">
                    <div class="mountain-info">
                        <h3>Gunung Semeru</h3>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Timur</span>
                        </div>
                        <div class="mountain-footer">
                            <span class="price">Rp 45.000</span>
                            <button class="btn-book" onclick="location.href='login.php'">Pesan Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Siap Memulai Petualangan?</h2>
                <p>Bergabunglah dengan ribuan pendaki lainnya dan rasakan pengalaman tak terlupakan di puncak gunung Jawa</p>
                <button class="btn-cta" onclick="location.href='login.php'">Mulai Petualangan Sekarang</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="cara-pemesanan-guest.php" class="nav-link">Pemesanan Tiket</a></li>
                        <li><a href="info-gunung-guest.php" class="nav-link">Info Gunung</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="cara-pemesanan-guest.php" class="nav-link">Cara Pemesanan</a></li>
                        <li><a href="cara-pemesanan-guest.php" class="nav-link">FAQ</a></li>
                        <li><a href="tentang-guest.php" class="nav-link">Kontak</a></li>
                        <li><a href="diskusi-guest.php" class="nav-link">Diskusi</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Tentang</h3>
                    <ul>
                        <li><a href="tentang-guest.php" class="nav-link">Tentang Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 KoncoNdaki. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- <script src="scripts/script.js"></script>
    <script src="scripts/dashboard-script.js"></script> -->
</body>

</html>