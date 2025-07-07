<?php
session_start();
require_once 'auth/check_auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - KoncoNdaki</title>
    <meta name="description" content="Tentang KoncoNdaki - Platform terpercaya untuk pemesanan tiket pendakian gunung di Pulau Jawa.">
    <meta name="keywords" content="KoncoNdaki, pendakian gunung, tiket gunung, wisata alam, Pulau Jawa">
    <meta name="author" content="KoncoNdaki Team">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Tentang Kami - KoncoNdaki">
    <meta property="og:description" content="Platform terpercaya untuk pemesanan tiket pendakian gunung di Pulau Jawa">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/tentang.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">
    <link rel="preload" href="styles/tentang.css" as="style">
</head>
<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only sr-only-focusable">Skip to main content</a>
    
    <!-- Navbar -->
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Logo -->
                <div class="logo">
                    <img src="images/logo.png" alt="KoncoNdaki Logo" width="120" height="40">
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links desktop-nav" role="menubar">
                    <a href="dashboard.php" class="nav-link" role="menuitem">Home</a>
                    <a href="info-gunung.php" class="nav-link" role="menuitem">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link" role="menuitem">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link" role="menuitem">Diskusi</a>
                    <a href="tentang.php" class="nav-link active" role="menuitem" aria-current="page">Tentang</a>
                </div>

                <!-- User Profile -->
                <div class="user-profile desktop-nav">
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn" aria-expanded="false" aria-haspopup="true">
                            <div class="profile-avatar">
                                <i class="fas fa-user" aria-hidden="true"></i>
                            </div>
                            <span class="profile-name" id="profileName">
                                <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                            </span>
                            <i class="fas fa-chevron-down profile-arrow" aria-hidden="true"></i>
                        </button>

                        <div class="profile-menu" id="profileMenu" role="menu" aria-labelledby="profileBtn">
                            <div class="profile-header">
                                <div class="profile-avatar large">
                                    <i class="fas fa-user" aria-hidden="true"></i>
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
                                <a href="profile.php" class="profile-menu-item" role="menuitem">
                                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                                    <span>Profile Saya</span>
                                </a>
                                <a href="chatbox.php" class="profile-menu-item" role="menuitem">
                                    <i class="fas fa-comment-alt" aria-hidden="true"></i>
                                    <span>KoncoNdaki Assistant</span>
                                </a>
                                <a href="#" class="profile-menu-item" role="menuitem">
                                    <i class="fas fa-ticket-alt" aria-hidden="true"></i>
                                    <span>Tiket Saya</span>
                                </a>
                                <a href="#" class="profile-menu-item" role="menuitem">
                                    <i class="fas fa-history" aria-hidden="true"></i>
                                    <span>Riwayat Pemesanan</span>
                                </a>
                                <a href="#" class="profile-menu-item" role="menuitem">
                                    <i class="fas fa-cog" aria-hidden="true"></i>
                                    <span>Pengaturan</span>
                                </a>
                                <div class="profile-menu-divider" role="separator"></div>
                                <a href="#" class="profile-menu-item logout" id="logoutBtn" role="menuitem">
                                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                    <span>Keluar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <button class="mobile-menu-btn" aria-expanded="false" aria-controls="mobile-nav" aria-label="Toggle mobile menu">
                    <i class="fas fa-bars" id="menu-icon" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div class="mobile-nav" id="mobile-nav" role="menu">
                <div class="mobile-nav-content">
                    <!-- Mobile Profile Header -->
                    <div class="mobile-profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user" aria-hidden="true"></i>
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
                    
                    <a href="dashboard.php" class="mobile-nav-link" role="menuitem">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link" role="menuitem">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link" role="menuitem">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link" role="menuitem">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link active" role="menuitem" aria-current="page">Tentang</a>
                    
                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link" role="menuitem">
                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                            Profile Saya
                        </a>
                        <a href="chatbox.php" class="mobile-nav-link" role="menuitem">
                            <i class="fas fa-comment-alt" aria-hidden="true"></i>
                            KoncoNdaki Assistant
                        </a>
                        <a href="#" class="mobile-nav-link" role="menuitem">
                            <i class="fas fa-ticket-alt" aria-hidden="true"></i>
                            Tiket Saya
                        </a>
                        <a href="#" class="mobile-nav-link" role="menuitem">
                            <i class="fas fa-history" aria-hidden="true"></i>
                            Riwayat Pemesanan
                        </a>
                        <a href="#" class="mobile-nav-link" role="menuitem">
                            <i class="fas fa-cog" aria-hidden="true"></i>
                            Pengaturan
                        </a>
                        <a href="#" class="mobile-nav-link logout" id="mobileLogoutBtn" role="menuitem">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Hero Section -->
        <section class="about-hero" aria-labelledby="hero-title">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 id="hero-title">Tentang <span class="text-green">KoncoNdaki</span></h1>
                        <p>Platform terpercaya yang menghubungkan para pecinta alam dengan keindahan gunung-gunung di Pulau Jawa. Kami berkomitmen untuk memberikan pengalaman pendakian yang aman, mudah, dan tak terlupakan.</p>
                        <div class="hero-stats" role="region" aria-label="Statistik KoncoNdaki">
                            <div class="stat-item">
                                <h3>10,000+</h3>
                                <p>Pendaki Terpuaskan</p>
                            </div>
                            <div class="stat-item">
                                <h3>25+</h3>
                                <p>Gunung Tersedia</p>
                            </div>
                            <div class="stat-item">
                                <h3>5 Tahun</h3>
                                <p>Pengalaman</p>
                            </div>
                        </div>
                    </div>
                    <div class="hero-image">
                        <img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop" 
                             alt="Tim KoncoNdaki sedang mendaki gunung" 
                             width="600" 
                             height="400"
                             loading="lazy">
                        <div class="hero-badge">
                            <i class="fas fa-award" aria-hidden="true"></i>
                            <span>Platform Terpercaya #1</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission & Vision Section -->
        <section class="mission-vision" aria-labelledby="mission-vision-title">
            <div class="container">
                <div class="mv-grid">
                    <article class="mv-card">
                        <div class="mv-icon">
                            <i class="fas fa-bullseye" aria-hidden="true"></i>
                        </div>
                        <h3>Misi Kami</h3>
                        <p>Menyediakan platform yang mudah, aman, dan terpercaya untuk pemesanan tiket pendakian gunung, serta membangun komunitas pecinta alam yang bertanggung jawab terhadap kelestarian lingkungan.</p>
                    </article>

                    <article class="mv-card">
                        <div class="mv-icon">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </div>
                        <h3>Visi Kami</h3>
                        <p>Menjadi platform nomor satu di Indonesia untuk wisata pendakian gunung yang mengedepankan keselamatan, kenyamanan, dan pelestarian alam untuk generasi mendatang.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="values-section" aria-labelledby="values-title">
            <div class="container">
                <div class="section-header">
                    <h2 id="values-title">Nilai-Nilai Kami</h2>
                    <p>Prinsip yang menjadi fondasi dalam setiap layanan yang kami berikan</p>
                </div>

                <div class="values-grid">
                    <article class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-shield-alt" aria-hidden="true"></i>
                        </div>
                        <h3>Keselamatan</h3>
                        <p>Keselamatan pendaki adalah prioritas utama kami. Setiap jalur dan layanan telah melalui standar keamanan yang ketat.</p>
                    </article>

                    <article class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-leaf" aria-hidden="true"></i>
                        </div>
                        <h3>Kelestarian Alam</h3>
                        <p>Kami berkomitmen untuk menjaga kelestarian alam dan mengajak setiap pendaki untuk menjadi guardian of nature.</p>
                    </article>

                    <article class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-handshake" aria-hidden="true"></i>
                        </div>
                        <h3>Kepercayaan</h3>
                        <p>Membangun kepercayaan melalui transparansi, pelayanan yang konsisten, dan komitmen terhadap kepuasan pelanggan.</p>
                    </article>

                    <article class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-users" aria-hidden="true"></i>
                        </div>
                        <h3>Komunitas</h3>
                        <p>Membangun komunitas pecinta alam yang solid, saling mendukung, dan berbagi pengalaman pendakian.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Story Section -->
        <section class="story-section" aria-labelledby="story-title">
            <div class="container">
                <div class="story-content">
                    <div class="story-text">
                        <h2 id="story-title">Cerita Kami</h2>
                        <p>KoncoNdaki lahir dari kecintaan terhadap alam dan keinginan untuk memudahkan para pecinta gunung dalam merencanakan petualangan mereka. Dimulai pada tahun 2025 oleh sekelompok pendaki berpengalaman yang merasakan kesulitan dalam proses pemesanan tiket pendakian.</p>
                        
                        <p>Kami memahami bahwa setiap pendaki memiliki kebutuhan yang berbeda - ada yang mencari tantangan ekstrem, ada yang ingin menikmati keindahan alam dengan santai, dan ada yang baru memulai perjalanan pendakian mereka. Itulah mengapa kami menciptakan platform yang dapat mengakomodasi semua kebutuhan tersebut.</p>
                        
                        <p>Dengan dukungan teknologi modern dan jaringan mitra lokal yang luas, kami terus berinovasi untuk memberikan pengalaman terbaik bagi setiap pendaki. Dari pemesanan tiket yang mudah hingga layanan tambahan yang komprehensif, semua dirancang untuk membuat petualangan Anda menjadi tak terlupakan.</p>
                    </div>
                    <div class="story-image">
                        <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop" 
                             alt="Perjalanan dan sejarah KoncoNdaki" 
                             width="600" 
                             height="400"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="team-section" aria-labelledby="team-title">
            <div class="container">
                <div class="section-header">
                    <h2 id="team-title">Tim Kami</h2>
                    <p>Bertemu dengan orang-orang hebat di balik KoncoNdaki</p>
                </div>

                <div class="team-grid">
                    <article class="team-card">
                        <div class="team-avatar">
                            <i class="fas fa-user" aria-hidden="true"></i>
                        </div>
                        <h3>Daviar Andrianoe Arhaburizky</h3>
                        <p class="team-nim">23523193</p>
                        <p class="team-bio">Pendaki berpengalaman dengan 15+ tahun menjelajahi gunung-gunung di Indonesia. Visinya adalah membuat pendakian gunung lebih accessible untuk semua orang.</p>
                        <div class="team-social">
                            <a href="#" aria-label="LinkedIn Daviar Andrianoe Arhaburizky"><i class="fab fa-linkedin" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Instagram Daviar Andrianoe Arhaburizky"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                        </div>
                    </article>

                    <article class="team-card">
                        <div class="team-avatar">
                            <i class="fas fa-user" aria-hidden="true"></i>
                        </div>
                        <h3>Siti Khotijah</h3>
                        <p class="team-nim">23523203</p>
                        <p class="team-bio">Ahli dalam manajemen operasional dan keselamatan pendakian. Memastikan setiap aspek perjalanan berjalan dengan lancar dan aman.</p>
                        <div class="team-social">
                            <a href="#" aria-label="LinkedIn Siti Khotijah"><i class="fab fa-linkedin" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Instagram Siti Khotijah"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                        </div>
                    </article>

                    <article class="team-card">
                        <div class="team-avatar">
                            <i class="fas fa-user" aria-hidden="true"></i>
                        </div>
                        <h3>Agil Seno Adjie</h3>
                        <p class="team-nim">23253250</p>
                        <p class="team-bio">Teknologi enthusiast yang mengubah ide-ide inovatif menjadi solusi digital yang user-friendly dan reliable.</p>
                        <div class="team-social">
                            <a href="#" aria-label="LinkedIn Agil Seno Adjie"><i class="fab fa-linkedin" aria-hidden="true"></i></a>
                            <a href="#" aria-label="GitHub Agil Seno Adjie"><i class="fab fa-github" aria-hidden="true"></i></a>
                        </div>
                    </article>

                    <article class="team-card">
                        <div class="team-avatar">
                            <i class="fas fa-user" aria-hidden="true"></i>
                        </div>
                        <h3>Satrio Leikanov Habibi</h3>
                        <p class="team-nim">23523078</p>
                        <p class="team-bio">Membangun dan memelihara komunitas KoncoNdaki. Passionate dalam menghubungkan para pendaki dan berbagi pengalaman.</p>
                        <div class="team-social">
                            <a href="#" aria-label="LinkedIn Satrio Leikanov Habibi"><i class="fab fa-linkedin" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Instagram Satrio Leikanov Habibi"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- Achievements Section -->
        <section class="achievements-section" aria-labelledby="achievements-title">
            <div class="container">
                <div class="section-header">
                    <h2 id="achievements-title">Pencapaian Kami</h2>
                    <p>Prestasi yang telah kami raih dalam perjalanan membangun KoncoNdaki</p>
                </div>

                <div class="achievements-grid">
                    <article class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-trophy" aria-hidden="true"></i>
                        </div>
                        <h3>Best Tourism Platform 2023</h3>
                        <p>Penghargaan dari Indonesia Tourism Awards untuk kategori platform wisata terbaik</p>
                    </article>

                    <article class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-certificate" aria-hidden="true"></i>
                        </div>
                        <h3>ISO 27001 Certified</h3>
                        <p>Sertifikasi keamanan informasi internasional untuk melindungi data pengguna</p>
                    </article>

                    <article class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-star" aria-hidden="true"></i>
                        </div>
                        <h3>4.9/5 User Rating</h3>
                        <p>Rating tinggi dari lebih dari 10,000 pengguna yang puas dengan layanan kami</p>
                    </article>

                    <article class="achievement-card">
                        <div class="achievement-icon">
                            <i class="fas fa-seedling" aria-hidden="true"></i>
                        </div>
                        <h3>Green Tourism Partner</h3>
                        <p>Kemitraan dengan Kementerian Lingkungan Hidup untuk program wisata berkelanjutan</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section" aria-labelledby="contact-title">
            <div class="container">
                <div class="contact-content">
                    <div class="contact-info">
                        <h2 id="contact-title">Hubungi Kami</h2>
                        <p>Punya pertanyaan atau butuh bantuan? Tim kami siap membantu Anda 24/7</p>

                        <div class="contact-methods">
                            <div class="contact-method">
                                <div class="contact-icon">
                                    <i class="fas fa-phone" aria-hidden="true"></i>
                                </div>
                                <div class="contact-details">
                                    <h4>WhatsApp</h4>
                                    <p>+62 812-3456-7890</p>
                                    <span>24/7 Customer Support</span>
                                </div>
                            </div>

                            <div class="contact-method">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                </div>
                                <div class="contact-details">
                                    <h4>Email</h4>
                                    <p>support@konco-ndaki.com</p>
                                </div>
                            </div>

                            <div class="contact-method">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                </div>
                                <div class="contact-details">
                                    <h4>Alamat</h4>
                                    <p>Jl. Kaliurang KM. 14,5<br>Sleman, Daerah Istimewa Yogyakarta</p>
                                    <span>Kantor Pusat</span>
                                </div>
                            </div>
                        </div>

                        <div class="social-links">
                            <h4>Ikuti Kami</h4>
                            <div class="social-icons">
                                <a href="#" class="social-icon" aria-label="Facebook KoncoNdaki">
                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="social-icon" aria-label="Instagram KoncoNdaki">
                                    <i class="fab fa-instagram" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="social-icon" aria-label="Twitter KoncoNdaki">
                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="social-icon" aria-label="YouTube KoncoNdaki">
                                    <i class="fab fa-youtube" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="social-icon" aria-label="LinkedIn KoncoNdaki">
                                    <i class="fab fa-linkedin" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form">
                        <h3>Kirim Pesan</h3>
                        <form id="contactForm" novalidate>
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span aria-label="required">*</span></label>
                                <input type="text" id="name" name="name" required aria-describedby="name-error">
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span aria-label="required">*</span></label>
                                <input type="email" id="email" name="email" required aria-describedby="email-error">
                            </div>

                            <div class="form-group">
                                <label for="subject">Subjek <span aria-label="required">*</span></label>
                                <select id="subject" name="subject" required aria-describedby="subject-error">
                                    <option value="">Pilih subjek</option>
                                    <option value="general">Pertanyaan Umum</option>
                                    <option value="booking">Bantuan Pemesanan</option>
                                    <option value="technical">Masalah Teknis</option>
                                    <option value="partnership">Kemitraan</option>
                                    <option value="feedback">Saran & Masukan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Pesan <span aria-label="required">*</span></label>
                                <textarea id="message" name="message" rows="5" required placeholder="Tulis pesan Anda di sini..." aria-describedby="message-error"></textarea>
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-mountain" aria-hidden="true"></i>
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

    <!-- Scripts -->
    <script src="scripts/script.js"></script>
    <script src="scripts/tentang.js"></script>
</body>
</html>
