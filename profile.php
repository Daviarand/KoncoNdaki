<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya - KoncoNdaki</title>
    <meta name="description" content="Profile pengguna KoncoNdaki - Kelola informasi akun Anda.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/profile-styles.css">
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
                    <a href="dashboard.php" class="nav-link">Home</a>
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
                            <span class="profile-name" id="profileName">John Doe</span>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </button>

                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar large">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="profile-info">
                                    <h4 id="menuProfileName">John Doe</h4>
                                    <p id="menuProfileEmail">john.doe@email.com</p>
                                </div>
                            </div>
                            <div class="profile-menu-items">
                                <a href="profile.php" class="profile-menu-item active">
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
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="profile-info">
                            <h4 id="mobileProfileName">John Doe</h4>
                            <p id="mobileProfileEmail">john.doe@email.com</p>
                        </div>
                    </div>

                    <a href="dashboard.php" class="mobile-nav-link">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>

                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link active">
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

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-page-header">
                    <div class="profile-avatar-large">
                        <i class="fas fa-user"></i>
                        <button class="avatar-edit-btn" id="avatarEditBtn" title="Ubah foto profil">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <div class="profile-header-info">
                        <h1 id="profilePageName">John Doe</h1>
                        <p id="profilePageEmail">john.doe@email.com</p>
                        <div class="profile-badges">
                            <span class="badge badge-verified">
                                <i class="fas fa-check-circle"></i>
                                Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="profile-content">
                    <!-- Profile Tabs -->
                    <div class="profile-tabs">
                        <button class="tab-btn active" data-tab="personal">
                            <i class="fas fa-user"></i>
                            <span>Informasi Personal</span>
                        </button>
                        <button class="tab-btn" data-tab="security">
                            <i class="fas fa-shield-alt"></i>
                            <span>Keamanan</span>
                        </button>
                        <button class="tab-btn" data-tab="preferences">
                            <i class="fas fa-cog"></i>
                            <span>Preferensi</span>
                        </button>
                    </div>

                    <!-- Personal Information Tab -->
                    <div class="tab-content active" id="personal-tab">
                        <div class="profile-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>
                                        <i class="fas fa-user-edit"></i>
                                        Informasi Personal
                                    </h3>
                                    <p>Kelola informasi dasar akun Anda</p>
                                </div>
                                <button class="btn-edit" id="editPersonalBtn">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>
                            </div>
                            <div class="card-content">
                                <form id="personalForm" class="profile-form">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="firstName">
                                                <i class="fas fa-user"></i>
                                                Nama Depan
                                            </label>
                                            <input type="text" id="firstName" name="firstName" value="John" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastName">
                                                <i class="fas fa-user"></i>
                                                Nama Belakang
                                            </label>
                                            <input type="text" id="lastName" name="lastName" value="Doe" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">
                                            <i class="fas fa-envelope"></i>
                                            Email
                                        </label>
                                        <input type="email" id="email" name="email" value="john.doe@email.com" readonly>
                                        <small class="form-help">Email tidak dapat diubah untuk keamanan akun</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">
                                            <i class="fas fa-phone"></i>
                                            Nomor Telepon
                                        </label>
                                        <input type="tel" id="phone" name="phone" value="+62 812-3456-7890" readonly>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="birthDate">
                                                <i class="fas fa-calendar"></i>
                                                Tanggal Lahir
                                            </label>
                                            <input type="date" id="birthDate" name="birthDate" value="1990-01-15"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="gender">
                                                <i class="fas fa-venus-mars"></i>
                                                Jenis Kelamin
                                            </label>
                                            <select id="gender" name="gender" disabled>
                                                <option value="">Pilih jenis kelamin</option>
                                                <option value="male" selected>Laki-laki</option>
                                                <option value="female">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Alamat
                                        </label>
                                        <textarea id="address" name="address" rows="3"
                                            readonly>Jl. Contoh No. 123, Jakarta Selatan, DKI Jakarta</textarea>
                                    </div>
                                    <div class="form-actions" id="personalActions" style="display: none;">
                                        <button type="button" class="btn-cancel" id="cancelPersonalBtn">
                                            <i class="fas fa-times"></i>
                                            Batal
                                        </button>
                                        <button type="submit" class="btn-save">
                                            <i class="fas fa-save"></i>
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Statistics Card -->
                        <div class="profile-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>
                                        <i class="fas fa-chart-bar"></i>
                                        Statistik Pendakian
                                    </h3>
                                    <p>Ringkasan aktivitas pendakian Anda</p>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <div class="stat-icon green">
                                            <i class="fas fa-mountain"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h4>5</h4>
                                            <p>Gunung Didaki</p>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon blue">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h4>12</h4>
                                            <p>Total Tiket</p>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon orange">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h4>8</h4>
                                            <p>Pendakian Selesai</p>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon purple">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h4>3</h4>
                                            <p>Pencapaian</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-content" id="security-tab">
                        <div class="profile-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>
                                        <i class="fas fa-key"></i>
                                        Ubah Password
                                    </h3>
                                    <p>Pastikan akun Anda tetap aman dengan password yang kuat</p>
                                </div>
                            </div>
                            <div class="card-content">
                                <form id="passwordForm" class="profile-form">
                                    <div class="form-group">
                                        <label for="currentPassword">
                                            <i class="fas fa-lock"></i>
                                            Password Saat Ini
                                        </label>
                                        <div class="input-group">
                                            <input type="password" id="currentPassword" name="currentPassword" required>
                                            <button type="button" class="toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">
                                            <i class="fas fa-key"></i>
                                            Password Baru
                                        </label>
                                        <div class="input-group">
                                            <input type="password" id="newPassword" name="newPassword" required>
                                            <button type="button" class="toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength">
                                            <div class="strength-bar">
                                                <div class="strength-fill"></div>
                                            </div>
                                            <span class="strength-text">Masukkan password baru</span>
                                        </div>
                                        <div class="password-requirements">
                                            <p>Password harus memenuhi kriteria berikut:</p>
                                            <ul>
                                                <li><i class="fas fa-times"></i> Minimal 8 karakter</li>
                                                <li><i class="fas fa-times"></i> Mengandung huruf besar</li>
                                                <li><i class="fas fa-times"></i> Mengandung huruf kecil</li>
                                                <li><i class="fas fa-times"></i> Mengandung angka</li>
                                                <li><i class="fas fa-times"></i> Mengandung simbol</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmNewPassword">
                                            <i class="fas fa-check-double"></i>
                                            Konfirmasi Password Baru
                                        </label>
                                        <div class="input-group">
                                            <input type="password" id="confirmNewPassword" name="confirmNewPassword"
                                                required>
                                            <button type="button" class="toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-save">
                                            <i class="fas fa-shield-alt"></i>
                                            Ubah Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Two Factor Authentication -->
                        <div class="profile-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>
                                        <i class="fas fa-mobile-alt"></i>
                                        Autentikasi Dua Faktor
                                    </h3>
                                    <p>Tingkatkan keamanan akun dengan verifikasi tambahan</p>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="security-option">
                                    <div class="security-info">
                                        <div class="security-icon">
                                            <i class="fas fa-sms"></i>
                                        </div>
                                        <div>
                                            <h4>SMS Authentication</h4>
                                            <p>Terima kode verifikasi melalui SMS ke nomor telepon Anda</p>
                                        </div>
                                    </div>
                                    <div class="security-toggle">
                                        <label class="switch">
                                            <input type="checkbox" id="smsAuth">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="security-option">
                                    <div class="security-info">
                                        <div class="security-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <h4>Email Authentication</h4>
                                            <p>Terima kode verifikasi melalui email yang terdaftar</p>
                                        </div>
                                    </div>
                                    <div class="security-toggle">
                                        <label class="switch">
                                            <input type="checkbox" id="emailAuth" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="security-option">
                                    <div class="security-info">
                                        <div class="security-icon">
                                            <i class="fas fa-qrcode"></i>
                                        </div>
                                        <div>
                                            <h4>Authenticator App</h4>
                                            <p>Gunakan aplikasi authenticator seperti Google Authenticator</p>
                                        </div>
                                    </div>
                                    <div class="security-toggle">
                                        <button class="btn-setup">
                                            <i class="fas fa-plus"></i>
                                            Setup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Tab -->
                    <div class="tab-content" id="preferences-tab">
                        <div class="profile-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>
                                        <i class="fas fa-bell"></i>
                                        Notifikasi
                                    </h3>
                                    <p>Atur preferensi notifikasi sesuai kebutuhan Anda</p>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="preference-option">
                                    <div class="preference-info">
                                        <div class="preference-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <h4>Email Notifications</h4>
                                            <p>Terima notifikasi pemesanan dan update via email</p>
                                        </div>
                                    </div>
                                    <div class="preference-toggle">
                                        <label class="switch">
                                            <input type="checkbox" id="emailNotif" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="preference-option">
                                    <div class="preference-info">
                                        <div class="preference-icon">
                                            <i class="fas fa-sms"></i>
                                        </div>
                                        <div>
                                            <h4>SMS Notifications</h4>
                                            <p>Terima notifikasi penting via SMS</p>
                                        </div>
                                    </div>
                                    <div class="preference-toggle">
                                        <label class="switch">
                                            <input type="checkbox" id="smsNotif">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="preference-option">
                                    <div class="preference-info">
                                        <div class="preference-icon">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                        <div>
                                            <h4>Marketing Emails</h4>
                                            <p>Terima informasi penawaran dan promosi menarik</p>
                                        </div>
                                    </div>
                                    <div class="preference-toggle">
                                        <label class="switch">
                                            <input type="checkbox" id="marketingEmail" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="preference-option">
                                    <div class="preference-info">
                                        <div class="preference-icon">
                                            <i class="fas fa-mobile-alt"></i>
                                        </div>
                                        <div>
                                            <h4>Push Notifications</h4>
                                            <p>Terima notifikasi langsung di browser</p>
                                        </div>
                                    </div>
                                    <div class="preference-toggle">
                                        <label class="switch">
                                            <input type="checkbox" id="pushNotif" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Language & Region -->
                        <div class="profile-card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3>
                                        <i class="fas fa-globe"></i>
                                        Bahasa & Wilayah
                                    </h3>
                                    <p>Sesuaikan pengaturan bahasa dan zona waktu</p>
                                </div>
                            </div>
                            <div class="card-content">
                                <form class="profile-form">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="language">
                                                <i class="fas fa-language"></i>
                                                Bahasa
                                            </label>
                                            <select id="language" name="language">
                                                <option value="id" selected>Bahasa Indonesia</option>
                                                <option value="en">English</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="timezone">
                                                <i class="fas fa-clock"></i>
                                                Zona Waktu
                                            </label>
                                            <select id="timezone" name="timezone">
                                                <option value="WIB" selected>WIB (UTC+7)</option>
                                                <option value="WITA">WITA (UTC+8)</option>
                                                <option value="WIT">WIT (UTC+9)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="currency">
                                            <i class="fas fa-money-bill"></i>
                                            Mata Uang
                                        </label>
                                        <select id="currency" name="currency">
                                            <option value="IDR" selected>Rupiah (IDR)</option>
                                            <option value="USD">US Dollar (USD)</option>
                                        </select>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-save">
                                            <i class="fas fa-save"></i>
                                            Simpan Preferensi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="scripts/script.js"></script>
    <script src="scripts/profile-script.js"></script>
</body>

</html>