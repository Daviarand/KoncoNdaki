<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Gunung - KoncoNdaki</title>
    <meta name="description"
        content="Informasi lengkap tentang gunung-gunung di Pulau Jawa untuk pendakian yang aman dan menyenangkan.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/info-gunung.css">
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
                    <a href="info-gunung-guest.php" class="nav-link active">Info Gunung</a>
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

            <!-- Mobile Navigation -->
            <div class="mobile-nav" id="mobile-nav">
                <div class="mobile-nav-content">
                    <!-- Mobile Profile Header -->
                    <div class="mobile-profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
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
                    <a href="info-gunung.php" class="mobile-nav-link active">Info Gunung</a>
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
                <h1><i class="fas fa-mountain"></i> Info Gunung</h1>
                <p>Temukan informasi lengkap tentang gunung-gunung di Pulau Jawa</p>
            </div>
        </div>
    </section>

    <!-- Booking Form Section (hidden by default) -->
    <section class="booking-form-section" id="bookingFormSection" style="display:none;">
        <div class="container">
            <div class="booking-layout">
                <!-- Booking Form -->
                <div class="booking-form-container">
                    <div class="form-header">
                        <h2>Mulai Pemesanan</h2>
                        <p>Isi form di bawah untuk memulai proses pemesanan tiket</p>
                    </div>
                    <form id="bookingForm" class="booking-form">
                        <!-- Step 1: Pilih Gunung -->
                        <div class="form-step active" id="step-1">
                            <div class="step-header">
                                <h3><i class="fas fa-mountain"></i> Pilih Gunung</h3>
                                <p>Pilih gunung yang ingin Anda daki</p>
                            </div>
                            <div class="mountain-selection">
                                <div class="mountain-option" data-mountain="bromo">
                                    <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Bromo">
                                    <div class="mountain-info">
                                        <h4>Gunung Bromo</h4>
                                        <p>Jawa Timur • 2.329 mdpl</p>
                                        <span class="price">Rp 35.000</span>
                                    </div>
                                </div>
                                <div class="mountain-option" data-mountain="merapi">
                                    <img src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Merapi">
                                    <div class="mountain-info">
                                        <h4>Gunung Merapi</h4>
                                        <p>Jawa Tengah • 2.930 mdpl</p>
                                        <span class="price">Rp 25.000</span>
                                    </div>
                                </div>
                                <div class="mountain-option" data-mountain="semeru">
                                    <img src="https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Semeru">
                                    <div class="mountain-info">
                                        <h4>Gunung Semeru</h4>
                                        <p>Jawa Timur • 3.676 mdpl</p>
                                        <span class="price">Rp 45.000</span>
                                    </div>
                                </div>
                                <div class="mountain-option" data-mountain="gede">
                                    <img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Gede">
                                    <div class="mountain-info">
                                        <h4>Gunung Gede</h4>
                                        <p>Jawa Barat • 2.958 mdpl</p>
                                        <span class="price">Rp 30.000</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-next" id="nextStep1" disabled>
                                    Lanjutkan <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Step 2: Pilih Jalur & Tanggal -->
                        <div class="form-step" id="step-2">
                            <div class="step-header">
                                <h3><i class="fas fa-route"></i> Pilih Jalur & Tanggal</h3>
                                <p>Tentukan jalur pendakian dan tanggal keberangkatan</p>
                            </div>
                            <div class="route-selection" id="routeSelection">
                                <!-- Routes will be populated dynamically -->
                            </div>
                            <div class="date-selection">
                                <div class="form-group">
                                    <label for="hikingDate">Tanggal Pendakian</label>
                                    <input type="date" id="hikingDate" name="hikingDate" required>
                                </div>
                                <div class="form-group">
                                    <label for="participants">Jumlah Pendaki</label>
                                    <select id="participants" name="participants" required>
                                        <option value="">Pilih jumlah pendaki</option>
                                        <option value="1">1 Orang</option>
                                        <option value="2">2 Orang</option>
                                        <option value="3">3 Orang</option>
                                        <option value="4">4 Orang</option>
                                        <option value="5">5 Orang</option>
                                        <option value="6+">6+ Orang (Grup)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-back" id="backStep2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn-next" id="nextStep2" disabled>
                                    Lanjutkan <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Step 3: Layanan Tambahan -->
                        <div class="form-step" id="step-3">
                            <div class="step-header">
                                <h3><i class="fas fa-plus-circle"></i> Layanan Tambahan</h3>
                                <p>Pilih layanan tambahan untuk kenyamanan pendakian Anda</p>
                            </div>
                            <div class="services-grid">
                                <div class="service-card" data-service="guide">
                                    <div class="service-icon">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="service-info">
                                        <h4>Jasa Guide</h4>
                                        <p>Pemandu berpengalaman untuk memandu perjalanan Anda</p>
                                        <span class="service-price">Rp 150.000/hari</span>
                                    </div>
                                    <div class="service-toggle">
                                        <input type="checkbox" id="guide" name="services[]" value="guide">
                                        <label for="guide" class="toggle-switch"></label>
                                    </div>
                                </div>
                                <div class="service-card" data-service="porter">
                                    <div class="service-icon">
                                        <i class="fas fa-hiking"></i>
                                    </div>
                                    <div class="service-info">
                                        <h4>Jasa Porter</h4>
                                        <p>Bantuan membawa barang bawaan hingga 15kg</p>
                                        <span class="service-price">Rp 100.000/hari</span>
                                    </div>
                                    <div class="service-toggle">
                                        <input type="checkbox" id="porter" name="services[]" value="porter">
                                        <label for="porter" class="toggle-switch"></label>
                                    </div>
                                </div>
                                <div class="service-card" data-service="ojek">
                                    <div class="service-icon">
                                        <i class="fas fa-motorcycle"></i>
                                    </div>
                                    <div class="service-info">
                                        <h4>Jasa Ojek</h4>
                                        <p>Transportasi dari basecamp ke pos pendakian</p>
                                        <span class="service-price">Rp 50.000/orang</span>
                                    </div>
                                    <div class="service-toggle">
                                        <input type="checkbox" id="ojek" name="services[]" value="ojek">
                                        <label for="ojek" class="toggle-switch"></label>
                                    </div>
                                </div>
                                <div class="service-card" data-service="basecamp">
                                    <div class="service-icon">
                                        <i class="fas fa-campground"></i>
                                    </div>
                                    <div class="service-info">
                                        <h4>Sewa Basecamp</h4>
                                        <p>Tempat istirahat sebelum dan sesudah pendakian</p>
                                        <span class="service-price">Rp 75.000/malam</span>
                                    </div>
                                    <div class="service-toggle">
                                        <input type="checkbox" id="basecamp" name="services[]" value="basecamp">
                                        <label for="basecamp" class="toggle-switch"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-back" id="backStep3">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn-next" id="nextStep3">
                                    Lanjutkan <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Step 4: Konfirmasi & Pembayaran -->
                        <div class="form-step" id="step-4">
                            <div class="step-header">
                                <h3><i class="fas fa-credit-card"></i> Konfirmasi & Pembayaran</h3>
                                <p>Periksa detail pemesanan dan lakukan pembayaran</p>
                            </div>
                            <div class="booking-summary" id="bookingSummary">
                                <!-- Summary will be populated dynamically -->
                            </div>
                            <div class="payment-methods">
                                <h4>Metode Pembayaran</h4>
                                <div class="payment-options">
                                    <div class="payment-option">
                                        <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" checked>
                                        <label for="bank_transfer">
                                            <i class="fas fa-university"></i>
                                            Transfer Bank
                                        </label>
                                    </div>
                                    <div class="payment-option">
                                        <input type="radio" id="e_wallet" name="payment_method" value="e_wallet">
                                        <label for="e_wallet">
                                            <i class="fas fa-mobile-alt"></i>
                                            E-Wallet
                                        </label>
                                    </div>
                                    <div class="payment-option">
                                        <input type="radio" id="credit_card" name="payment_method" value="credit_card">
                                        <label for="credit_card">
                                            <i class="fas fa-credit-card"></i>
                                            Kartu Kredit
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="terms-agreement">
                                <label class="checkbox-container">
                                    <input type="checkbox" id="agreeTerms" required>
                                    <span class="checkmark"></span>
                                    Saya setuju dengan <a href="#" target="_blank">syarat dan ketentuan</a> yang berlaku
                                </label>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-back" id="backStep4">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="submit" class="btn-submit" id="submitBooking">
                                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Booking Summary Sidebar -->
                <div class="booking-sidebar">
                    <div class="sidebar-card">
                        <h3>Ringkasan Pemesanan</h3>
                        <div class="summary-content" id="sidebarSummary">
                            <div class="summary-placeholder">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Pilih gunung untuk melihat ringkasan pemesanan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="search-filter-section">
        <div class="container">
            <div class="search-filter-container">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari gunung berdasarkan nama atau lokasi...">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">
                        <i class="fas fa-globe"></i>
                        Semua
                    </button>
                    <button class="filter-btn" data-filter="jawa-timur">
                        <i class="fas fa-map-marker-alt"></i>
                        Jawa Timur
                    </button>
                    <button class="filter-btn" data-filter="jawa-tengah">
                        <i class="fas fa-map-marker-alt"></i>
                        Jawa Tengah
                    </button>
                    <button class="filter-btn" data-filter="jawa-barat">
                        <i class="fas fa-map-marker-alt"></i>
                        Jawa Barat
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Mountains Grid Section -->
    <section class="mountains-grid-section">
        <div class="container">
            <div class="mountains-grid" id="mountainsGrid">
                <!-- Mountain Card 1 -->
                <div class="mountain-detail-card" data-region="jawa-timur" data-name="gunung bromo">
                    <div class="mountain-image">
                        <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop"
                            alt="Gunung Bromo">
                        <div class="difficulty-badge beginner">Pemula</div>
                    </div>
                    <div class="mountain-content">
                        <div class="mountain-header">
                            <h3>Gunung Bromo</h3>
                        </div>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Timur</span>
                        </div>
                        <div class="mountain-stats">
                            <div class="stat">
                                <i class="fas fa-mountain"></i>
                                <span>2.329 mdpl</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>2-3 jam</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-thermometer-half"></i>
                                <span>5-15°C</span>
                            </div>
                        </div>
                        <p class="mountain-description">
                            Gunung berapi aktif yang terkenal dengan pemandangan sunrise spektakuler dan lautan pasir
                            yang menakjubkan.
                        </p>
                        <div class="mountain-footer">
                            <div class="price">
                                <span class="price-label">Mulai dari</span>
                                <span class="price-amount">Rp 35.000</span>
                            </div>
                            <button class="btn-detail" onclick="openMountainModal('bromo')">
                                <i class="fas fa-info-circle"></i>
                                Detail
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mountain Card 2 -->
                <div class="mountain-detail-card" data-region="jawa-tengah" data-name="gunung merapi">
                    <div class="mountain-image">
                        <img src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop"
                            alt="Gunung Merapi">
                        <div class="difficulty-badge intermediate">Menengah</div>
                    </div>
                    <div class="mountain-content">
                        <div class="mountain-header">
                            <h3>Gunung Merapi</h3>
                        </div>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Tengah</span>
                        </div>
                        <div class="mountain-stats">
                            <div class="stat">
                                <i class="fas fa-mountain"></i>
                                <span>2.930 mdpl</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>4-6 jam</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-thermometer-half"></i>
                                <span>8-18°C</span>
                            </div>
                        </div>
                        <p class="mountain-description">
                            Gunung berapi paling aktif di Indonesia dengan pemandangan kota Yogyakarta yang memukau dari
                            puncaknya.
                        </p>
                        <div class="mountain-footer">
                            <div class="price">
                                <span class="price-label">Mulai dari</span>
                                <span class="price-amount">Rp 25.000</span>
                            </div>
                            <button class="btn-detail" onclick="openMountainModal('merapi')">
                                <i class="fas fa-info-circle"></i>
                                Detail
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mountain Card 3 -->
                <div class="mountain-detail-card" data-region="jawa-timur" data-name="gunung semeru">
                    <div class="mountain-image">
                        <img src="https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop"
                            alt="Gunung Semeru">
                        <div class="difficulty-badge advanced">Lanjutan</div>
                    </div>
                    <div class="mountain-content">
                        <div class="mountain-header">
                            <h3>Gunung Semeru</h3>
                        </div>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Timur</span>
                        </div>
                        <div class="mountain-stats">
                            <div class="stat">
                                <i class="fas fa-mountain"></i>
                                <span>3.676 mdpl</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>2-3 hari</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-thermometer-half"></i>
                                <span>0-10°C</span>
                            </div>
                        </div>
                        <p class="mountain-description">
                            Puncak tertinggi di Pulau Jawa dengan pemandangan yang sangat menakjubkan dan tantangan
                            pendakian yang menantang.
                        </p>
                        <div class="mountain-footer">
                            <div class="price">
                                <span class="price-label">Mulai dari</span>
                                <span class="price-amount">Rp 45.000</span>
                            </div>
                            <button class="btn-detail" onclick="openMountainModal('semeru')">
                                <i class="fas fa-info-circle"></i>
                                Detail
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mountain Card 4 -->
                <div class="mountain-detail-card" data-region="jawa-barat" data-name="gunung gede">
                    <div class="mountain-image">
                        <img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop"
                            alt="Gunung Gede">
                        <div class="difficulty-badge intermediate">Menengah</div>
                    </div>
                    <div class="mountain-content">
                        <div class="mountain-header">
                            <h3>Gunung Gede</h3>
                        </div>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Barat</span>
                        </div>
                        <div class="mountain-stats">
                            <div class="stat">
                                <i class="fas fa-mountain"></i>
                                <span>2.958 mdpl</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>5-7 jam</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-thermometer-half"></i>
                                <span>10-20°C</span>
                            </div>
                        </div>
                        <p class="mountain-description">
                            Gunung dengan keanekaragaman flora dan fauna yang tinggi, terkenal dengan air terjun dan
                            danau kawahnya.
                        </p>
                        <div class="mountain-footer">
                            <div class="price">
                                <span class="price-label">Mulai dari</span>
                                <span class="price-amount">Rp 30.000</span>
                            </div>
                            <button class="btn-detail" onclick="openMountainModal('gede')">
                                <i class="fas fa-info-circle"></i>
                                Detail
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mountain Card 5 -->
                <div class="mountain-detail-card" data-region="jawa-barat" data-name="gunung papandayan">
                    <div class="mountain-image">
                        <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop"
                            alt="Gunung Papandayan">
                        <div class="difficulty-badge beginner">Pemula</div>
                    </div>
                    <div class="mountain-content">
                        <div class="mountain-header">
                            <h3>Gunung Papandayan</h3>
                        </div>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Barat</span>
                        </div>
                        <div class="mountain-stats">
                            <div class="stat">
                                <i class="fas fa-mountain"></i>
                                <span>2.665 mdpl</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>3-4 jam</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-thermometer-half"></i>
                                <span>12-22°C</span>
                            </div>
                        </div>
                        <p class="mountain-description">
                            Gunung dengan kawah aktif yang mengeluarkan gas belerang dan pemandangan savana yang indah.
                        </p>
                        <div class="mountain-footer">
                            <div class="price">
                                <span class="price-label">Mulai dari</span>
                                <span class="price-amount">Rp 20.000</span>
                            </div>
                            <button class="btn-detail" onclick="openMountainModal('papandayan')">
                                <i class="fas fa-info-circle"></i>
                                Detail
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mountain Card 6 -->
                <div class="mountain-detail-card" data-region="jawa-tengah" data-name="gunung merbabu">
                    <div class="mountain-image">
                        <img src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=500&h=300&fit=crop"
                            alt="Gunung Merbabu">
                        <div class="difficulty-badge intermediate">Menengah</div>
                    </div>
                    <div class="mountain-content">
                        <div class="mountain-header">
                            <h3>Gunung Merbabu</h3>
                        </div>
                        <div class="mountain-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jawa Tengah</span>
                        </div>
                        <div class="mountain-stats">
                            <div class="stat">
                                <i class="fas fa-mountain"></i>
                                <span>3.145 mdpl</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>6-8 jam</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-thermometer-half"></i>
                                <span>5-15°C</span>
                            </div>
                        </div>
                        <p class="mountain-description">
                            Gunung dengan padang savana yang luas dan pemandangan Gunung Merapi yang spektakuler.
                        </p>
                        <div class="mountain-footer">
                            <div class="price">
                                <span class="price-label">Mulai dari</span>
                                <span class="price-amount">Rp 28.000</span>
                            </div>
                            <button class="btn-detail" onclick="openMountainModal('merbabu')">
                                <i class="fas fa-info-circle"></i>
                                Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mountain Detail Modal -->
    <div class="modal-overlay" id="mountainModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Detail Gunung</h2>
                <button class="modal-close" onclick="closeMountainModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be dynamically loaded -->
            </div>
        </div>
    </div>

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
</body>

</html>