<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Layanan - KoncoNdaki</title>
    <meta name="description" content="Dashboard untuk pengelola layanan ojek, porter, guide, dan basecamp di KoncoNdaki.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/dashboard-layanan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Layanan</h1>
                <p>Kelola pesanan dan notifikasi untuk semua layanan pendakian Anda</p>
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
                        <h3><i class="fas fa-filter"></i> Filter Layanan</h3>
                        <div class="notification-count" id="totalNotifications">
                            <span>24</span> notifikasi
                        </div>
                    </div>

                    <!-- Service Categories -->
                    <div class="sidebar-section">
                        <h4>Kategori Layanan</h4>
                        <ul class="category-list">
                            <li class="category-item active" data-category="all">
                                <i class="fas fa-list"></i>
                                <span>Semua Layanan</span>
                                <span class="count">24</span>
                            </li>
                            <li class="category-item" data-category="ojek">
                                <i class="fas fa-motorcycle"></i>
                                <span>Pemesanan Ojek</span>
                                <span class="count">8</span>
                            </li>
                            <li class="category-item" data-category="porter">
                                <i class="fas fa-hiking"></i>
                                <span>Pemesanan Porter</span>
                                <span class="count">6</span>
                            </li>
                            <li class="category-item" data-category="guide">
                                <i class="fas fa-user-tie"></i>
                                <span>Pemesanan Guide</span>
                                <span class="count">5</span>
                            </li>
                            <li class="category-item" data-category="basecamp">
                                <i class="fas fa-campground"></i>
                                <span>Pemesanan Basecamp</span>
                                <span class="count">5</span>
                            </li>
                        </ul>
                    </div>
                </aside>

                <!-- Main Dashboard Content -->
                <div class="dashboard-content">
                    <!-- Dashboard Header -->
                    <div class="content-header">
                        <div class="header-left">
                            <h2 id="currentCategory">Semua Layanan</h2>
                            <p id="categoryDescription">Menampilkan semua notifikasi pesanan dari berbagai layanan</p>
                        </div>
                        <div class="header-actions">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Cari pesanan..." id="searchInput">
                            </div>
                            <div class="sort-dropdown">
                                <select id="sortSelect">
                                    <option value="newest">Terbaru</option>
                                    <option value="oldest">Terlama</option>
                                    <option value="priority">Prioritas</option>
                                    <option value="amount">Nilai Pesanan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Grid -->
                    <div class="notifications-grid" id="notificationsGrid">
                        <!-- Notification cards will be populated by JavaScript -->
                    </div>

                    <!-- Load More Button -->
                    <div class="load-more-container">
                        <button class="btn-load-more" id="loadMoreBtn">
                            <i class="fas fa-plus"></i>
                            Muat Lebih Banyak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Notification Detail Modal -->
    <div class="modal-overlay" id="notificationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Detail Pesanan</h3>
                <button class="modal-close" id="modalClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Modal content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="modalCancel">Tutup</button>
                <button class="btn-primary" id="modalAction">Terima Pesanan</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content" style="justify-content: center; text-align: center;">
                <div class="footer-section" style="margin: 0 auto;">
                    <div class="footer-logo" style="justify-content: center;">
                        <i class="fas fa-mountain"></i>
                        <span>KoncoNdaki</span>
                    </div>
                    <p>Platform terpercaya untuk pemesanan tiket pendakian gunung di seluruh Pulau Jawa.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 KoncoNdaki. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="scripts/script.js"></script>
    <script src="scripts/dashboard-layanan.js"></script>
</body>
</html>
