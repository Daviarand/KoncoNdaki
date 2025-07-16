<?php
session_start();
require_once 'config/database.php';

// Keamanan: Memastikan hanya pengelola gunung yang sudah login dan memiliki ID gunung yang bisa mengakses. Ini sudah benar.
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pengelola_gunung' || !isset($_SESSION['gunung_id'])) {
    header('Location: login.php');
    exit;
}

// Mengambil data sesi yang sudah di-set saat login. Ini adalah cara yang tepat untuk membedakan dashboard.
$gunungDikelolaId = $_SESSION['gunung_id'];
$namaGunungDikelola = $_SESSION['nama_gunung'] ?? 'Gunung';
$pageTitle = "Dashboard Pengelola " . htmlspecialchars($namaGunungDikelola);

// Query untuk mengambil data booking. Klausa "WHERE p.tiket_id = :gunung_id" sudah benar,
// memastikan hanya booking untuk gunung ini yang ditampilkan.
$queryBooking = "
    SELECT 
        p.id, 
        p.kode_booking,
        p.tanggal_pendakian, 
        p.status_pemesanan,
        u.first_name, 
        u.last_name,
        GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS layanan_dipesan
    FROM 
        pemesanan p
    JOIN 
        users u ON p.user_id = u.id
    LEFT JOIN 
        pemesanan_layanan pl ON p.id = pl.pemesanan_id
    LEFT JOIN 
        layanan l ON pl.layanan_id = l.id
    WHERE 
        p.tiket_id = :gunung_id 
        AND p.status_pemesanan IN ('pending', 'in progress')
    GROUP BY
        p.id
    ORDER BY 
        FIELD(p.status_pemesanan, 'pending', 'in progress'), p.tanggal_pemesanan ASC
";

$stmtBooking = $pdo->prepare($queryBooking);
$stmtBooking->bindParam(':gunung_id', $gunungDikelolaId, PDO::PARAM_INT);
$stmtBooking->execute();
$bookings = $stmtBooking->fetchAll(PDO::FETCH_ASSOC);

// Query untuk mengambil data partner. Klausa "AND gunung_id = :gunung_id" sudah benar untuk model one-to-one.
// Ini akan mengambil user layanan yang kolom `gunung_id`-nya cocok dengan gunung yang dikelola.
$queryPartners = "
    SELECT id, first_name, last_name, role AS tipe_partner 
    FROM users 
    WHERE role IN ('ojek', 'porter', 'guide', 'basecamp') 
    AND gunung_id = :gunung_id
";
$stmtPartners = $pdo->prepare($queryPartners);
$stmtPartners->bindParam(':gunung_id', $gunungDikelolaId, PDO::PARAM_INT);
$stmtPartners->execute();
$allPartners = $stmtPartners->fetchAll(PDO::FETCH_ASSOC);

// Mengelompokkan partner berdasarkan tipe untuk dropdown notifikasi. Logika ini sudah benar.
$partnersByTipe = [
    'ojek' => [],
    'porter' => [],
    'guide' => [],
    'basecamp' => []
];
foreach ($allPartners as $partner) {
    $tipe = $partner['tipe_partner'];
    if (isset($partnersByTipe[$tipe])) {
        $partnersByTipe[$tipe][] = [
            'id' => $partner['id'],
            'nama' => trim($partner['first_name'] . ' ' . $partner['last_name'])
        ];
    }
}

// --- PERSIAPAN DATA UNTUK GRAFIK (Semua query sudah benar memfilter berdasarkan ID gunung) ---

// 1. Data untuk Pie Chart Partner Bulan Ini
$stmtPartner = $pdo->prepare("
    SELECT l.nama_layanan, COUNT(pl.id) AS total
    FROM pemesanan_layanan pl
    JOIN layanan l ON pl.layanan_id = l.id
    JOIN pemesanan p ON pl.pemesanan_id = p.id
    WHERE p.tiket_id = ? AND MONTH(p.tanggal_pemesanan) = MONTH(CURDATE()) AND YEAR(p.tanggal_pemesanan) = YEAR(CURDATE())
    GROUP BY l.nama_layanan
");
$stmtPartner->execute([$gunungDikelolaId]);
$partnerStats = $stmtPartner->fetchAll(PDO::FETCH_KEY_PAIR);

// 2. Data untuk Bar Chart dan Line Chart (12 bulan terakhir)
$stmtMonthly = $pdo->prepare("
    SELECT 
        DATE_FORMAT(tanggal_pendakian, '%b %Y') as bulan, 
        COUNT(id) as total_booking,
        SUM(jumlah_pendaki) as total_pendaki
    FROM pemesanan
    WHERE tiket_id = ? AND status_pemesanan = 'complete' AND tanggal_pendakian >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY YEAR(tanggal_pendakian), MONTH(tanggal_pendakian)
    ORDER BY MIN(tanggal_pendakian) ASC
");
$stmtMonthly->execute([$gunungDikelolaId]);
$monthlyStats = $stmtMonthly->fetchAll(PDO::FETCH_ASSOC);

$monthlyLabels = array_column($monthlyStats, 'bulan');
$monthlyBookingData = array_column($monthlyStats, 'total_booking');
$monthlyClimberData = array_column($monthlyStats, 'total_pendaki');
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
            <div class="logo">
                <h2>🏔️ <?php echo htmlspecialchars($_SESSION['nama_gunung']); ?></h2>
                <p>Dashboard Pengelola</p>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#" class="nav-link active" data-target="dashboard">📊 Dashboard</a></li>
                    <li><a href="#" class="nav-link" data-target="kelolaBooking">🗓️ Kelola Booking</a></li>
                    <li><a href="#" class="nav-link" data-target="kirimNotifikasi">🔔 Kirim Notifikasi</a></li>
                    <li><a href="#" class="nav-link" data-target="tambahPartner">👥 Tambah Partner</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <h1><?php echo $pageTitle; ?></h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
                    <a href="auth/logout.php" title="Logout" style="color: #333; text-decoration:none;"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <section id="dashboard" class="section active">
                <div class="section-header"><h2>Ringkasan <?php echo htmlspecialchars($_SESSION['nama_gunung']); ?></h2></div>
                <div class="charts-container">
                    <div class="chart-wrapper">
                        <h3>Pesanan Partner Bulan Ini</h3>
                        <canvas id="partnerPieChart"></canvas>
                    </div>
                    <div class="chart-wrapper">
                        <h3>Booking Selesai per Bulan</h3>
                        <canvas id="monthlyBarChart"></canvas>
                    </div>
                    <div class="chart-wrapper">
                        <h3>Jumlah Pendaki per Bulan</h3>
                        <canvas id="climbersLineChart"></canvas>
                    </div>
                </div>
            </section>

            <section id="kelolaBooking" class="section">
                <div class="section-header"><h2>Kelola Booking <?php echo htmlspecialchars($_SESSION['nama_gunung']); ?></h2></div>
                <div class="booking-table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kode Booking</th>
                                <th>Nama Pendaki</th>
                                <th>Layanan Dipesan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookings)): ?>
                                <tr><td colspan="5">Tidak ada booking yang memerlukan tindakan saat ini.</td></tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['kode_booking']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['layanan_dipesan'] ?? '-'); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $booking['status_pemesanan'])); ?>">
                                                <?php echo htmlspecialchars(ucfirst($booking['status_pemesanan'])); ?>
                                            </span>
                                        </td>
                                        <td class="actions-cell">
                                            <?php if ($booking['status_pemesanan'] == 'pending'): ?>
                                                <form action="update_status.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="pemesanan_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="new_status" value="in progress">
                                                    <button type="submit" class="btn-action btn-verify" title="Verifikasi">Verifikasi</button>
                                                </form>
                                                <form action="update_status.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="pemesanan_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="new_status" value="rejected">
                                                    <button type="submit" class="btn-action btn-reject" title="Tolak" onclick="return confirm('Yakin tolak pesanan ini?');">Tolak</button>
                                                </form>

                                            <?php elseif ($booking['status_pemesanan'] == 'in progress'): ?>
                                                <?php if (!empty($booking['layanan_dipesan'])): ?>
                                                    <button type="button" class="btn-action btn-notify btn-kirim-notif" 
                                                            title="Kirim Notifikasi ke Partner"
                                                            data-kode-booking="<?php echo htmlspecialchars($booking['kode_booking']); ?>"
                                                            data-nama-pendaki="<?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?>"
                                                            data-layanan="<?php echo htmlspecialchars($booking['layanan_dipesan']); ?>">
                                                        <i class="fas fa-bell"></i> Kirim Notif
                                                    </button>
                                                <?php endif; ?>
                                                <form action="update_status.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="pemesanan_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="new_status" value="complete">
                                                    <button type="submit" class="btn-action btn-complete" title="Selesaikan Pesanan">
                                                        <i class="fas fa-flag-checkered"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <section id="kirimNotifikasi" class="section">
                <div class="section-header"><h2>Kirim Notifikasi ke Partner</h2></div>
                <div class="form-wrapper">
                    <form id="notifForm" action="kirim_notifikasi_proses.php" method="POST">
                        <div class="form-group">
                            <label for="tipePartner">Tipe Partner:</label>
                            <select id="tipePartner" name="tipe_partner" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="ojek">🛵 Ojek</option>
                                <option value="porter">🎒 Porter</option>
                                <option value="guide">👨‍💼 Guide</option>
                                <option value="basecamp">⛺ Basecamp</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pilihPartner">Pilih Partner Spesifik:</label>
                            <select id="pilihPartner" name="partner_id" required disabled>
                                <option value="">-- Pilih Tipe Dulu --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pesanNotifikasi">Pesan Notifikasi:</label>
                            <textarea id="pesanNotifikasi" name="pesan" rows="5" required placeholder="Contoh: Tolong jemput 2 pendaki di Basecamp Bromo besok jam 5 pagi. Kode Booking: KNCD-XXXXXX"></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Kirim Notifikasi</button>
                        </div>
                    </form>
                </div>
            </section>
            
            <section id="tambahPartner" class="section">
                <div class="section-header"><h2>Buat Akun Partner</h2></div>
                <div class="form-wrapper">
                    <form id="addPartnerForm" action="tambah_partner_proses.php" method="POST">
                        <div class="form-group">
                            <label for="firstName">Nama Depan</label>
                            <input type="text" id="firstName" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Nama Belakang</label>
                            <input type="text" id="lastName" name="last_name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="addTipePartner">Jenis Partner</label>
                            <select id="addTipePartner" name="tipe_partner" required>
                                <option value="">-- Pilih Jenis Layanan --</option>
                                <option value="ojek">Ojek</option>
                                <option value="porter">Porter</option>
                                <option value="guide">Guide</option>
                                <option value="basecamp">Basecamp</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ditugaskan ke Gunung</label>
                            <input type="text" value="<?php echo htmlspecialchars($_SESSION['nama_gunung']); ?>" disabled>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Buat Akun</button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
    <script>
        const allPartnersData = <?php echo json_encode($partnersByTipe); ?>;
    </script>
    <script src="scripts/dashboard-nav.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Data dari PHP untuk Charts ---
        const partnerLabels = <?php echo json_encode(array_keys($partnerStats)); ?>;
        const partnerData = <?php echo json_encode(array_values($partnerStats)); ?>;
        const monthlyLabels = <?php echo json_encode($monthlyLabels); ?>;
        const monthlyBookingData = <?php echo json_encode($monthlyBookingData); ?>;
        const monthlyClimberData = <?php echo json_encode($monthlyClimberData); ?>;
        const chartColors = ['#22c55e', '#f97316', '#3b82f6', '#8b5cf6', '#ef4444', '#f59e0b'];

        // --- Inisialisasi Grafik (Charts.js) ---
        const partnerPieCtx = document.getElementById('partnerPieChart');
        if (partnerPieCtx && partnerData.length > 0) {
            new Chart(partnerPieCtx, {
                type: 'pie',
                data: {
                    labels: partnerLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                    datasets: [{
                        label: 'Jumlah Pesanan',
                        data: partnerData,
                        backgroundColor: chartColors,
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'top' } } }
            });
        } else if (partnerPieCtx) {
             partnerPieCtx.parentNode.innerHTML = '<div style="text-align:center; padding: 20px;">Tidak ada data pesanan partner bulan ini.</div>';
        }

        const monthlyBarCtx = document.getElementById('monthlyBarChart');
        if (monthlyBarCtx && monthlyBookingData.length > 0) {
            new Chart(monthlyBarCtx, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Booking Selesai',
                        data: monthlyBookingData,
                        backgroundColor: '#22c55e',
                        borderColor: '#16a34a',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
            });
        } else if (monthlyBarCtx) {
             monthlyBarCtx.parentNode.innerHTML = '<div style="text-align:center; padding: 20px;">Belum ada data booking selesai.</div>';
        }

        const climbersLineCtx = document.getElementById('climbersLineChart');
        if (climbersLineCtx && monthlyClimberData.length > 0) {
            new Chart(climbersLineCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Jumlah Pendaki',
                        data: monthlyClimberData,
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: '#3b82f6',
                        tension: 0.3
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
            });
        } else if (climbersLineCtx) {
            climbersLineCtx.parentNode.innerHTML = '<div style="text-align:center; padding: 20px;">Belum ada data jumlah pendaki.</div>';
        }
    });
    </script>
</body>
</html>