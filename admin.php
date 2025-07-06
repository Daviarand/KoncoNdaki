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

// 1. KPI Utama (Data Agregat)
$stmtKpi = $pdo->query("
    SELECT
        (SELECT COUNT(*) FROM users WHERE role = 'pendaki') as total_pendaki,
        (SELECT COUNT(*) FROM pemesanan WHERE status_pemesanan IN ('pending', 'menunggu pembayaran')) as total_booking_baru,
        (SELECT SUM(total_harga) FROM pemesanan WHERE status_pemesanan = 'complete' AND MONTH(tanggal_pemesanan) = MONTH(CURDATE())) as total_pendapatan_bulan_ini,
        (SELECT g.nama_gunung FROM pemesanan p JOIN tiket_gunung tg ON p.tiket_id = tg.id JOIN gunung g ON tg.id = g.id GROUP BY g.nama_gunung ORDER BY COUNT(p.id) DESC LIMIT 1) as gunung_populer
");
$kpi = $stmtKpi->fetch(PDO::FETCH_ASSOC);

// 2. Data untuk Grafik Performa Pendapatan Antar Gunung (Bulan Ini)
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

// 3. Data untuk Grafik Komposisi Role Pengguna
$stmtRole = $pdo->query("
    SELECT role, COUNT(*) as jumlah FROM users GROUP BY role
");
$roleData = $stmtRole->fetchAll(PDO::FETCH_KEY_PAIR);

// Logika untuk form pembuatan akun pengelola (tetap sama)
$firstName = ''; $lastName = ''; $email = ''; $phone = '';
$createUserError = ''; $createUserSuccess = '';
if (isset($_POST['submit_create_user'])) {
    // ... (Logika form Anda yang sudah ada di sini)
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
    <style>
        /* Tambahan CSS untuk dashboard super admin */
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 25px;
        }
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        @media (max-width: 992px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo"><h2>🏔️ Super Admin</h2><p>KoncoNdaki MIS</p></div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#" class="nav-link active" data-target="dashboard">📊 Dashboard</a></li>
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
                        <div class="stat-icon pendaki"><i class="fas fa-wallet"></i></div>
                        <div class="stat-number">Rp <?php echo number_format($kpi['total_pendapatan_bulan_ini'] ?? 0, 0, ',', '.'); ?></div>
                        <div class="stat-label">Pendapatan Bulan Ini</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon kuota"><i class="fas fa-book"></i></div>
                        <div class="stat-number"><?php echo $kpi['total_booking_baru'] ?? 0; ?></div>
                        <div class="stat-label">Booking Perlu Diproses</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon jalur"><i class="fas fa-users"></i></div>
                        <div class="stat-number"><?php echo $kpi['total_pendaki'] ?? 0; ?></div>
                        <div class="stat-label">Total Pendaki Terdaftar</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon pendapatan"><i class="fas fa-trophy"></i></div>
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

            <section id="buatPengguna" class="section">
                <div class="section-header"><h2>Buat Akun Pengelola Gunung</h2></div>
                <div class="form-container">
                    <?php if ($createUserError) echo "<div class='message error'>$createUserError</div>"; ?>
                    <?php if ($createUserSuccess) echo "<div class='message success'>$createUserSuccess</div>"; ?>
                    
                    <form method="POST" action="admin.php#buatPengguna" id="create-user-form">
                        </form>
                </div>
            </section>
        </main>
    </div>
    
    <script src="scripts/dashboard-nav.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari PHP
    const labelsPendapatan = <?php echo json_encode($labelsPendapatan); ?>;
    const dataPendapatan = <?php echo json_encode($dataPendapatan); ?>;
    const dataRole = <?php echo json_encode($roleData); ?>;

    // Grafik 1: Bar Chart Pendapatan per Gunung
    if (document.getElementById('pendapatanChart')) {
        const ctxPendapatan = document.getElementById('pendapatanChart').getContext('2d');
        new Chart(ctxPendapatan, {
            type: 'bar',
            data: {
                labels: labelsPendapatan,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dataPendapatan,
                    backgroundColor: 'rgba(22, 163, 74, 0.7)',
                    borderColor: 'rgba(22, 163, 74, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // <--- TAMBAHKAN BARIS INI
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); }
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                }
            }
        });
    }

    // Grafik 2: Pie Chart Komposisi Role
    if (document.getElementById('roleChart')) {
        const ctxRole = document.getElementById('roleChart').getContext('2d');
        new Chart(ctxRole, {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataRole),
                datasets: [{
                    label: 'Jumlah Pengguna',
                    data: Object.values(dataRole),
                    backgroundColor: [
                        '#34d399', '#60a5fa', '#facc15', '#a78bfa', '#f87171', '#fb923c'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // <--- TAMBAHKAN BARIS INI JUGA
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
});
</script>
</body>
</html>