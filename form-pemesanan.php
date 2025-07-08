<?php
session_start();
// Pastikan kedua file ini ada dan path-nya benar
require_once 'config/database.php'; 
require_once 'auth/check_auth.php'; // Script ini harus memastikan $_SESSION['user_id'] tersedia

// Blok ini HANYA akan berjalan saat formulir di-submit dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Ambil & Sanitasi Data dari Form
    $user_id = $_SESSION['user_id'];
    $tiket_id = intval($_POST['tiket_id'] ?? 0);
    $tanggal_pendakian = $_POST['tanggal_pendakian'] ?? '';
    $jumlah_pendaki = intval($_POST['jumlah_pendaki'] ?? 0);
    $selected_services = $_POST['services'] ?? []; // Ini adalah array, contoh: ['guide', 'ojek']

    // 2. Validasi Data Penting
    if ($tiket_id <= 0 || empty($tanggal_pendakian) || $jumlah_pendaki <= 0) {
        die("Data dasar tidak lengkap. Pastikan gunung, tanggal, dan jumlah pendaki sudah terisi. Mohon ulangi proses pemesanan.");
    }

    // Mulai database transaction untuk memastikan semua query berhasil atau tidak sama sekali
    $pdo->beginTransaction();

    try {
        // 3. Ambil Harga Tiket dari DATABASE (Sumber Tepercaya)
        $stmt_tiket = $pdo->prepare("SELECT harga_tiket FROM tiket_gunung WHERE id = ?");
        $stmt_tiket->execute([$tiket_id]);
        $tiket = $stmt_tiket->fetch();

        if (!$tiket) {
            throw new Exception("Tiket gunung tidak valid.");
        }
        $harga_tiket_satuan = floatval($tiket['harga_tiket']);
        $subtotal_tiket = $harga_tiket_satuan * $jumlah_pendaki;
        
        // 4. Hitung Harga Layanan Tambahan dari DATABASE
        $subtotal_layanan = 0;
        $layanan_data_to_insert = []; // Untuk menyimpan detail layanan yang akan di-insert
        if (!empty($selected_services)) {
            $placeholders = implode(',', array_fill(0, count($selected_services), '?'));
            $stmt_layanan = $pdo->prepare("SELECT id, nama_layanan, harga_layanan, satuan FROM layanan WHERE nama_layanan IN ($placeholders)");
            $stmt_layanan->execute($selected_services);
            $db_services = $stmt_layanan->fetchAll(PDO::FETCH_ASSOC);

            foreach ($db_services as $service) {
                $harga_layanan_per_item = floatval($service['harga_layanan']);
                $jumlah_item_layanan = 1;
                $harga_total_per_layanan = $harga_layanan_per_item;

                // Logika Kalkulasi berdasarkan satuan layanan
                if ($service['satuan'] === '/orang') {
                    $jumlah_item_layanan = $jumlah_pendaki;
                    $harga_total_per_layanan = $harga_layanan_per_item * $jumlah_pendaki;
                }
                // Asumsi pendakian 2 hari untuk guide/porter/basecamp
                if ($service['satuan'] === '/hari' || $service['satuan'] === '/malam') {
                     $jumlah_item_layanan = 2; // Hardcode 2 hari/malam, bisa dibuat dinamis
                     $harga_total_per_layanan = $harga_layanan_per_item * 2;
                }

                $subtotal_layanan += $harga_total_per_layanan;
                $layanan_data_to_insert[] = [
                    'layanan_id' => $service['id'],
                    'jumlah' => $jumlah_item_layanan,
                    'harga_saat_pesan' => $harga_total_per_layanan 
                ];
            }
        }
        
        // 5. Hitung Total Harga di Server (Final & Aman)
        $total_harga = $subtotal_tiket + $subtotal_layanan;

        // 6. Buat Kode Booking Unik
        $kode_booking = 'KNCD-' . strtoupper(substr(uniqid(), 7, 6));

        // 7. Insert data ke tabel utama `pemesanan`
        $stmt_pemesanan = $pdo->prepare(
            "INSERT INTO pemesanan (user_id, tiket_id, kode_booking, tanggal_pendakian, tanggal_turun, jumlah_pendaki, subtotal_tiket, subtotal_layanan, total_harga, status_pemesanan, tanggal_pemesanan)
             VALUES (?, ?, ?, ?, DATE_ADD(?, INTERVAL 1 DAY), ?, ?, ?, ?, 'menunggu pembayaran', NOW())" // Asumsi pendakian 2 hari 1 malam
        );
        $stmt_pemesanan->execute([
            $user_id, $tiket_id, $kode_booking, $tanggal_pendakian, $tanggal_pendakian, 
            $jumlah_pendaki, $subtotal_tiket, $subtotal_layanan, $total_harga
        ]);

        // 8. Dapatkan ID dari pemesanan yang baru saja dibuat
        $pemesanan_id = $pdo->lastInsertId();

        // 9. Insert data layanan tambahan ke tabel `pemesanan_layanan`
        if (!empty($layanan_data_to_insert)) {
            $stmt_pemesanan_layanan = $pdo->prepare(
                "INSERT INTO pemesanan_layanan (pemesanan_id, layanan_id, jumlah, harga_saat_pesan) VALUES (?, ?, ?, ?)"
            );
            foreach ($layanan_data_to_insert as $data) {
                $stmt_pemesanan_layanan->execute([$pemesanan_id, $data['layanan_id'], $data['jumlah'], $data['harga_saat_pesan']]);
            }
        }

        // 10. Jika semua query berhasil, simpan perubahan secara permanen
        $pdo->commit();

        // Arahkan pengguna ke halaman dashboard atau pembayaran
        header("Location: proses_pembayaran.php?id=" . $pemesanan_id);
        exit;

    } catch (Exception $e) {
        // Jika terjadi error di salah satu langkah, batalkan semua query
        $pdo->rollBack();
        die("Gagal memproses pemesanan: " . $e->getMessage());
    }
}
// Jika halaman diakses dengan metode GET, kode PHP di atas akan diabaikan dan hanya HTML di bawah ini yang ditampilkan.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Tiket Gunung - KoncoNdaki</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/form-pemesanan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="dashboard.php">
                        <img src="images/logo.png" alt="KoncoNdaki Logo" width="120" height="40">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links desktop-nav" role="menubar">
                    <a href="dashboard.php" class="nav-link" role="menuitem">Home</a>
                    <a href="info-gunung.php" class="nav-link" role="menuitem">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link" role="menuitem">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link" role="menuitem">Diskusi</a>
                    <a href="tentang.php" class="nav-link" role="menuitem">Tentang</a>
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
                    <a href="tentang.php" class="mobile-nav-link" role="menuitem">Tentang</a>
                    
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
    
    <section class="booking-form-section">
        <div class="container">
            <div class="booking-layout">
                <div class="booking-form-container">
                    <div class="form-header">
                        <h2>Mulai Pemesanan</h2>
                        <p>Isi form di bawah untuk memulai proses pemesanan tiket</p>
                    </div>
                    <form id="bookingForm" class="booking-form" method="POST" action="form-pemesanan.php">
                        <div class="form-step active" id="step-1">
                            <div class="step-header"><h3><i class="fas fa-mountain"></i> Pilih Gunung</h3></div>
                            <div class="mountain-selection">
                                <div class="mountain-option" data-mountain="bromo"><img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Bromo"><div class="mountain-info"><h4>Gunung Bromo</h4><p>Jawa Timur</p><span class="price">Rp 35.000</span></div></div>
                                <div class="mountain-option" data-mountain="merapi"><img src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Merapi"><div class="mountain-info"><h4>Gunung Merapi</h4><p>Jawa Tengah</p><span class="price">Rp 25.000</span></div></div>
                                <div class="mountain-option" data-mountain="semeru"><img src="https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Semeru"><div class="mountain-info"><h4>Gunung Semeru</h4><p>Jawa Timur</p><span class="price">Rp 45.000</span></div></div>
                                <div class="mountain-option" data-mountain="gede"><img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Gede"><div class="mountain-info"><h4>Gunung Gede</h4><p>Jawa Barat</p><span class="price">Rp 30.000</span></div></div>
                            </div>
                            <div class="form-actions"><button type="button" class="btn-back" id="backStep1"><i class="fas fa-arrow-left"></i> Kembali</button><button type="button" class="btn-next" id="nextStep1" disabled>Lanjutkan <i class="fas fa-arrow-right"></i></button></div>
                        </div>
                        <div class="form-step" id="step-2">
                            <div class="step-header"><h3><i class="fas fa-route"></i> Pilih Jalur & Tanggal</h3></div>
                            <div class="route-selection" id="routeSelection"><p class="placeholder">Pilih gunung terlebih dahulu untuk melihat jalur yang tersedia.</p></div>
                            <div class="date-selection">
                                <div class="form-group"><label for="hikingDate">Tanggal Pendakian</label><input type="date" id="hikingDate" name="tanggal_pendakian" required></div>
                                <div class="form-group"><label for="participants">Jumlah Pendaki</label><input type="number" id="participants" name="jumlah_pendaki" value="1" min="1" max="10" required></div>
                            </div>
                            <div class="form-actions"><button type="button" class="btn-back" id="backStep2"><i class="fas fa-arrow-left"></i> Kembali</button><button type="button" class="btn-next" id="nextStep2" disabled>Lanjutkan <i class="fas fa-arrow-right"></i></button></div>
                        </div>
                        <div class="form-step" id="step-3">
                            <div class="step-header"><h3><i class="fas fa-plus-circle"></i> Layanan Tambahan</h3></div>
                            <div class="services-grid">
                                <div class="service-card" data-service="guide"><div class="service-icon"><i class="fas fa-user-tie"></i></div><div class="service-info"><h4>Jasa Guide</h4><p>Pemandu berpengalaman</p><span class="service-price">Rp 150.000/hari</span></div><div class="service-toggle"><input type="checkbox" id="guide" name="services[]" value="guide"><label for="guide" class="toggle-switch"></label></div></div>
                                <div class="service-card" data-service="porter"><div class="service-icon"><i class="fas fa-hiking"></i></div><div class="service-info"><h4>Jasa Porter</h4><p>Bantuan membawa barang</p><span class="service-price">Rp 100.000/hari</span></div><div class="service-toggle"><input type="checkbox" id="porter" name="services[]" value="porter"><label for="porter" class="toggle-switch"></label></div></div>
                                <div class="service-card" data-service="ojek"><div class="service-icon"><i class="fas fa-motorcycle"></i></div><div class="service-info"><h4>Jasa Ojek</h4><p>Transportasi ke pos</p><span class="service-price">Rp 50.000/orang</span></div><div class="service-toggle"><input type="checkbox" id="ojek" name="services[]" value="ojek"><label for="ojek" class="toggle-switch"></label></div></div>
                                <div class="service-card" data-service="basecamp"><div class="service-icon"><i class="fas fa-campground"></i></div><div class="service-info"><h4>Sewa Basecamp</h4><p>Tempat istirahat</p><span class="service-price">Rp 75.000/malam</span></div><div class="service-toggle"><input type="checkbox" id="basecamp" name="services[]" value="basecamp"><label for="basecamp" class="toggle-switch"></label></div></div>
                            </div>
                            <div class="form-actions"><button type="button" class="btn-back" id="backStep3"><i class="fas fa-arrow-left"></i> Kembali</button><button type="button" class="btn-next" id="nextStep3">Lanjutkan <i class="fas fa-arrow-right"></i></button></div>
                        </div>
                        <div class="form-step" id="step-4">
                            <div class="step-header"><h3><i class="fas fa-credit-card"></i> Konfirmasi & Pembayaran</h3></div>
                            <div class="booking-summary" id="bookingSummary"><p class="placeholder">Ringkasan akhir akan muncul di sini.</p></div>
                            <div class="payment-methods">
                                <h4>Metode Pembayaran</h4>
                                <div class="payment-options"><div class="payment-option"><input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" checked><label for="bank_transfer"><i class="fas fa-university"></i> Transfer Bank</label></div></div>
                            </div>
                            <div class="terms-agreement"><label class="checkbox-container"><input type="checkbox" id="agreeTerms" required><span class="checkmark"></span> Saya setuju dengan <a href="syarat-ketentuan.php" target="_blank">syarat & ketentuan</a></label></div>
                            <div class="form-actions"><button type="button" class="btn-back" id="backStep4"><i class="fas fa-arrow-left"></i> Kembali</button><button type="submit" class="btn-submit" id="submitBooking" disabled>Bayar Sekarang</button></div>
                        </div>
                    </form>
                </div>
                <div class="booking-sidebar">
                    <div class="sidebar-card"><h3>Ringkasan Pemesanan</h3><div class="summary-content" id="sidebarSummary"><p class="placeholder">Pilih item untuk melihat ringkasan.</p></div></div>
                </div>
            </div>
        </div>
    </section>

    <script src="scripts/form-pemesanan.js"></script>
    <script src="scripts/tentang.js"></script>
</body>
</html>
