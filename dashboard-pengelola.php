<?php
session_start();
require_once 'config/database.php';

// --- LOGIKA UNTUK MEMBUAT AKUN PARTNER BARU ---
$createUserError = '';
$createUserSuccess = '';
// Cek apakah form 'Buat Partner' yang di-submit
if (isset($_POST['submit_create_user'])) {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? '');

    // Peran yang bisa dibuat oleh admin HANYA partner
    $creatable_roles = ['porter', 'guide', 'ojek'];

    if (empty($firstName) || empty($email) || empty($password) || empty($role)) {
        $createUserError = 'Nama Depan, Email, Password, dan Peran harus diisi.';
    } elseif (!in_array($role, $creatable_roles)) {
        $createUserError = 'Peran yang dipilih tidak valid.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $createUserError = 'Email sudah digunakan oleh akun lain.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$firstName, $lastName, $email, $phone, $hashedPassword, $role]);
                $createUserSuccess = "Akun untuk partner '{$role}' dengan email '{$email}' berhasil dibuat.";
            }
        } catch (PDOException $e) {
            $createUserError = 'Gagal membuat akun. Terjadi kesalahan sistem.';
        }
    }
}

// --- LOGIKA UNTUK MENGAMBIL DATA BOOKING (FITUR ASLI) ---
$stmt = $pdo->prepare("
    SELECT 
        pt.id, 
        u.first_name, 
        u.last_name, 
        g.nama_gunung, 
        pt.tanggal_pendakian, 
        pt.status_pemesanan,
        '' AS partner_dibutuhkan
    FROM 
        pemesanan_tiket pt
    JOIN 
        users u ON pt.user_id = u.id
    JOIN 
        tiket t ON pt.tiket_id = t.id
    JOIN 
        gunung g ON t.gunung_id = g.id
    ORDER BY 
        pt.tanggal_pendakian DESC
");
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pengelola Gunung</title>
    <meta name="description" content="Dashboard untuk pengelola gunung dan sistem pendakian di KoncoNdaki.">
    <link rel="stylesheet" href="styles/dashboard-pengelola.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <h1><i class="fas fa-mountain"></i> Dashboard Pengelola Gunung</h1>
                <p>Sistem pengelolaan pendakian dan operasional gunung terpadu</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="dashboard-layout">
                <!-- Sidebar -->
                <aside class="dashboard-sidebar">
                    <div class="sidebar-header">
                        <h3><i class="fas fa-cogs"></i> Menu Pengelola</h3>
                        <div class="admin-info">
                            <span class="admin-name">Admin: <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></span>
                            <div class="admin-actions">
                                <span class="notification-icon"><i class="fas fa-bell"></i></span>
                                <a href="auth/logout.php" title="Logout" class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Categories -->
                    <div class="sidebar-section">
                        <h4>Menu Utama</h4>
                        <ul class="category-list">
                            <li class="category-item active" data-category="dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </li>
                            <li class="category-item" data-category="kelolaBooking">
                                <i class="fas fa-calendar-check"></i>
                                <span>Kelola Booking</span>
                                <span class="count">24</span>
                            </li>
                            <li class="category-item" data-category="kelolaKuota">
                                <i class="fas fa-users"></i>
                                <span>Kelola Kuota</span>
                            </li>
                            <li class="category-item" data-category="dataPendaki">
                                <i class="fas fa-hiking"></i>
                                <span>Data Pendaki</span>
                                <span class="count">1,247</span>
                            </li>
                            <li class="category-item" data-category="laporanKeuangan">
                                <i class="fas fa-chart-line"></i>
                                <span>Laporan Keuangan</span>
                            </li>
                            <li class="category-item" data-category="partnerNetwork">
                                <i class="fas fa-network-wired"></i>
                                <span>Partner Network</span>
                            </li>
                            <li class="category-item" data-category="pesananLayanan">
                                <i class="fas fa-clipboard-list"></i>
                                <span>Pesanan Layanan</span>
                                <span class="count">12</span>
                            </li>
                            <li class="category-item" data-category="kelolaPartner">
                                <i class="fas fa-user-friends"></i>
                                <span>Kelola Partner</span>
                            </li>
                            <li class="category-item" data-category="buatPengguna">
                                <i class="fas fa-user-plus"></i>
                                <span>Buat Partner Baru</span>
                            </li>
                            <li class="category-item" data-category="sistem">
                                <i class="fas fa-cog"></i>
                                <span>Pengaturan Sistem</span>
                            </li>
                        </ul>
                    </div>
                </aside>

                <!-- Main Dashboard Content -->
                <div class="dashboard-content">
                    <!-- Dashboard Overview Section -->
                    <section id="dashboard" class="content-section active">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Dashboard Operasional</h2>
                                <p>Ringkasan aktivitas dan statistik pendakian hari ini</p>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon pendaki">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">1,247</div>
                                    <div class="stat-label">Total Pendaki Bulan Ini</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon kuota">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">78%</div>
                                    <div class="stat-label">Kuota Terpakai</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon jalur">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">5</div>
                                    <div class="stat-label">Jalur Aktif</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon pendapatan">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">Rp 124.7M</div>
                                    <div class="stat-label">Pendapatan Bulan Ini</div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Section -->
                        <div class="charts-section">
                            <div class="chart-container">
                                <h3 class="chart-title">Tren Jumlah Pendaki (6 Bulan Terakhir)</h3>
                                <canvas id="trendChart" width="400" height="200"></canvas>
                            </div>
                            <div class="chart-container">
                                <h3 class="chart-title">Distribusi Jalur Pendakian</h3>
                                <canvas id="pathChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </section>

                    <!-- Kelola Booking Section -->
                    <section id="kelolaBooking" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Kelola Booking</h2>
                                <p>Mengelola semua pemesanan tiket pendakian</p>
                            </div>
                            <div class="header-actions">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Cari booking..." id="searchBooking">
                                </div>
                                <button class="btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Booking Baru
                                </button>
                            </div>
                        </div>

                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>ID Booking</th>
                                        <th>Pendaki</th>
                                        <th>Gunung</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Partner</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td><span class="booking-id">#<?php echo htmlspecialchars($booking['id']); ?></span></td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['nama_gunung']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['tanggal_pendakian']); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo htmlspecialchars(strtolower($booking['status_pemesanan'])); ?>">
                                                <?php echo htmlspecialchars($booking['status_pemesanan']); ?>
                                            </span>
                                        </td>
                                        <td class="partner-icons">
                                            <i class="fas fa-map-marked-alt" title="Guide"></i>
                                            <i class="fas fa-hiking" title="Porter"></i>
                                            <i class="fas fa-motorcycle" title="Ojek"></i>
                                        </td>
                                        <td class="action-buttons">
                                            <button class="btn-action btn-verify" title="Verifikasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-action btn-delete" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Kelola Kuota Section -->
                    <section id="kelolaKuota" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Kelola Kuota Jalur</h2>
                                <p>Mengatur kuota maksimal untuk setiap jalur pendakian</p>
                            </div>
                        </div>

                        <div class="form-container">
                            <form class="modern-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="inputJalur">Nama Jalur</label>
                                        <input type="text" id="inputJalur" placeholder="Masukkan nama jalur">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputKuota">Kuota Maksimal</label>
                                        <input type="number" id="inputKuota" placeholder="Masukkan kuota">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn-primary">
                                            <i class="fas fa-plus"></i>
                                            Tambah Jalur
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Jalur</th>
                                        <th>Kuota Maksimal</th>
                                        <th>Kuota Terpakai</th>
                                        <th>Persentase</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="jalur-info">
                                                <i class="fas fa-route"></i>
                                                <span>Jalur Selo</span>
                                            </div>
                                        </td>
                                        <td><input type="number" value="500" class="table-input"></td>
                                        <td>312</td>
                                        <td>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 62%"></div>
                                                <span class="progress-text">62%</span>
                                            </div>
                                        </td>
                                        <td class="action-buttons">
                                            <button class="btn-action btn-save" title="Simpan">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button class="btn-action btn-delete" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Buat Partner Baru Section -->
                    <section id="buatPengguna" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Buat Akun Partner Baru</h2>
                                <p>Membuat akun baru untuk Partner (Porter, Guide, atau Ojek)</p>
                            </div>
                        </div>

                        <?php if (!empty($createUserError)): ?>
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $createUserError; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($createUserSuccess)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <?php echo $createUserSuccess; ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-container">
                            <form method="POST" action="dashboard-pengelola-new.php#buatPengguna" class="modern-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="firstName">Nama Depan</label>
                                        <input type="text" id="firstName" name="firstName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Nama Belakang</label>
                                        <input type="text" id="lastName" name="lastName">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="email">Email (untuk login)</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Nomor Telepon</label>
                                        <input type="tel" id="phone" name="phone" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="password">Password Sementara</label>
                                        <input type="text" id="password" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Peran (Role) Partner</label>
                                        <select id="role" name="role" required>
                                            <option value="" disabled selected>-- Pilih Peran Partner --</option>
                                            <option value="porter">Porter</option>
                                            <option value="guide">Guide</option>
                                            <option value="ojek">Ojek</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="submit_create_user" class="btn-primary btn-large">
                                        <i class="fas fa-user-plus"></i>
                                        Buat Akun Partner
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <!-- Other sections would follow the same pattern -->
                    <section id="dataPendaki" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Data Pendaki</h2>
                                <p>Mengelola data semua pendaki yang terdaftar</p>
                            </div>
                        </div>
                        <div class="placeholder-content">
                            <i class="fas fa-hiking"></i>
                            <h3>Data Pendaki</h3>
                            <p>Fitur ini akan menampilkan daftar lengkap pendaki yang terdaftar</p>
                        </div>
                    </section>

                    <section id="laporanKeuangan" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Laporan Keuangan</h2>
                                <p>Laporan pendapatan dan pengeluaran operasional</p>
                            </div>
                        </div>
                        <div class="placeholder-content">
                            <i class="fas fa-chart-line"></i>
                            <h3>Laporan Keuangan</h3>
                            <p>Fitur ini akan menampilkan laporan keuangan lengkap</p>
                        </div>
                    </section>

                    <section id="partnerNetwork" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Partner Network</h2>
                                <p>Mengelola jaringan partner dan komunikasi</p>
                            </div>
                        </div>
                        <div class="placeholder-content">
                            <i class="fas fa-network-wired"></i>
                            <h3>Partner Network</h3>
                            <p>Fitur ini akan menampilkan jaringan partner dan sistem komunikasi</p>
                        </div>
                    </section>

                    <section id="pesananLayanan" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Pesanan Layanan</h2>
                                <p>Mengelola pesanan layanan tambahan</p>
                            </div>
                        </div>
                        <div class="placeholder-content">
                            <i class="fas fa-clipboard-list"></i>
                            <h3>Pesanan Layanan</h3>
                            <p>Fitur ini akan menampilkan daftar pesanan layanan tambahan</p>
                        </div>
                    </section>

                    <section id="kelolaPartner" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Kelola Partner</h2>
                                <p>Mengelola semua partner yang terdaftar</p>
                            </div>
                        </div>
                        <div class="placeholder-content">
                            <i class="fas fa-user-friends"></i>
                            <h3>Kelola Partner</h3>
                            <p>Fitur ini akan menampilkan daftar dan pengelolaan partner</p>
                        </div>
                    </section>

                    <section id="sistem" class="content-section">
                        <div class="content-header">
                            <div class="header-left">
                                <h2>Pengaturan Sistem</h2>
                                <p>Konfigurasi dan pengaturan sistem</p>
                            </div>
                        </div>
                        <div class="placeholder-content">
                            <i class="fas fa-cog"></i>
                            <h3>Pengaturan Sistem</h3>
                            <p>Fitur ini akan menampilkan pengaturan dan konfigurasi sistem</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content" style="justify-content: center; text-align: center;">
                <div class="footer-section" style="margin: 0 auto;">
                    <div class="footer-logo" style="justify-content: center;">
                        <i class="fas fa-mountain"></i>
                        <span>KoncoNdaki</span>
                    </div>
                    <p>Platform terpercaya untuk pengelolaan pendakian gunung di seluruh Pulau Jawa.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 KoncoNdaki. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="scripts/dashboard-pengelola.js"></script>
</body>
</html>