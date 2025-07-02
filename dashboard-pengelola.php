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
    <link rel="stylesheet" href="styles/dashboard-pengelola.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo">
                <h2>🏔️ Admin Gunung</h2>
                <p>Sistem Pengelolaan Pendakian</p>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#" class="nav-link active" data-target="dashboard">📊 Dashboard</a></li>
                    <li><a href="#" class="nav-link" data-target="kelolaBooking">🗓️ Kelola Booking</a></li>
                    <li><a href="#" class="nav-link" data-target="kelolaKuota">👥 Kelola Kuota</a></li>
                    <li><a href="#" class="nav-link" data-target="dataPendaki">🥾 Data Pendaki</a></li>
                    <li><a href="#" class="nav-link" data-target="laporanKeuangan">💰 Laporan Keuangan</a></li>
                    <li><a href="#" class="nav-link" data-target="partnerNetwork">🔔 Notifikasi</a></li>
                    <li><a href="#" class="nav-link" data-target="pesananLayanan">📋 Pesanan Layanan</a></li>
                    <li><a href="#" class="nav-link" data-target="kelolaPartner">👥 Kelola Partner</a></li>
                    <li><a href="#" class="nav-link" data-target="buatPengguna">➕ Buat Partner Baru</a></li>
                    <li><a href="#" class="nav-link" data-target="sistem">⚙️ Sistem</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <h1>Dashboard Operasional</h1>
                <div class="user-info">
                    <span>Admin: <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></span>
                    <span>🔔</span>
                    <a href="auth/logout.php" title="Logout" style="color: inherit; text-decoration: none;"><span><i class="fas fa-sign-out-alt"></i></span></a>
                </div>
            </header>

            <section id="dashboard" class="section active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon pendaki">👥</div>
                        <div class="stat-number">1,247</div>
                        <div class="stat-label">Total Pendaki Bulan Ini</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon kuota">📊</div>
                        <div class="stat-number">78%</div>
                        <div class="stat-label">Kuota Terpakai</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon jalur">🗺️</div>
                        <div class="stat-number">5</div>
                        <div class="stat-label">Jalur Aktif</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon pendapatan">💰</div>
                        <div class="stat-number">Rp 124.7M</div>
                        <div class="stat-label">Pendapatan Bulan Ini</div>
                    </div>
                </div>

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

            <section id="kelolaBooking" class="section">
                <div class="section-header">
                    <h2 class="table-title" style="text-align: left; margin: 0;">Kelola Booking</h2>
                    <a href="#" class="btn-new-booking">Booking Baru</a>
                </div>
                <div class="booking-table-wrapper">
                    <table class="data-table booking-table">
                        <thead>
                            <tr>
                                <th>ID Booking</th>
                                <th>Pendaki</th>
                                <th>Gunung</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Partner Dibutuhkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr data-booking-id="<?php echo htmlspecialchars($booking['id']); ?>">
                                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['nama_gunung']); ?></td>
                                <td><?php echo htmlspecialchars($booking['tanggal_pendakian']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo htmlspecialchars(strtolower($booking['status_pemesanan'])); ?>">
                                        <?php echo htmlspecialchars($booking['status_pemesanan']); ?>
                                    </span>
                                </td>
                                <td class="partner-icons">
                                    <?php
                                    $partners = explode(',', $booking['partner_dibutuhkan']);
                                    foreach ($partners as $partner) {
                                        $partner = trim($partner);
                                        if ($partner === "guide") echo '<i class="fas fa-map-marked-alt" title="Guide"></i>';
                                        if ($partner === "porter") echo '<i class="fas fa-box" title="Porter"></i>';
                                        if ($partner === "ojek") echo '<i class="fas fa-motorcycle" title="Ojek"></i>';
                                    }
                                    ?>
                                </td>
                                <td class="action-buttons">
                                    <button class="btn-primary btn-verify" onclick="verifyBookingPayment(this)">Verifikasi Pembayaran</button>
                                    <button class="btn-primary btn-assign" onclick="assignBookingPartner(this)" style="display:none;">Tugaskan Partner</button>
                                    <button class="btn-danger" onclick="cancelBooking(this)">Batalkan</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="kelolaKuota" class="section">
                <h2 class="table-title">Kelola Kuota Jalur Pendakian</h2>

                <form class="kuota-form" onsubmit="tambahKuota(event)">
                    <input type="text" id="inputJalur" placeholder="Nama Jalur" required>
                    <input type="number" id="inputKuota" placeholder="Kuota Maksimal" required>
                    <button type="submit" class="btn-primary">Tambah</button>
                </form>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Jalur</th>
                            <th>Kuota Maksimal</th>
                            <th>Kuota Terpakai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jalur Selo</td>
                            <td><input type="number" value="500"></td>
                            <td>312</td>
                            <td>
                                <button class="btn-primary" onclick="updateKuota(this)">Simpan</button>
                                <button class="btn-danger" onclick="hapusKuota(this)">Hapus</button>
                            </td>
                        </tr>
                        </tbody>
                </table>
            </section>

            <section id="dataPendaki" class="section">
                <h2 class="table-title">Data Pendaki</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jalur</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Agus Santoso</td>
                            <td>Jalur Selo</td>
                            <td>2024-06-25</td>
                            <td><span class="status-badge aktif">Aktif</span></td>
                            <td><button class="btn-danger" onclick="hapusPendaki(this)">Hapus</button></td>
                        </tr>
                        </tbody>
                </table>
            </section>

            <section id="laporanKeuangan" class="section">
                <h2 class="table-title">Laporan Keuangan</h2>
                <div style="text-align: center; margin-bottom: 20px;">
                    <button class="btn-primary" onclick="downloadKeuanganPDF()">Download PDF</button>
                </div>
                <table class="data-table" id="tableKeuangan">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Total Pemasukan</th>
                            <th>Pengeluaran</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-06-01</td>
                            <td>Penjualan Paket (Tiket + Add-ons) Gunung Merapi</td>
                            <td>Rp 30.500.000</td>
                            <td>-</td>
                            <td>Rp 30.500.000</td>
                        </tr>
                        </tbody>
                </table>
            </section>

            <section id="partnerNetwork" class="section">
                <h2 class="table-title">Partner Network - Kirim Pesan & Notifikasi</h2>
                </section>

            <section id="pesananLayanan" class="section">
                <h2 class="table-title">Daftar Pesanan Layanan</h2>
                <div id="serviceOrdersList" class="service-orders-container"></div>
            </section>

            <section id="kelolaPartner" class="section">
                <h2 class="table-title">Kelola Partner</h2>
                </section>

            <section id="buatPengguna" class="section">
                <div class="section-header">
                    <h2 class="table-title">Buat Akun Partner Baru</h2>
                </div>
                <div class="form-container" style="max-width: 700px; margin: 20px auto; padding: 2rem; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <p style="margin-bottom: 1.5rem; color: #666;">Gunakan formulir ini untuk membuat akun baru untuk Partner (Porter, Guide, atau Ojek).</p>

                    <?php if (!empty($createUserError)): ?>
                        <div class="message error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 1rem;"><?php echo $createUserError; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($createUserSuccess)): ?>
                        <div class="message success" style="background-color: #d4edda; color: #155724; padding: 1rem; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 1rem;"><?php echo $createUserSuccess; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="dashboard-pengelola.php#buatPengguna" class="create-user-form">
                        <div class="form-row">
                            <div class="form-group"><label for="firstName">Nama Depan</label><input type="text" id="firstName" name="firstName" required></div>
                            <div class="form-group"><label for="lastName">Nama Belakang</label><input type="text" id="lastName" name="lastName"></div>
                        </div>
                        <div class="form-group"><label for="email">Email (untuk login)</label><input type="email" id="email" name="email" required></div>
                        <div class="form-group"><label for="phone">Nomor Telepon</label><input type="tel" id="phone" name="phone" required></div>
                        <div class="form-group"><label for="password">Password Sementara</label><input type="text" id="password" name="password" required></div>
                        <div class="form-group">
                            <label for="role">Peran (Role) Partner</label>
                            <select id="role" name="role" required>
                                <option value="" disabled selected>-- Pilih Peran Partner --</option>
                                <option value="porter">Porter</option>
                                <option value="guide">Guide</option>
                                <option value="ojek">Ojek</option>
                            </select>
                        </div>
                        <button type="submit" name="submit_create_user" class="btn-primary" style="width: 100%; padding: 12px;">Buat Akun Partner</button>
                    </form>
                </div>
            </section>
            
            <section id="sistem" class="section">
                <h2 class="table-title">Pengaturan Sistem</h2>
                <p style="text-align: center; color: #666;">Bagian ini akan berisi pengaturan umum sistem.</p>
            </section>

        </main>
    </div>

    <script src="scripts/dashboard-pengelola.js"></script>
</body>
</html>