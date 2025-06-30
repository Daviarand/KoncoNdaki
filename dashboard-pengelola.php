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
                <li><a href="#" class="nav-link" data-target="partnerNetwork">🤝 Partner Network</a></li>
                <li><a href="#" class="nav-link" data-target="pesananLayanan">📋 Pesanan Layanan</a></li>
                <li><a href="#" class="nav-link" data-target="kelolaPartner">👥 Kelola Partner</a></li>
                <li><a href="#" class="nav-link" data-target="sistem">⚙️ Sistem</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="header">
            <h1>Dashboard Operasional</h1>
            <div class="user-info">
                <span>Admin: Budi Santoso</span>
                <span>🔔</span>
                <span>⚙️</span>
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
                         <tr data-booking-id="BOOK-001">
                             <td>BOOK-001</td>
                             <td>John Doe</td>
                             <td>Gunung Semeru</td>
                             <td>2024-01-16</td>
                             <td><span class="status-badge pending">pending</span></td>
                             <td class="partner-icons">
                                 <i class="fas fa-map-marked-alt" title="Guide"></i>
                                 <i class="fas fa-box" title="Porter"></i>
                                 <i class="fas fa-motorcycle" title="Ojek"></i>
                             </td>
                             <td class="action-buttons">
                                 <button class="btn-primary btn-verify" onclick="verifyBookingPayment(this)">Verifikasi Pembayaran</button>
                                 <button class="btn-primary btn-assign" onclick="assignBookingPartner(this)" style="display:none;">Tugaskan Partner</button>
                                 <button class="btn-danger" onclick="cancelBooking(this)">Batalkan</button>
                             </td>
                         </tr>
                         <tr data-booking-id="BOOK-002">
                             <td>BOOK-002</td>
                             <td>Jane Smith</td>
                             <td>Gunung Bromo</td>
                             <td>2024-01-15</td>
                             <td><span class="status-badge in-progress">in-progress</span></td>
                             <td class="partner-icons">
                                <i class="fas fa-map-marked-alt" title="Guide"></i>
                                <i class="fas fa-motorcycle" title="Ojek"></i>
                             </td>
                             <td class="action-buttons">
                                <button class="btn-primary btn-verify" onclick="verifyBookingPayment(this)" style="display:none;">Verifikasi Pembayaran</button>
                                <button class="btn-primary btn-assign" onclick="assignBookingPartner(this)">Tugaskan Partner</button>
                                <button class="btn-danger" onclick="cancelBooking(this)">Batalkan</button>
                             </td>
                         </tr>
                         <tr data-booking-id="BOOK-003">
                             <td>BOOK-003</td>
                             <td>Peter Jones</td>
                             <td>Gunung Merapi</td>
                             <td>2024-01-17</td>
                             <td><span class="status-badge confirmed">confirmed</span></td>
                             <td class="partner-icons">
                                <i class="fas fa-map-marked-alt" title="Guide"></i>
                             </td>
                             <td class="action-buttons">
                                <button class="btn-primary btn-verify" onclick="verifyBookingPayment(this)" style="display:none;">Verifikasi Pembayaran</button>
                                <button class="btn-primary btn-assign" onclick="assignBookingPartner(this)" style="display:none;">Tugaskan Partner</button>
                                <button class="btn-danger" onclick="cancelBooking(this)">Batalkan</button>
                             </td>
                         </tr>
                         <tr data-booking-id="BOOK-004">
                             <td>BOOK-004</td>
                             <td>Anna Williams</td>
                             <td>Gunung Lawu</td>
                             <td>2024-01-18</td>
                             <td><span class="status-badge cancelled">cancelled</span></td>
                             <td class="partner-icons">
                             </td>
                             <td class="action-buttons">
                                <button class="btn-primary btn-verify" onclick="verifyBookingPayment(this)" style="display:none;">Verifikasi Pembayaran</button>
                                <button class="btn-primary btn-assign" onclick="assignBookingPartner(this)" style="display:none;">Tugaskan Partner</button>
                                <button class="btn-danger" onclick="cancelBooking(this)" style="display:none;">Batalkan</button>
                             </td>
                         </tr>
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
                    <tr>
                        <td>Jalur Cemoro Kandang</td>
                        <td><input type="number" value="300"></td>
                        <td>190</td>
                        <td>
                            <button class="btn-primary" onclick="updateKuota(this)">Simpan</button>
                            <button class="btn-danger" onclick="hapusKuota(this)">Hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Jalur Kaliurang</td>
                        <td><input type="number" value="250"></td>
                        <td>120</td>
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
                        <td>
                            <button class="btn-danger" onclick="hapusPendaki(this)">Hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Siti Nurhaliza</td>
                        <td>Jalur Kaliurang</td>
                        <td>2024-06-26</td>
                        <td><span class="status-badge selesai">Selesai</span></td>
                        <td>
                            <button class="btn-danger" onclick="hapusPendaki(this)">Hapus</button>
                        </td>
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

        <section id="partnerNetwork" class="section">
            <h2 class="table-title">Partner Network - Kirim Pesan & Notifikasi</h2>

            <div class="partner-grid">
                <div class="partner-card" onclick="selectPartner('ojek', this)">
                    <div class="partner-icon">🏍️</div>
                    <div class="partner-name">Ojek</div>
                    <div class="partner-count">24 Partner Aktif</div>
                </div>
                <div class="partner-card" onclick="selectPartner('guide', this)">
                    <div class="partner-icon">🧭</div>
                    <div class="partner-name">Guide</div>
                    <div class="partner-count">18 Partner Aktif</div>
                </div>
                <div class="partner-card" onclick="selectPartner('porter', this)">
                    <div class="partner-icon">🎒</div>
                    <div class="partner-name">Porter</div>
                    <div class="partner-count">32 Partner Aktif</div>
                </div>
                <div class="partner-card" onclick="selectPartner('basecamp', this)">
                    <div class="partner-icon">🏠</div>
                    <div class="partner-name">Basecamp</div>
                    <div class="partner-count">8 Partner Aktif</div>
                </div>
            </div>

            <div class="message-form">
                <form id="messageForm" onsubmit="kirimPesan(event)">
                    <input type="hidden" id="currentBookingId" value="">

                    <div id="partnerSelectionGroups">
                        <div class="partner-selection-group" data-partner-index="0">
                            <div class="form-group">
                                <label for="partnerType_0">Tipe Partner 1:</label>
                                <select id="partnerType_0" class="partner-type-select" onchange="populateSpecificPartners(this)">
                                    <option value="">Pilih Tipe Partner</option>
                                    <option value="ojek">🏍️ Ojek</option>
                                    <option value="guide">🧭 Guide</option>
                                    <option value="porter">🎒 Porter</option>
                                    <option value="basecamp">🏠 Basecamp</option>
                                    <option value="semua">👥 Semua Partner</option>
                                </select>
                            </div>

                            <div class="form-group specific-partner-selection" id="specificPartnerSelection_0" style="display: none;">
                                <label for="specificPartner_0">Pilih Partner Spesifik 1:</label>
                                <select id="specificPartner_0" class="specific-partner-select">
                                    <option value="">Pilih Partner</option>
                                </select>
                                <button type="button" class="btn-danger remove-partner-field" style="margin-left: 10px; display: none;" onclick="removePartnerSelectionField(this)">Hapus</button>
                            </div>
                             <div class="form-group">
                                <label for="messageContent_0">Isi Pesan 1:</label>
                                <textarea id="messageContent_0" class="message-content-textarea" placeholder="Tulis pesan untuk partner ini... (Jika ini adalah pesanan baru, tulis detailnya di sini)" required></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addPartnerFieldBtn" class="btn-secondary" style="margin-top: 15px;">Tambah Partner</button>
                    <span id="partnerFieldCounter" style="margin-left: 10px; color: #666;">0/4</span>

                    <button type="submit" class="btn-primary">📤 Kirim Pesan</button>
                </form>
            </div>

            <h3 style="color: #333; margin-bottom: 15px;">📜 Riwayat Pesan Terkirim</h3>
            <div class="message-history" id="messageHistory">
                <div class="message-item">
                    <div class="message-header">
                        <span class="message-recipient">🏍️ Semua Ojek</span>
                        <span class="message-time">25 Jun 2024, 14:30</span>
                    </div>
                    <div class="message-content">
                        Mulai akhir pekan ini, tarif ojek untuk rute basecamp akan naik 20% karena peningkatan jumlah pendaki. Mohon informasikan kepada pendaki sebelum perjalanan.
                    </div>
                </div>
                <div class="message-item">
                    <div class="message-header">
                        <span class="message-recipient">🧭 Semua Guide</span>
                        <span class="message-time">24 Jun 2024, 10:00</span>
                    </div>
                    <div class="message-content">
                       Akan ada briefing SOP baru untuk semua guide pada tanggal 28 Juni 2024 jam 10 pagi di kantor pusat. Mohon hadir tepat waktu.
                    </div>
                </div>
            </div>
        </section>

        <section id="pesananLayanan" class="section">
            <h2 class="table-title">Daftar Pesanan Layanan</h2>
            <div id="serviceOrdersList" class="service-orders-container">
            </div>
        </section>

        <section id="kelolaPartner" class="section">
            <h2 class="table-title">Kelola Partner</h2>

            <div class="controls-container" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <select id="partnerFilterDropdown" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
                    <option value="all">Semua Partner</option>
                    <option value="ojek">Ojek</option>
                    <option value="guide">Guide</option>
                    <option value="porter">Porter</option>
                    <option value="basecamp">Basecamp</option>
                </select>
                <input type="text" id="partnerSearch" placeholder="Cari Partner..." style="flex-grow: 1; margin-left: 10px; padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
            </div>

            <table class="data-table partner-table">
                <thead>
                    <tr>
                        <th>Partner</th>
                        <th>Tipe</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Rating</th>
                        <th>Terakhir Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-partner-type="ojek">
                        <td>
                            <div class="partner-info">
                                <span class="partner-icon">🏍️</span>
                                <div>
                                    <div class="partner-name">Budi Santoso</div>
                                    <div class="partner-contact">081234567890</div>
                                </div>
                            </div>
                        </td>
                        <td>Ojek</td>
                        <td>Basecamp Ranu Pani</td>
                        <td><span class="status-badge online">online</span></td>
                        <td><span class="rating">⭐ 4.8</span></td>
                        <td>5 menit lalu</td>
                        <td>
                            <div class="action-buttons">
                                <button class="icon-button"><i class="fas fa-comment"></i></button>
                                <button class="icon-button"><i class="fas fa-edit"></i></button>
                                <button class="icon-button"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr data-partner-type="guide">
                        <td>
                            <div class="partner-info">
                                <span class="partner-icon">🧭</span>
                                <div>
                                    <div class="partner-name">Sari Dewi</div>
                                    <div class="partner-contact">081234567891</div>
                                </div>
                            </div>
                        </td>
                        <td>Guide</td>
                        <td>Pos 1 Semeru</td>
                        <td><span class="status-badge online">online</span></td>
                        <td><span class="rating">⭐ 4.9</span></td>
                        <td>2 menit lalu</td>
                        <td>
                            <button class="icon-button"><i class="fas fa-comment"></i></button>
                            <button class="icon-button"><i class="fas fa-edit"></i></button>
                            <button class="icon-button"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr data-partner-type="porter">
                        <td>
                            <div class="partner-info">
                                <span class="partner-icon">🎒</span>
                                <div>
                                    <div class="partner-name">Ahmad Porter</div>
                                    <div class="partner-contact">081234567892</div>
                                </div>
                            </div>
                        </td>
                        <td>Porter</td>
                        <td>Pos 2 Semeru</td>
                        <td><span class="status-badge busy">busy</span></td>
                        <td><span class="rating">⭐ 4.7</span></td>
                        <td>1 jam lalu</td>
                        <td>
                            <div class="action-buttons">
                                <button class="icon-button"><i class="fas fa-comment"></i></button>
                                <button class="icon-button"><i class="fas fa-edit"></i></button>
                                <button class="icon-button"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr data-partner-type="basecamp">
                        <td>
                            <div class="partner-info">
                                <span class="partner-icon">🏠</span>
                                <div>
                                    <div class="partner-name">Basecamp Ranu Pani</div>
                                    <div class="partner-contact">081234567893</div>
                                </div>
                            </div>
                        </td>
                        <td>Basecamp</td>
                        <td>Ranu Pani</td>
                        <td><span class="status-badge online">online</span></td>
                        <td><span class="rating">⭐ 4.6</span></td>
                        <td>10 menit lalu</td>
                        <td>
                            <div class="action-buttons">
                                <button class="icon-button"><i class="fas fa-comment"></i></button>
                                <button class="icon-button"><i class="fas fa-edit"></i></button>
                                <button class="icon-button"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr data-partner-type="ojek">
                        <td>
                            <div class="partner-info">
                                <span class="partner-icon">🏍️</span>
                                <div>
                                    <div class="partner-name">Candra Jaya</div>
                                    <div class="partner-contact">081234567894</div>
                                </div>
                            </div>
                        </td>
                        <td>Ojek</td>
                        <td>Basecamp Selo</td>
                        <td><span class="status-badge offline">offline</span></td>
                        <td><span class="rating">⭐ 4.5</span></td>
                        <td>2 hari lalu</td>
                        <td>
                            <div class="action-buttons">
                                <button class="icon-button"><i class="fas fa-comment"></i></button>
                                <button class="icon-button"><i class="fas fa-edit"></i></button>
                                <button class="icon-button"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr data-partner-type="guide">
                        <td>
                            <div class="partner-info">
                                <span class="partner-icon">🧭</span>
                                <div>
                                    <div class="partner-name">Eko Susanto</div>
                                    <div class="partner-contact">081234567895</div>
                                </div>
                            </div>
                        </td>
                        <td>Guide</td>
                        <td>Basecamp Sapuangin</td>
                        <td><span class="status-badge online">online</span></td>
                        <td><span class="rating">⭐ 4.9</span></td>
                        <td>1 jam lalu</td>
                        <td>
                            <div class="action-buttons">
                                <button class="icon-button"><i class="fas fa-comment"></i></button>
                                <button class="icon-button"><i class="fas fa-edit"></i></button>
                                <button class="icon-button"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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