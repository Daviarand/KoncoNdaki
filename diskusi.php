<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Diskusi - KoncoNdaki</title>
    <meta name="description" content="Forum diskusi komunitas pendaki gunung KoncoNdaki. Berbagi cerita, tips, dan pengalaman pendakian.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/diskusi.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Logo -->
                <div class="logo">
                    <img src="images/logo.png" alt="KoncoNdaki Logo">
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links desktop-nav">
                    <a href="dashboard.php" class="nav-link">Home</a>
                    <a href="info-gunung.php" class="nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link active">Diskusi</a>
                    <a href="tentang.php" class="nav-link">Tentang</a>
                </div>

                <!-- User Profile -->
                <div class="user-profile desktop-nav">
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn">
                            <div class="profile-avatar">
                                <?php if (!empty($_SESSION['profile_picture'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Foto Profil" class="profile-img">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <span class="profile-name" id="profileName">
                                <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                            </span>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </button>

                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar large">
                                    <?php if (!empty($_SESSION['profile_picture'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Foto Profil" class="profile-img">
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
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
                                <a href="profile.php" class="profile-menu-item">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Profile Saya</span>
                                </a>
                                <a href="chatbox.php" class="profile-menu-item">
                                    <i class="fas fa-comment-alt"></i>
                                    <span>KoncoNdaki Assistant</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-ticket-alt"></i>
                                    <span>Tiket Saya</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-history"></i>
                                    <span>Riwayat Pemesanan</span>
                                </a>
                                <a href="#" class="profile-menu-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Pengaturan</span>
                                </a>
                                <div class="profile-menu-divider"></div>
                                <a href="#" class="profile-menu-item logout" id="logoutBtn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Keluar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="mobile-nav" id="mobile-nav">
                <div class="mobile-nav-content">
                    <!-- Mobile Profile Header -->
                    <div class="mobile-profile-header">
                        <div class="profile-avatar">
                            <?php if (!empty($_SESSION['profile_picture'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Foto Profil" class="profile-img">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
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

                    <a href="dashboard.php" class="mobile-nav-link">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link active">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>

                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-user-circle"></i>
                            Profile Saya
                        </a>
                        <a href="chatbox.php" class="profile-menu-item">
                            <i class="fas fa-comment-alt"></i>
                            <span>KoncoNdaki Assistant</span>
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-ticket-alt"></i>
                            Tiket Saya
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-history"></i>
                            Riwayat Pemesanan
                        </a>
                        <a href="#" class="mobile-nav-link">
                            <i class="fas fa-cog"></i>
                            Pengaturan
                        </a>
                        <a href="#" class="mobile-nav-link logout" id="mobileLogoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <h1><i class="fas fa-comments"></i> Forum Diskusi</h1>
                <p>Berbagi cerita, tips, dan pengalaman pendakian bersama komunitas KoncoNdaki</p>
            </div>
        </div>
    </section>

    <!-- Forum Content -->
    <section class="forum-section">
        <div class="container">
            <div class="forum-layout">
                <!-- Sidebar -->
                <div class="forum-sidebar">
                    <!-- HAPUS sidebar-card anggota aktif -->
                    <div class="sidebar-card">
                        <h3><i class="fas fa-fire"></i> Topik Populer</h3>
                        <div class="popular-topics">
                            <div class="topic-tag active" data-category="all">
                                <i class="fas fa-globe"></i>
                                Semua Topik
                            </div>
                            <div class="topic-tag" data-category="pengalaman">
                                <i class="fas fa-mountain"></i>
                                Pengalaman Pendakian
                            </div>
                            <div class="topic-tag" data-category="tips">
                                <i class="fas fa-lightbulb"></i>
                                Tips & Trik
                            </div>
                            <div class="topic-tag" data-category="peralatan">
                                <i class="fas fa-backpack"></i>
                                Peralatan
                            </div>
                            <div class="topic-tag" data-category="cuaca">
                                <i class="fas fa-cloud-sun"></i>
                                Info Cuaca
                            </div>
                            <div class="topic-tag" data-category="tanya-jawab">
                                <i class="fas fa-question-circle"></i>
                                Tanya Jawab
                            </div>
                        </div>
                    </div>
                    <!-- HAPUS mulai dari sini -->
                    <!--
                    <div class="sidebar-card">
                        <h3><i class="fas fa-users"></i> Anggota Aktif</h3>
                        <div class="active-members">
                            <div class="member-item">
                                <div class="member-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="member-info">
                                    <span class="member-name">Andi Pratama</span>
                                    <span class="member-posts">142 post</span>
                                </div>
                            </div>
                            <div class="member-item">
                                <div class="member-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="member-info">
                                    <span class="member-name">Sari Dewi</span>
                                    <span class="member-posts">98 post</span>
                                </div>
                            </div>
                            <div class="member-item">
                                <div class="member-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="member-info">
                                    <span class="member-name">Budi Santoso</span>
                                    <span class="member-posts">87 post</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                </div>

                <!-- Main Content -->
                <div class="forum-main">
                    <!-- Create Post Section -->
                    <div class="create-post-card">
                        <div class="create-post-header">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" placeholder="Bagikan pengalaman pendakian Anda..." id="createPostInput">
                        </div>
                        <div class="create-post-actions">
                            <div class="post-options">
                                <button class="post-option" id="addImageBtn">
                                    <i class="fas fa-image"></i>
                                    Foto
                                </button>
                                <button class="post-option" id="addLocationBtn">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Lokasi
                                </button>
                                <select class="category-select" id="categorySelect">
                                    <option value="pengalaman">Pengalaman Pendakian</option>
                                    <option value="tips">Tips & Trik</option>
                                    <option value="peralatan">Peralatan</option>
                                    <option value="cuaca">Info Cuaca</option>
                                    <option value="tanya-jawab">Tanya Jawab</option>
                                </select>
                            </div>
                            <button class="btn-post" id="submitPostBtn">
                                <i class="fas fa-paper-plane"></i>
                                Posting
                            </button>
                        </div>
                    </div>

                    <!-- Posts Feed -->
                    <div class="posts-feed" id="postsFeed">
                        <!-- Post 1 -->
                        <div class="post-card" data-category="pengalaman">
                            <div class="post-header">
                                <div class="post-author">
                                    <div class="author-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name">Andi Pratama</span>
                                        <span class="post-time">2 jam yang lalu</span>
                                    </div>
                                </div>
                                <div class="post-category pengalaman">
                                    <i class="fas fa-mountain"></i>
                                    Pengalaman Pendakian
                                </div>
                            </div>
                            <div class="post-content">
                                <h3>Pengalaman Mendaki Gunung Bromo di Musim Hujan</h3>
                                <p>Kemarin baru saja selesai mendaki Gunung Bromo bersama teman-teman. Meskipun cuaca tidak terlalu mendukung karena musim hujan, pemandangan sunrise tetap spektakuler! Beberapa tips yang bisa saya bagikan untuk pendakian di musim hujan...</p>
                                <div class="post-image">
                                    <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=600&h=300&fit=crop" alt="Gunung Bromo">
                                </div>
                            </div>
                            <div class="post-footer">
                                <div class="post-stats">
                                    <button class="stat-btn like-btn" data-post="1">
                                        <i class="far fa-heart"></i>
                                        <span>24</span>
                                    </button>
                                    <button class="stat-btn comment-btn" data-post="1">
                                        <i class="far fa-comment"></i>
                                        <span>8</span>
                                    </button>
                                    <button class="stat-btn share-btn">
                                        <i class="fas fa-share"></i>
                                        <span>3</span>
                                    </button>
                                </div>
                                <button class="btn-read-more" onclick="expandPost(1)">
                                    Baca Selengkapnya
                                </button>
                            </div>
                            <div class="comments-section" id="comments-1" style="display: none;">
                                <div class="comment">
                                    <div class="comment-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="comment-content">
                                        <span class="comment-author">Sari Dewi</span>
                                        <p>Wah keren banget! Aku juga pengen coba mendaki Bromo. Kira-kira persiapan apa aja yang perlu dibawa untuk musim hujan?</p>
                                        <span class="comment-time">1 jam yang lalu</span>
                                    </div>
                                </div>
                                <div class="comment-input">
                                    <div class="user-avatar small">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" placeholder="Tulis komentar...">
                                    <button class="btn-comment">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Post 2 -->
                        <div class="post-card" data-category="tips">
                            <div class="post-header">
                                <div class="post-author">
                                    <div class="author-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name">Budi Santoso</span>
                                        <span class="post-time">5 jam yang lalu</span>
                                    </div>
                                </div>
                                <div class="post-category tips">
                                    <i class="fas fa-lightbulb"></i>
                                    Tips & Trik
                                </div>
                            </div>
                            <div class="post-content">
                                <h3>5 Tips Penting untuk Pendaki Pemula</h3>
                                <p>Buat teman-teman yang baru mau mulai hobi mendaki, ini beberapa tips penting yang harus diketahui:</p>
                                <ul>
                                    <li>Pilih gunung dengan tingkat kesulitan pemula</li>
                                    <li>Persiapkan fisik minimal 2 minggu sebelum pendakian</li>
                                    <li>Bawa peralatan yang sesuai dan jangan berlebihan</li>
                                    <li>Selalu informasikan rencana pendakian ke keluarga</li>
                                    <li>Ikuti aturan dan jaga kebersihan alam</li>
                                </ul>
                            </div>
                            <div class="post-footer">
                                <div class="post-stats">
                                    <button class="stat-btn like-btn" data-post="2">
                                        <i class="far fa-heart"></i>
                                        <span>45</span>
                                    </button>
                                    <button class="stat-btn comment-btn" data-post="2">
                                        <i class="far fa-comment"></i>
                                        <span>12</span>
                                    </button>
                                    <button class="stat-btn share-btn">
                                        <i class="fas fa-share"></i>
                                        <span>7</span>
                                    </button>
                                </div>
                                <button class="btn-read-more" onclick="expandPost(2)">
                                    Lihat Komentar
                                </button>
                            </div>
                            <div class="comments-section" id="comments-2" style="display: none;">
                                <div class="comment">
                                    <div class="comment-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="comment-content">
                                        <span class="comment-author">Maya Putri</span>
                                        <p>Terima kasih tipsnya! Sangat membantu untuk pemula seperti saya. Ada rekomendasi gunung untuk pemula di Jawa Barat?</p>
                                        <span class="comment-time">3 jam yang lalu</span>
                                    </div>
                                </div>
                                <div class="comment-input">
                                    <div class="user-avatar small">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" placeholder="Tulis komentar...">
                                    <button class="btn-comment">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Post 3 -->
                        <div class="post-card" data-category="tanya-jawab">
                            <div class="post-header">
                                <div class="post-author">
                                    <div class="author-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name">Dina Marlina</span>
                                        <span class="post-time">1 hari yang lalu</span>
                                    </div>
                                </div>
                                <div class="post-category tanya-jawab">
                                    <i class="fas fa-question-circle"></i>
                                    Tanya Jawab
                                </div>
                            </div>
                            <div class="post-content">
                                <h3>Tanya: Perlengkapan Wajib untuk Mendaki Gunung Semeru?</h3>
                                <p>Halo semuanya! Saya berencana mendaki Gunung Semeru bulan depan untuk pertama kalinya. Bisa minta saran perlengkapan apa saja yang wajib dibawa? Terutama untuk menghadapi cuaca dingin di puncak. Terima kasih!</p>
                            </div>
                            <div class="post-footer">
                                <div class="post-stats">
                                    <button class="stat-btn like-btn" data-post="3">
                                        <i class="far fa-heart"></i>
                                        <span>18</span>
                                    </button>
                                    <button class="stat-btn comment-btn" data-post="3">
                                        <i class="far fa-comment"></i>
                                        <span>15</span>
                                    </button>
                                    <button class="stat-btn share-btn">
                                        <i class="fas fa-share"></i>
                                        <span>2</span>
                                    </button>
                                </div>
                                <button class="btn-read-more" onclick="expandPost(3)">
                                    Lihat Jawaban
                                </button>
                            </div>
                            <div class="comments-section" id="comments-3" style="display: none;">
                                <div class="comment">
                                    <div class="comment-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="comment-content">
                                        <span class="comment-author">Andi Pratama</span>
                                        <p>Untuk Semeru wajib bawa sleeping bag yang tahan suhu -5°C, jaket tebal, sarung tangan, dan kacamata hitam. Jangan lupa headlamp cadangan!</p>
                                        <span class="comment-time">20 jam yang lalu</span>
                                    </div>
                                </div>
                                <div class="comment">
                                    <div class="comment-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="comment-content">
                                        <span class="comment-author">Riko Saputra</span>
                                        <p>Tambahan: bawa masker atau buff untuk melindungi dari debu vulkanik. Semeru sering ada hujan abu soalnya.</p>
                                        <span class="comment-time">18 jam yang lalu</span>
                                    </div>
                                </div>
                                <div class="comment-input">
                                    <div class="user-avatar small">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" placeholder="Tulis komentar...">
                                    <button class="btn-comment">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Load More Button -->
                    <div class="load-more-section">
                        <button class="btn-load-more" id="loadMoreBtn">
                            <i class="fas fa-chevron-down"></i>
                            Muat Lebih Banyak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-mountain"></i>
                        <span>KoncoNdaki</span>
                    </div>
                    <p>Platform terpercaya untuk pemesanan tiket pendakian gunung di seluruh Pulau Jawa.</p>
                </div>

                <div class="footer-section">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="cara-pemesanan.php" class="nav-link">Pemesanan Tiket</a></li>
                        <li><a href="info-gunung.php" class="nav-link">Info Gunung</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="cara-pemesanan.php" class="nav-link">Cara Pemesanan</a></li>
                        <li><a href="cara-pemesanan.php" class="nav-link">FAQ</a></li>
                        <li><a href="tentang.php" class="nav-link">Kontak</a></li>
                        <li><a href="diskusi.php" class="nav-link">Diskusi</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Tentang</h3>
                    <ul>
                        <li><a href="tentang.php" class="nav-link">Tentang Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 KoncoNdaki. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Variabel ini akan menampung data pengguna dari session PHP
        // dan akan dibaca oleh file diskusi.js
        const LOGGED_IN_USER = {
            firstName: "<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : 'Pengguna'; ?>",
            lastName: "<?php echo isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : ''; ?>"
        };
    </script>

    <script src="scripts/script.js"></script>
    <script src="scripts/diskusi.js"></script>
</body>

</html>