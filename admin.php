<?php
session_start();
require_once 'config/database.php';

// Keamanan: Pastikan hanya Super Admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pageTitle = "Dashboard Super Admin";

// --- PENGAMBILAN DATA UNTUK DASHBOARD ---
$stmtKpi = $pdo->query("
    SELECT
        (SELECT COUNT(*) FROM users WHERE role = 'pendaki') as total_pendaki,
        (SELECT COUNT(*) FROM pemesanan WHERE status_pemesanan IN ('pending', 'menunggu pembayaran')) as total_booking_baru,
        (SELECT SUM(total_harga) FROM pemesanan WHERE status_pemesanan = 'complete' AND MONTH(tanggal_pemesanan) = MONTH(CURDATE())) as total_pendapatan_bulan_ini,
        (SELECT g.nama_gunung FROM pemesanan p JOIN tiket_gunung tg ON p.tiket_id = tg.id JOIN gunung g ON tg.id = g.id GROUP BY g.nama_gunung ORDER BY COUNT(p.id) DESC LIMIT 1) as gunung_populer
");
$kpi = $stmtKpi->fetch(PDO::FETCH_ASSOC);

$stmtPendapatanGunung = $pdo->query("
    SELECT g.nama_gunung, SUM(p.total_harga) as pendapatan
    FROM pemesanan p
    JOIN tiket_gunung tg ON p.tiket_id = tg.id
    JOIN gunung g ON tg.id = g.id
    WHERE p.status_pemesanan = 'complete' AND MONTH(p.tanggal_pemesanan) = MONTH(CURDATE())
    GROUP BY g.nama_gunung
    ORDER BY pendapatan DESC
");
$pendapatanPerGunung = $stmtPendapatanGunung->fetchAll(PDO::FETCH_ASSOC);
$labelsPendapatan = array_column($pendapatanPerGunung, 'nama_gunung');
$dataPendapatan = array_column($pendapatanPerGunung, 'pendapatan');

$stmtRole = $pdo->query("
    SELECT role, COUNT(*) as jumlah FROM users GROUP BY role
");
$roleData = $stmtRole->fetchAll(PDO::FETCH_KEY_PAIR);

// --- PENGAMBILAN DATA UNTUK LAPORAN & ANALISIS ---
$laporanKeuangan = [];
$filterGunung = $_GET['filter_gunung'] ?? 'semua';
$filterWaktu = $_GET['filter_waktu'] ?? 'bulanan';

$sqlLaporan = "
    SELECT
        p.kode_booking, g.nama_gunung, p.tanggal_pemesanan,
        p.subtotal_tiket, p.subtotal_layanan, p.total_harga
    FROM pemesanan p
    JOIN tiket_gunung tg ON p.tiket_id = tg.id
    JOIN gunung g ON tg.id = g.id
    WHERE p.status_pemesanan = 'complete'
";
$params = [];
if ($filterGunung !== 'semua') {
    $sqlLaporan .= " AND g.id = :gunung_id";
    $params[':gunung_id'] = $filterGunung;
}
if ($filterWaktu === 'mingguan') {
    $sqlLaporan .= " AND p.tanggal_pemesanan >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
} else {
    $sqlLaporan .= " AND MONTH(p.tanggal_pemesanan) = MONTH(CURDATE()) AND YEAR(p.tanggal_pemesanan) = YEAR(CURDATE())";
}
$sqlLaporan .= " ORDER BY p.tanggal_pemesanan DESC";
$stmtLaporan = $pdo->prepare($sqlLaporan);
$stmtLaporan->execute($params);
$laporanKeuangan = $stmtLaporan->fetchAll(PDO::FETCH_ASSOC);

$stmtAnalisisPendapatan = $pdo->query("
    SELECT SUM(subtotal_tiket) as total_tiket, SUM(subtotal_layanan) as total_layanan
    FROM pemesanan WHERE status_pemesanan = 'complete'
");
$analisisPendapatan = $stmtAnalisisPendapatan->fetch(PDO::FETCH_ASSOC);

$stmtOkupansi = $pdo->query("
    SELECT
        g.nama_gunung, g.kuota_pendaki_harian,
        COALESCE(SUM(p.jumlah_pendaki), 0) as total_pendaki,
        (COALESCE(SUM(p.jumlah_pendaki), 0) / (g.kuota_pendaki_harian * 30)) * 100 AS persentase_okupansi
    FROM gunung g
    LEFT JOIN tiket_gunung tg ON g.id = tg.id
    LEFT JOIN pemesanan p ON tg.id = p.tiket_id AND p.status_pemesanan = 'complete' AND MONTH(p.tanggal_pendakian) = MONTH(CURDATE())
    GROUP BY g.id ORDER BY persentase_okupansi DESC
");
$laporanOkupansi = $stmtOkupansi->fetchAll(PDO::FETCH_ASSOC);

// Logika form pembuatan akun pengelola
$firstName = ''; $lastName = ''; $email = ''; $phone = '';
$createUserError = ''; $createUserSuccess = '';
if (isset($_POST['submit_create_user'])) {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $gunung_id = $_POST['gunung_id'] ?? null;
    $role = 'pengelola_gunung';

    if (empty($firstName) || empty($email) || empty($password) || empty($gunung_id)) {
        $createUserError = 'Semua field wajib diisi, termasuk gunung yang akan dikelola.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $createUserError = 'Email sudah terdaftar. Gunakan email lain.';
            } else {
                $pdo->beginTransaction();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmtUser = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmtUser->execute([$firstName, $lastName, $email, $phone, $hashedPassword, $role]);
                $newUserId = $pdo->lastInsertId();
                $stmtGunung = $pdo->prepare("UPDATE gunung SET admin_id = ? WHERE id = ?");
                $stmtGunung->execute([$newUserId, $gunung_id]);
                $pdo->commit();
                $createUserSuccess = "Akun Pengelola Gunung untuk " . htmlspecialchars($firstName) . " berhasil dibuat.";
                $firstName = $lastName = $email = $phone = '';
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $createUserError = 'Gagal membuat akun: ' . $e->getMessage();
        }
    }
}
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
                    <li><a href="#" class="nav-link active" data-target="dashboard">📊 Dashboard</a></li>
                    <li><a href="#" class="nav-link" data-target="laporan">📈 Laporan & Analisis</a></li>
                    <li><a href="#" class="nav-link" data-target="buatPengguna">➕ Buat Akun Pengelola</a></li>
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

            <section id="dashboard" class="section active">
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
                    <form method="get" class="filter-form" onsubmit="event.preventDefault(); window.location.href = `admin.php?filter_gunung=${this.filter_gunung.value}&filter_waktu=${this.filter_waktu.value}#laporan`;">
                        <select name="filter_gunung">
                            <option value="semua">Semua Gunung</option>
                            <?php
                            $stmtGunungList = $pdo->query("SELECT id, nama_gunung FROM gunung");
                            foreach ($stmtGunungList->fetchAll() as $gunung) {
                                $selected = ($filterGunung == $gunung['id']) ? 'selected' : '';
                                echo "<option value='{$gunung['id']}' {$selected}>" . htmlspecialchars($gunung['nama_gunung']) . "</option>";
                            }
                            ?>
                        </select>
                        <select name="filter_waktu">
                            <option value="bulanan" <?php echo ($filterWaktu === 'bulanan') ? 'selected' : ''; ?>>Bulan Ini</option>
                            <option value="mingguan" <?php echo ($filterWaktu === 'mingguan') ? 'selected' : ''; ?>>7 Hari Terakhir</option>
                        </select>
                        <button type="submit">Filter</button>
                    </form>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Nama Gunung</th>
                                    <th>Tgl. Pesan</th>
                                    <th>Subtotal Tiket</th>
                                    <th>Subtotal Layanan</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
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
                        <h3 class="chart-title">Analisis Pendapatan: Tiket vs Layanan</h3>
                        <canvas id="analisisPendapatanChart"></canvas>
                    </div>
                    <div class="booking-table-wrapper">
                        <h3 class="chart-title">Tingkat Okupansi Kuota (Bulan Ini)</h3>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama Gunung</th>
                                    <th>Pendaki Aktual</th>
                                    <th>Okupansi</th>
                                </tr>
                            </thead>
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

            <section id="buatPengguna" class="section">
                <div class="section-header"><h2>Buat Akun Pengelola Gunung</h2></div>
                 <div class="form-container">
                    <?php if (!empty($createUserError)): ?>
                        <div class='message error'><?php echo $createUserError; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($createUserSuccess)): ?>
                        <div class='message success'><?php echo $createUserSuccess; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="admin.php#buatPengguna" id="create-user-form">
                        <div class="form-group">
                            <label for="firstName">Nama Depan</label>
                            <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Nama Belakang</label>
                            <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="gunung_id">Tugaskan ke Gunung</label>
                            <select id="gunung_id" name="gunung_id" required>
                                <option value="" disabled selected>-- Pilih Gunung --</option>
                                <?php
                                    $stmtGunungListForm = $pdo->prepare("SELECT id, nama_gunung FROM gunung WHERE admin_id IS NULL");
                                    $stmtGunungListForm->execute();
                                    foreach ($stmtGunungListForm->fetchAll() as $gunung) {
                                        echo "<option value='".htmlspecialchars($gunung['id'])."'>".htmlspecialchars($gunung['nama_gunung'])."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" name="submit_create_user" class="btn-primary">Buat Akun</button>
                    </form>
                 </div>
            </section>
        </main>
    </div>
    
    <script src="scripts/dashboard-nav.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#laporan' || new URLSearchParams(window.location.search).has('filter_gunung')) {
            const navLink = document.querySelector(`.nav-link[data-target='laporan']`);
            if(navLink) {
                document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
                navLink.classList.add('active');
                document.getElementById('laporan').classList.add('active');
            }
        }
        
        const labelsPendapatan = <?php echo json_encode($labelsPendapatan); ?>;
        const dataPendapatan = <?php echo json_encode($dataPendapatan); ?>;
        const dataRole = <?php echo json_encode($roleData); ?>;

        if (document.getElementById('pendapatanChart')) {
            const ctxPendapatan = document.getElementById('pendapatanChart').getContext('2d');
            new Chart(ctxPendapatan, {
                type: 'bar', data: { labels: labelsPendapatan, datasets: [{ label: 'Pendapatan (Rp)', data: dataPendapatan, backgroundColor: 'rgba(22, 163, 74, 0.7)' }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
        if (document.getElementById('roleChart')) {
            const ctxRole = document.getElementById('roleChart').getContext('2d');
            new Chart(ctxRole, {
                type: 'doughnut', data: { labels: Object.keys(dataRole), datasets: [{ label: 'Jumlah Pengguna', data: Object.values(dataRole), backgroundColor: ['#34d399', '#60a5fa', '#facc15', '#a78bfa', '#f87171', '#fb923c'] }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
        
        const totalTiket = <?php echo $analisisPendapatan['total_tiket'] ?? 0; ?>;
        const totalLayanan = <?php echo $analisisPendapatan['total_layanan'] ?? 0; ?>;
        if (document.getElementById('analisisPendapatanChart')) {
            const ctxAnalisis = document.getElementById('analisisPendapatanChart').getContext('2d');
            new Chart(ctxAnalisis, {
                type: 'doughnut', data: { labels: ['Pendapatan Tiket', 'Pendapatan Layanan'], datasets: [{ data: [totalTiket, totalLayanan], backgroundColor: ['#36A2EB', '#FFCE56'] }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
    });
    </script>
</body>
</html>