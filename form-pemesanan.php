<?php
require_once 'auth/check_auth.php';
requireLogin();

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan - KoncoNdaki</title>
    <meta name="description" content="Form pemesanan tiket pendakian gunung di KoncoNdaki.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/form-pemesanan.css">
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
                    <a href="diskusi.php" class="nav-link">Diskusi</a>
                    <a href="tentang.php" class="nav-link">Tentang</a>
                </div>

                <!-- User Profile -->
                <div class="user-profile desktop-nav">
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="profile-name" id="profileName"><?php echo htmlspecialchars($currentUser['nama']); ?></span>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </button>
                        
                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar large">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="profile-info">
                                    <h4 id="menuProfileName"><?php echo htmlspecialchars($currentUser['nama']); ?></h4>
                                    <p id="menuProfileEmail"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                                </div>
                            </div>
                            <div class="profile-menu-items">
                                <a href="profile.php" class="profile-menu-item">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Profile Saya</span>
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
                                <a href="auth/logout.php" class="profile-menu-item logout" id="logoutBtn">
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
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="profile-info">
                            <h4 id="mobileProfileName"><?php echo htmlspecialchars($currentUser['nama']); ?></h4>
                            <p id="mobileProfileEmail"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                        </div>
                    </div>
                    
                    <a href="dashboard.php" class="mobile-nav-link">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>
                    
                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-user-circle"></i>
                            Profile Saya
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
                        <a href="auth/logout.php" class="mobile-nav-link logout" id="mobileLogoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Booking Form Section -->
    <section class="booking-section">
        <div class="container">
            <div class="booking-container">
                <!-- Progress Bar -->
                <div class="progress-bar">
                    <div class="progress-step active" data-step="1">
                        <div class="step-number">1</div>
                        <span class="step-label">Pilih Gunung</span>
                    </div>
                    <div class="progress-step" data-step="2">
                        <div class="step-number">2</div>
                        <span class="step-label">Pilih Tanggal</span>
                    </div>
                    <div class="progress-step" data-step="3">
                        <div class="step-number">3</div>
                        <span class="step-label">Data Pendaki</span>
                    </div>
                    <div class="progress-step" data-step="4">
                        <div class="step-number">4</div>
                        <span class="step-label">Konfirmasi</span>
                    </div>
                </div>

                <!-- Form Container -->
                <div class="form-container">
                    <!-- Step 1: Pilih Gunung -->
                    <div class="form-step active" id="step1">
                        <div class="step-header">
                            <h2>Pilih Gunung</h2>
                            <p>Pilih gunung yang ingin Anda daki</p>
                        </div>
                        
                        <div class="mountains-grid">
                            <div class="mountain-option" data-mountain="bromo" data-price="35000">
                                <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop" alt="Gunung Bromo">
                                <div class="mountain-info">
                                    <h3>Gunung Bromo</h3>
                                    <p>Jawa Timur • 2.329 mdpl</p>
                                    <div class="mountain-features">
                                        <span><i class="fas fa-clock"></i> 2-3 hari</span>
                                        <span><i class="fas fa-users"></i> Mudah</span>
                                    </div>
                                    <div class="price">Rp 35.000</div>
                                </div>
                            </div>
                            
                            <div class="mountain-option" data-mountain="merapi" data-price="25000">
                                <img src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop" alt="Gunung Merapi">
                                <div class="mountain-info">
                                    <h3>Gunung Merapi</h3>
                                    <p>Jawa Tengah • 2.930 mdpl</p>
                                    <div class="mountain-features">
                                        <span><i class="fas fa-clock"></i> 1-2 hari</span>
                                        <span><i class="fas fa-users"></i> Sedang</span>
                                    </div>
                                    <div class="price">Rp 25.000</div>
                                </div>
                            </div>
                            
                            <div class="mountain-option" data-mountain="semeru" data-price="45000">
                                <img src="https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop" alt="Gunung Semeru">
                                <div class="mountain-info">
                                    <h3>Gunung Semeru</h3>
                                    <p>Jawa Timur • 3.676 mdpl</p>
                                    <div class="mountain-features">
                                        <span><i class="fas fa-clock"></i> 3-4 hari</span>
                                        <span><i class="fas fa-users"></i> Sulit</span>
                                    </div>
                                    <div class="price">Rp 45.000</div>
                                </div>
                            </div>
                            
                            <div class="mountain-option" data-mountain="lawu" data-price="30000">
                                <img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop" alt="Gunung Lawu">
                                <div class="mountain-info">
                                    <h3>Gunung Lawu</h3>
                                    <p>Jawa Tengah • 3.265 mdpl</p>
                                    <div class="mountain-features">
                                        <span><i class="fas fa-clock"></i> 2-3 hari</span>
                                        <span><i class="fas fa-users"></i> Mudah</span>
                                    </div>
                                    <div class="price">Rp 30.000</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button class="btn-next" onclick="nextStep()" disabled>Lanjutkan</button>
                        </div>
                    </div>

                    <!-- Step 2: Pilih Tanggal -->
                    <div class="form-step" id="step2">
                        <div class="step-header">
                            <h2>Pilih Tanggal Pendakian</h2>
                            <p>Tentukan tanggal yang sesuai untuk pendakian Anda</p>
                        </div>
                        
                        <div class="date-selection">
                            <div class="form-group">
                                <label for="climbDate">Tanggal Pendakian</label>
                                <input type="date" id="climbDate" name="climbDate" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="returnDate">Tanggal Turun</label>
                                <input type="date" id="returnDate" name="returnDate" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="climberCount">Jumlah Pendaki</label>
                                <select id="climberCount" name="climberCount" required>
                                    <option value="">Pilih jumlah pendaki</option>
                                    <option value="1">1 orang</option>
                                    <option value="2">2 orang</option>
                                    <option value="3">3 orang</option>
                                    <option value="4">4 orang</option>
                                    <option value="5">5 orang</option>
                                    <option value="6">6 orang</option>
                                    <option value="7">7 orang</option>
                                    <option value="8">8 orang</option>
                                    <option value="9">9 orang</option>
                                    <option value="10">10 orang</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button class="btn-prev" onclick="prevStep()">Kembali</button>
                            <button class="btn-next" onclick="nextStep()" disabled>Lanjutkan</button>
                        </div>
                    </div>

                    <!-- Step 3: Data Pendaki -->
                    <div class="form-step" id="step3">
                        <div class="step-header">
                            <h2>Data Pendaki</h2>
                            <p>Lengkapi data diri dan informasi pendaki lainnya</p>
                        </div>
                        
                        <div class="climbers-data" id="climbersData">
                            <!-- Climber data forms will be generated here -->
                        </div>
                        
                        <div class="form-actions">
                            <button class="btn-prev" onclick="prevStep()">Kembali</button>
                            <button class="btn-next" onclick="nextStep()" disabled>Lanjutkan</button>
                        </div>
                    </div>

                    <!-- Step 4: Konfirmasi -->
                    <div class="form-step" id="step4">
                        <div class="step-header">
                            <h2>Konfirmasi & Pembayaran</h2>
                            <p>Periksa kembali data pemesanan Anda</p>
                        </div>
                        
                        <div class="confirmation-details">
                            <div class="confirmation-section">
                                <h3>Detail Pemesanan</h3>
                                <div class="detail-item">
                                    <span class="label">Gunung:</span>
                                    <span class="value" id="confirmMountain">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Tanggal Pendakian:</span>
                                    <span class="value" id="confirmClimbDate">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Tanggal Turun:</span>
                                    <span class="value" id="confirmReturnDate">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Jumlah Pendaki:</span>
                                    <span class="value" id="confirmClimberCount">-</span>
                                </div>
                            </div>
                            
                            <div class="confirmation-section">
                                <h3>Daftar Pendaki</h3>
                                <div class="climbers-list" id="confirmClimbersList">
                                    <!-- Climbers list will be populated here -->
                                </div>
                            </div>
                            
                            <div class="confirmation-section">
                                <h3>Rincian Biaya</h3>
                                <div class="cost-breakdown">
                                    <div class="cost-item">
                                        <span>Tiket per orang:</span>
                                        <span id="ticketPrice">Rp 0</span>
                                    </div>
                                    <div class="cost-item">
                                        <span>Jumlah pendaki:</span>
                                        <span id="totalClimbers">0</span>
                                    </div>
                                    <div class="cost-item">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">Rp 0</span>
                                    </div>
                                    <div class="cost-item">
                                        <span>Pajak (10%):</span>
                                        <span id="tax">Rp 0</span>
                                    </div>
                                    <div class="cost-item total">
                                        <span>Total Pembayaran:</span>
                                        <span id="totalPayment">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button class="btn-prev" onclick="prevStep()">Kembali</button>
                            <button class="btn-confirm" onclick="confirmBooking()">
                                <i class="fas fa-credit-card"></i>
                                Konfirmasi & Bayar
                            </button>
                        </div>
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
                        <li><a href="form-pemesanan.php">Pemesanan Tiket</a></li>
                        <li><a href="info-gunung.php">Info Gunung</a></li>
                        <li><a href="cara-pemesanan.php">Panduan Pendakian</a></li>
                        <li><a href="#">Peralatan</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="cara-pemesanan.php">Cara Pemesanan</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Kontak</a></li>
                        <li><a href="diskusi.php">Diskusi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Tentang</h3>
                    <ul>
                        <li><a href="tentang.php">Tentang Kami</a></li>
                        <li><a href="#">Tim</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 KoncoNdaki. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="scripts/script.js"></script>
    <script src="scripts/form-pemesanan.js"></script>
</body>
</html> 