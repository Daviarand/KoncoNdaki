<?php
session_start();
if (!isset($_SESSION['admin_name'])) {
    $_SESSION['admin_name'] = 'Budi Santoso';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pengelola Gunung</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-pengelola.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
                <li><a href="#" class="nav-link active">📊 Dashboard</a></li>
                <li><a href="#" class="nav-link">👥 Kelola Kuota</a></li>
                <li><a href="#" class="nav-link">🥾 Data Pendaki</a></li>
                <li><a href="#" class="nav-link">💰 Laporan Keuangan</a></li>
                <li><a href="#" class="nav-link">🤝 Partner Network</a></li>
                <li><a href="#" class="nav-link">📋 Pesanan Layanan</a></li>
                <li><a href="#" class="nav-link">⚙️ Sistem</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="header">
            <h1>Dashboard Operasional</h1>
            <div class="user-info">
                <span>Admin: <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                <span>🔔</span>
                <span>⚙️</span>
            </div>
        </header>

        <section class="stats-grid">
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
        </section>

        <section class="charts-section">
            <div class="chart-container">
                <h3 class="chart-title">Tren Jumlah Pendaki (6 Bulan Terakhir)</h3>
                <canvas id="trendChart" width="400" height="200"></canvas>
            </div>
            <div class="chart-container">
                <h3 class="chart-title">Distribusi Jalur Pendakian</h3>
                <canvas id="pathChart" width="200" height="200"></canvas>
            </div>
        </section>

        <section id="kelolaKuota" class="kuota-section">
            <h2 class="table-title">Kelola Kuota Jalur Pendakian</h2>

            <form class="kuota-form" onsubmit="tambahKuota(event)">
                <input type="text" id="inputJalur" placeholder="Nama Jalur" required>
                <input type="number" id="inputKuota" placeholder="Kuota Maksimal" required>
                <button type="submit" class="btn-primary">Tambah</button>
            </form>

            <table class="kuota-table">
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
                            <button class="btn btn-primary" onclick="updateKuota(this)">Simpan</button>
                            <button class="btn btn-danger" onclick="hapusKuota(this)">Hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Jalur Cemoro Kandang</td>
                        <td><input type="number" value="300"></td>
                        <td>190</td>
                        <td>
                            <button class="btn btn-primary" onclick="updateKuota(this)">Simpan</button>
                            <button class="btn btn-danger" onclick="hapusKuota(this)">Hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Jalur Kaliurang</td>
                        <td><input type="number" value="250"></td>
                        <td>120</td>
                        <td>
                            <button class="btn btn-primary" onclick="updateKuota(this)">Simpan</button>
                            <button class="btn btn-danger" onclick="hapusKuota(this)">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section id="dataPendaki" class="pendaki-section">
            <h2 class="table-title">Data Pendaki</h2>

            <table class="pendaki-table">
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
                        <td>
                            <button class="btn btn-danger" onclick="hapusPendaki(this)">Hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Siti Nurhaliza</td>
                        <td>Jalur Kaliurang</td>
                        <td>2024-06-26</td>
                        <td><span class="status-badge selesai">Selesai</span></td>
                        <td>
                            <button class="btn btn-danger" onclick="hapusPendaki(this)">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section id="laporanKeuangan" class="keuangan-section">
            <h2 class="table-title">Laporan Keuangan</h2>
            <button class="btn-primary" onclick="downloadKeuanganPDF()">Download PDF</button>

            <table class="keuangan-table" id="tableKeuangan">
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
                    <tr>
                        <td>2024-06-02</td>
                        <td>Penjualan Paket (Tiket + Add-ons) Gunung Sumbing</td>
                        <td>Rp 28.000.000</td>
                        <td>-</td>
                        <td>Rp 58.500.000</td>
                    </tr>
                    <tr>
                        <td>2024-06-05</td>
                        <td>Perawatan Jalur Pendakian</td>
                        <td>-</td>
                        <td>Rp 5.000.000</td>
                        <td>Rp 53.500.000</td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>
</div>

<script src="scripts/dashboard-pengelola.js"></script>
</body>
</html>
