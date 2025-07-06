<?php
session_start();
require_once 'config/database.php';

// Keamanan: Pastikan hanya Super Admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pageTitle = "Dashboard Super Admin";
$actionMessage = '';
$createUserError = '';

// --- LOGIKA AKSI POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aksi Menonaktifkan Akun
    if (isset($_POST['deactivate_user'])) {
        $user_id = intval($_POST['user_id']);
        try {
            $stmtRole = $pdo->prepare("SELECT role FROM users WHERE id = ?");
            $stmtRole->execute([$user_id]);
            $userToDelete = $stmtRole->fetch();

            $pdo->beginTransaction();
            if ($userToDelete && $userToDelete['role'] === 'pengelola_gunung') {
                 $pdo->prepare("UPDATE gunung SET admin_id = NULL WHERE admin_id = ?")->execute([$user_id]);
            }
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
            $pdo->commit();
            $_SESSION['actionMessage'] = "Akun pengguna berhasil dinonaktifkan.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['actionMessage'] = "Gagal menonaktifkan akun: " . $e->getMessage();
        }
        header("Location: admin.php#manajemen");
        exit;
    }

    // Aksi Membuat Akun Pengelola Baru
    if (isset($_POST['submit_create_user'])) {
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $gunung_id = $_POST['gunung_id'] ?? null;
        if (empty($firstName) || empty($email) || empty($password) || empty($gunung_id)) {
            $createUserError = 'Semua field wajib diisi.';
        } else {
             try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $createUserError = 'Email sudah terdaftar. Gunakan email lain.';
                } else {
                    $pdo->beginTransaction();
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmtUser = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, 'pengelola_gunung', NOW())");
                    $stmtUser->execute([$firstName, $lastName, $email, $phone, $hashedPassword]);
                    $newUserId = $pdo->lastInsertId();
                    $stmtGunung = $pdo->prepare("UPDATE gunung SET admin_id = ? WHERE id = ?");
                    $stmtGunung->execute([$newUserId, $gunung_id]);
                    $pdo->commit();
                    $_SESSION['actionMessage'] = "Akun Pengelola Gunung untuk " . htmlspecialchars($firstName) . " berhasil dibuat.";
                    header("Location: admin.php#manajemen");
                    exit;
                }
            } catch (PDOException $e) {
                $pdo->rollBack();
                $createUserError = 'Gagal membuat akun: ' . $e->getMessage();
            }
        }
    }
}

if (isset($_SESSION['actionMessage'])) {
    $actionMessage = $_SESSION['actionMessage'];
    unset($_SESSION['actionMessage']);
}


// --- PENGAMBILAN DATA (SEMUA BAGIAN) ---
// Data Dashboard
$stmtKpi = $pdo->query("SELECT (SELECT COUNT(*) FROM users WHERE role = 'pendaki') as total_pendaki, (SELECT COUNT(*) FROM pemesanan WHERE status_pemesanan IN ('pending', 'menunggu pembayaran')) as total_booking_baru, (SELECT SUM(total_harga) FROM pemesanan WHERE status_pemesanan = 'complete' AND MONTH(tanggal_pemesanan) = MONTH(CURDATE())) as total_pendapatan_bulan_ini, (SELECT g.nama_gunung FROM pemesanan p JOIN tiket_gunung tg ON p.tiket_id = tg.id JOIN gunung g ON tg.id = g.id GROUP BY g.nama_gunung ORDER BY COUNT(p.id) DESC LIMIT 1) as gunung_populer");
$kpi = $stmtKpi->fetch(PDO::FETCH_ASSOC);
$stmtPendapatanGunung = $pdo->query("SELECT g.nama_gunung, SUM(p.total_harga) as pendapatan FROM pemesanan p JOIN tiket_gunung tg ON p.tiket_id = tg.id JOIN gunung g ON tg.id = g.id WHERE p.status_pemesanan = 'complete' AND MONTH(p.tanggal_pemesanan) = MONTH(CURDATE()) GROUP BY g.nama_gunung ORDER BY pendapatan DESC");
$pendapatanPerGunung = $stmtPendapatanGunung->fetchAll(PDO::FETCH_ASSOC);
$labelsPendapatan = array_column($pendapatanPerGunung, 'nama_gunung');
$dataPendapatan = array_column($pendapatanPerGunung, 'pendapatan');
$stmtRole = $pdo->query("SELECT role, COUNT(*) as jumlah FROM users GROUP BY role");
$roleData = $stmtRole->fetchAll(PDO::FETCH_KEY_PAIR);

// Data Laporan
$filterGunung = $_GET['filter_gunung'] ?? 'semua';
$filterWaktu = $_GET['filter_waktu'] ?? 'bulanan';
$baseSqlLaporan = " FROM pemesanan p JOIN tiket_gunung tg ON p.tiket_id = tg.id JOIN gunung g ON tg.id = g.id WHERE p.status_pemesanan = 'complete'";
$params = [];
$filterSql = "";
if ($filterGunung !== 'semua') {
    $filterSql .= " AND g.id = :gunung_id";
    $params[':gunung_id'] = $filterGunung;
}
if ($filterWaktu === 'mingguan') {
    $filterSql .= " AND p.tanggal_pemesanan >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
} else {
    $filterSql .= " AND MONTH(p.tanggal_pemesanan) = MONTH(CURDATE()) AND YEAR(p.tanggal_pemesanan) = YEAR(CURDATE())";
}
$sqlLaporan = "SELECT p.kode_booking, g.nama_gunung, p.tanggal_pemesanan, p.subtotal_tiket, p.subtotal_layanan, p.total_harga " . $baseSqlLaporan . $filterSql . " ORDER BY p.tanggal_pemesanan DESC";
$stmtLaporan = $pdo->prepare($sqlLaporan);
$stmtLaporan->execute($params);
$laporanKeuangan = $stmtLaporan->fetchAll(PDO::FETCH_ASSOC);
$sqlAnalisis = "SELECT SUM(p.subtotal_tiket) as total_tiket, SUM(p.subtotal_layanan) as total_layanan " . $baseSqlLaporan . $filterSql;
$stmtAnalisisPendapatan = $pdo->prepare($sqlAnalisis);
$stmtAnalisisPendapatan->execute($params);
$analisisPendapatan = $stmtAnalisisPendapatan->fetch(PDO::FETCH_ASSOC);
$stmtOkupansi = $pdo->query("SELECT g.nama_gunung, g.kuota_pendaki_harian, COALESCE(SUM(p.jumlah_pendaki), 0) as total_pendaki, (COALESCE(SUM(p.jumlah_pendaki), 0) / (g.kuota_pendaki_harian * 30)) * 100 AS persentase_okupansi FROM gunung g LEFT JOIN tiket_gunung tg ON g.id = tg.id LEFT JOIN pemesanan p ON tg.id = p.tiket_id AND p.status_pemesanan = 'complete' AND MONTH(p.tanggal_pendakian) = MONTH(CURDATE()) GROUP BY g.id ORDER BY persentase_okupansi DESC");
$laporanOkupansi = $stmtOkupansi->fetchAll(PDO::FETCH_ASSOC);

// Data Manajemen Sistem
$stmtPengelola = $pdo->query("SELECT u.id, u.first_name, u.last_name, u.email, u.phone, g.nama_gunung FROM users u LEFT JOIN gunung g ON u.id = g.admin_id WHERE u.role = 'pengelola_gunung' ORDER BY u.created_at DESC");
$daftarPengelola = $stmtPengelola->fetchAll(PDO::FETCH_ASSOC);
$stmtPendaki = $pdo->query("SELECT id, first_name, last_name, email, phone FROM users WHERE role = 'pendaki' ORDER BY created_at DESC");
$daftarPendaki = $stmtPendaki->fetchAll(PDO::FETCH_ASSOC);
$stmtDaftarGunung = $pdo->query("SELECT * FROM gunung ORDER BY nama_gunung ASC");
$daftarGunung = $stmtDaftarGunung->fetchAll(PDO::FETCH_ASSOC);
$stmtDaftarLayanan = $pdo->query("SELECT * FROM layanan ORDER BY nama_layanan ASC");
$daftarLayanan = $stmtDaftarLayanan->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles/dashboard-pengelola.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo"><h2>🏔️ Super Admin</h2><p>KoncoNdaki MIS</p></div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#dashboard" class="nav-link" data-target="dashboard">📊 Dashboard</a></li>
                    <li><a href="#laporan" class="nav-link" data-target="laporan">📈 Laporan & Analisis</a></li>
                    <li><a href="#manajemen" class="nav-link" data-target="manajemen">⚙️ Manajemen Sistem</a></li>
                    <li><a href="#tambahPengelola" class="nav-link" data-target="tambahPengelola">➕ Tambah Pengelola</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="header">
                <h1><?php echo $pageTitle; ?></h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
                    <a href="auth/logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <section id="dashboard" class="section">
                <div class="section-header"><h2>Ringkasan Seluruh Gunung</h2></div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                        <div class="stat-number">Rp <?php echo number_format($kpi['total_pendapatan_bulan_ini'] ?? 0, 0, ',', '.'); ?></div>
                        <div class="stat-label">Pendapatan Bulan Ini</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-book"></i></div>
                        <div class="stat-number"><?php echo $kpi['total_booking_baru'] ?? 0; ?></div>
                        <div class="stat-label">Booking Perlu Diproses</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-number"><?php echo $kpi['total_pendaki'] ?? 0; ?></div>
                        <div class="stat-label">Total Pendaki Terdaftar</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                        <div class="stat-number"><?php echo htmlspecialchars($kpi['gunung_populer'] ?? '-'); ?></div>
                        <div class="stat-label">Gunung Terpopuler</div>
                    </div>
                </div>
                <div class="charts-section">
                    <div class="chart-container">
                        <h3 class="chart-title">Performa Pendapatan Antar Gunung (Bulan Ini)</h3>
                        <canvas id="pendapatanChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3 class="chart-title">Komposisi Peran Pengguna</h3>
                        <canvas id="roleChart"></canvas>
                    </div>
                </div>
            </section>

            <section id="laporan" class="section">
                <div class="section-header"><h2>Laporan & Analisis Lintas Gunung</h2></div>
                <div class="report-section">
                    <h3>Laporan Keuangan Konsolidasi</h3>
                    <form method="get" class="filter-form" action="admin.php#laporan">
                        <select name="filter_gunung" onchange="this.form.submit()">
                            <option value="semua">Semua Gunung</option>
                            <?php
                            $stmtGunungList = $pdo->query("SELECT id, nama_gunung FROM gunung");
                            foreach ($stmtGunungList->fetchAll() as $gunung) {
                                $selected = ($filterGunung == $gunung['id']) ? 'selected' : '';
                                echo "<option value='{$gunung['id']}' {$selected}>" . htmlspecialchars($gunung['nama_gunung']) . "</option>";
                            }
                            ?>
                        </select>
                        <select name="filter_waktu" onchange="this.form.submit()">
                            <option value="bulanan" <?php echo ($filterWaktu === 'bulanan') ? 'selected' : ''; ?>>Bulan Ini</option>
                            <option value="mingguan" <?php echo ($filterWaktu === 'mingguan') ? 'selected' : ''; ?>>7 Hari Terakhir</option>
                        </select>
                    </form>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>Kode Booking</th><th>Nama Gunung</th><th>Tgl. Pesan</th><th>Subtotal Tiket</th><th>Subtotal Layanan</th><th>Total Harga</th></tr></thead>
                            <tbody>
                                <?php if (empty($laporanKeuangan)): ?>
                                    <tr><td colspan="6" style="text-align:center;">Tidak ada data untuk filter yang dipilih.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($laporanKeuangan as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['kode_booking']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_gunung']); ?></td>
                                        <td><?php echo date('d M Y', strtotime($row['tanggal_pemesanan'])); ?></td>
                                        <td>Rp <?php echo number_format($row['subtotal_tiket'], 0, ',', '.'); ?></td>
                                        <td>Rp <?php echo number_format($row['subtotal_layanan'], 0, ',', '.'); ?></td>
                                        <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="charts-section">
                    <div class="chart-container">
                        <h3 class="chart-title">Analisis Pendapatan (Sesuai Filter)</h3>
                        <canvas id="analisisPendapatanChart"></canvas>
                    </div>
                    <div class="booking-table-wrapper">
                        <h3 class="chart-title">Tingkat Okupansi Kuota (Bulan Ini)</h3>
                        <table class="data-table">
                            <thead><tr><th>Nama Gunung</th><th>Pendaki Aktual</th><th>Okupansi</th></tr></thead>
                            <tbody>
                                <?php foreach ($laporanOkupansi as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nama_gunung']); ?></td>
                                    <td><?php echo number_format($row['total_pendaki'], 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($row['persentase_okupansi'], 2, ',', '.'); ?>%</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            
            <section id="manajemen" class="section">
                <div class="section-header"><h2>Manajemen Sistem</h2></div>
                <?php if ($actionMessage): ?>
                    <div class="message success" style="margin-bottom: 1rem;"><?php echo htmlspecialchars($actionMessage); ?></div>
                <?php endif; ?>

                <div class="report-section">
                    <h3>Daftar Akun Pengelola Gunung</h3>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>Nama</th><th>Email & Telp</th><th>Mengelola Gunung</th><th>Aksi</th></tr></thead>
                            <tbody>
                                <?php if (empty($daftarPengelola)): ?>
                                    <tr><td colspan="4" style="text-align: center;">Belum ada akun pengelola.</td></tr>
                                <?php else: ?>
                                <?php foreach ($daftarPengelola as $pengelola): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pengelola['first_name'] . ' ' . $pengelola['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($pengelola['email']); ?><br><small><?php echo htmlspecialchars($pengelola['phone']); ?></small></td>
                                    <td><?php echo htmlspecialchars($pengelola['nama_gunung'] ?? '<i>Tidak ditugaskan</i>'); ?></td>
                                    <td class="actions-cell">
                                        <form method="POST" action="admin.php#manajemen" onsubmit="return confirm('Anda yakin ingin menonaktifkan akun ini?');" style="display:inline;">
                                            <input type="hidden" name="deactivate_user" value="1">
                                            <input type="hidden" name="user_id" value="<?php echo $pengelola['id']; ?>">
                                            <button type="submit" class="btn-action btn-reject">Nonaktifkan</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="report-section">
                    <h3>Daftar User Pendaki</h3>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Aksi</th></tr></thead>
                            <tbody>
                                <?php if (empty($daftarPendaki)): ?>
                                    <tr><td colspan="4" style="text-align: center;">Belum ada user pendaki.</td></tr>
                                <?php else: ?>
                                <?php foreach ($daftarPendaki as $pendaki): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pendaki['first_name'] . ' ' . $pendaki['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($pendaki['email']); ?></td>
                                    <td><?php echo htmlspecialchars($pendaki['phone']); ?></td>
                                    <td class="actions-cell">
                                        <form method="POST" action="admin.php#manajemen" onsubmit="return confirm('Anda yakin ingin menonaktifkan akun ini?');" style="display:inline;">
                                            <input type="hidden" name="deactivate_user" value="1">
                                            <input type="hidden" name="user_id" value="<?php echo $pendaki['id']; ?>">
                                            <button type="submit" class="btn-action btn-reject">Nonaktifkan</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="report-section">
                    <h3>Daftar Data Gunung</h3>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                           <thead><tr><th>Nama Gunung</th><th>Lokasi</th><th>Kuota Harian</th><th>Status</th><th>Aksi</th></tr></thead>
                           <tbody><?php foreach ($daftarGunung as $gunung): ?><tr><td><?php echo htmlspecialchars($gunung['nama_gunung']); ?></td><td><?php echo htmlspecialchars($gunung['lokasi']); ?></td><td><?php echo number_format($gunung['kuota_pendaki_harian']); ?></td><td><span class="status-badge <?php echo $gunung['status'] === 'buka' ? 'complete' : 'rejected'; ?>"><?php echo ucfirst($gunung['status']); ?></span></td><td class="actions-cell"><button class="btn-action btn-verify">Edit</button></td></tr><?php endforeach; ?></tbody>
                        </table>
                    </div>
                </div>

                <div class="report-section">
                    <h3>Daftar Layanan Tambahan</h3>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>Nama Layanan</th><th>Harga</th><th>Satuan</th><th>Aksi</th></tr></thead>
                            <tbody><?php foreach ($daftarLayanan as $layanan): ?><tr><td><?php echo ucfirst(htmlspecialchars($layanan['nama_layanan'])); ?></td><td>Rp <?php echo number_format($layanan['harga_layanan'], 0, ',', '.'); ?></td><td><?php echo htmlspecialchars($layanan['satuan']); ?></td><td class="actions-cell"><button class="btn-action btn-verify">Edit</button></td></tr><?php endforeach; ?></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="tambahPengelola" class="section">
                 <div class="section-header"><h2>Tambah Pengelola Baru</h2></div>
                 <div class="form-container">
                    <?php if ($createUserError): ?>
                        <div class='message error'><?php echo $createUserError; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="admin.php#tambahPengelola" id="create-user-form">
                        <div class="form-group"><label for="firstName">Nama Depan</label><input type="text" id="firstName" name="firstName" required></div>
                        <div class="form-group"><label for="lastName">Nama Belakang</label><input type="text" id="lastName" name="lastName"></div>
                        <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
                        <div class="form-group"><label for="phone">Telepon</label><input type="tel" id="phone" name="phone" required></div>
                        <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" required></div>
                        <div class="form-group"><label for="gunung_id">Tugaskan ke Gunung</label><select id="gunung_id" name="gunung_id" required><option value="" disabled selected>-- Pilih Gunung --</option><?php $stmtGunungListForm = $pdo->query("SELECT id, nama_gunung FROM gunung WHERE admin_id IS NULL"); foreach ($stmtGunungListForm->fetchAll() as $gunung) { echo "<option value='".htmlspecialchars($gunung['id'])."'>".htmlspecialchars($gunung['nama_gunung'])."</option>"; } ?></select></div>
                        <button type="submit" name="submit_create_user" class="btn-primary">Buat Akun</button>
                    </form>
                 </div>
            </section>
        </main>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.section');
        function activateTab(targetId) {
            sections.forEach(section => {
                section.classList.remove('active');
            });
            navLinks.forEach(link => {
                link.classList.remove('active');
            });
            const activeSection = document.getElementById(targetId);
            const activeLink = document.querySelector(`.nav-link[data-target='${targetId}']`);
            if (activeSection && activeLink) {
                activeSection.classList.add('active');
                activeLink.classList.add('active');
            }
        }
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.dataset.target;
                window.history.pushState({path: '#' + targetId}, '', '#' + targetId);
                activateTab(targetId);
            });
        });
        let currentHash = window.location.hash.substring(1);
        if (currentHash && document.getElementById(currentHash)) {
            activateTab(currentHash);
        } else {
            activateTab('dashboard'); 
        }

        // --- Inisialisasi Chart.js ---
        if (document.getElementById('pendapatanChart')) {
            new Chart(document.getElementById('pendapatanChart').getContext('2d'), {
                type: 'bar', data: { labels: <?php echo json_encode($labelsPendapatan); ?>, datasets: [{ label: 'Pendapatan (Rp)', data: <?php echo json_encode($dataPendapatan); ?>, backgroundColor: 'rgba(22, 163, 74, 0.7)' }] }, options: { responsive: true, maintainAspectRatio: false }
            });
        }
        if (document.getElementById('roleChart')) {
            new Chart(document.getElementById('roleChart').getContext('2d'), {
                type: 'doughnut', data: { labels: <?php echo json_encode(array_keys($roleData)); ?>, datasets: [{ label: 'Jumlah Pengguna', data: <?php echo json_encode(array_values($roleData)); ?>, backgroundColor: ['#34d399', '#60a5fa', '#facc15', '#a78bfa', '#f87171', '#fb923c'] }] }, options: { responsive: true, maintainAspectRatio: false }
            });
        }
        if (document.getElementById('analisisPendapatanChart')) {
            new Chart(document.getElementById('analisisPendapatanChart').getContext('2d'), {
                type: 'doughnut', data: { labels: ['Pendapatan Tiket', 'Pendapatan Layanan'], datasets: [{ data: [<?php echo $analisisPendapatan['total_tiket'] ?? 0; ?>, <?php echo $analisisPendapatan['total_layanan'] ?? 0; ?>], backgroundColor: ['#36A2EB', '#FFCE56'] }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { tooltip: { callbacks: { label: function(context) { return context.label + ': ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.raw); } } } } }
            });
        }
    });
    </script>
</body>
</html>