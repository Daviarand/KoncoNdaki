<?php
require_once 'auth/check_auth.php';

$currentUser = null;
$isLoggedIn = false;

if (isLoggedIn()) {
    $currentUser = getCurrentUser();
    $isLoggedIn = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Dilarang - KoncoNdaki</title>
    <meta name="description" content="Anda tidak memiliki akses ke halaman ini.">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            padding: 20px;
        }
        
        .error-container {
            text-align: center;
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        
        .error-icon {
            font-size: 120px;
            color: #ef4444;
            margin-bottom: 30px;
        }
        
        .error-title {
            font-size: 48px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .error-message {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-error {
            padding: 15px 30px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);
        }
        
        .btn-secondary {
            background: transparent;
            color: #ef4444;
            border: 2px solid #ef4444;
        }
        
        .btn-secondary:hover {
            background: #ef4444;
            color: white;
        }
        
        @media (max-width: 768px) {
            .error-container {
                padding: 40px 20px;
            }
            
            .error-icon {
                font-size: 80px;
            }
            
            .error-title {
                font-size: 36px;
            }
            
            .error-message {
                font-size: 16px;
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .btn-error {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
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
                    <a href="<?php echo $isLoggedIn ? 'dashboard.php' : 'login.php'; ?>" class="nav-link">Home</a>
                    <a href="info-gunung.php" class="nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link">Diskusi</a>
                    <a href="tentang.php" class="nav-link">Tentang</a>
                </div>

                <?php if ($isLoggedIn): ?>
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
                <?php else: ?>
                <!-- Auth Buttons -->
                <div class="auth-buttons desktop-nav">
                    <a href="login.php" class="btn-login">Masuk</a>
                    <a href="register.php" class="btn-register">Daftar</a>
                </div>
                <?php endif; ?>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="mobile-nav" id="mobile-nav">
                <div class="mobile-nav-content">
                    <?php if ($isLoggedIn): ?>
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
                    <?php endif; ?>
                    
                    <a href="<?php echo $isLoggedIn ? 'dashboard.php' : 'login.php'; ?>" class="mobile-nav-link">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>
                    
                    <?php if ($isLoggedIn): ?>
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
                    <?php else: ?>
                    <div class="mobile-auth-buttons">
                        <a href="login.php" class="mobile-nav-link">Masuk</a>
                        <a href="register.php" class="mobile-nav-link">Daftar</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Error Section -->
    <section class="error-section">
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-ban"></i>
            </div>
            <h1 class="error-title">403</h1>
            <p class="error-message">
                Maaf! Anda tidak memiliki izin untuk mengakses halaman ini. 
                Silakan login dengan akun yang sesuai atau hubungi administrator.
            </p>
            <div class="error-actions">
                <a href="<?php echo $isLoggedIn ? 'dashboard.php' : 'login.php'; ?>" class="btn-error btn-primary">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
                <a href="login.php" class="btn-error btn-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
            </div>
        </div>
    </section>

    <script src="scripts/script.js"></script>
</body>
</html> 