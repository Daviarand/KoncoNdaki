<?php
require_once 'auth/check_auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error Server - KoncoNdaki</title>
    <meta name="description" content="Terjadi kesalahan pada server.">
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            color: #f59e0b;
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
            color: #f59e0b;
            border: 2px solid #f59e0b;
        }
        
        .btn-secondary:hover {
            background: #f59e0b;
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
                    <a href="<?php echo 'login.php'; ?>" class="nav-link">Home</a>
                    <a href="info-gunung.php" class="nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link">Diskusi</a>
                    <a href="tentang.php" class="nav-link">Tentang</a>
                </div>

                <!-- Auth Buttons -->
                <div class="auth-buttons desktop-nav">
                    <a href="login.php" class="btn-login">Masuk</a>
                    <a href="register.php" class="btn-register">Daftar</a>
                </div>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="mobile-nav" id="mobile-nav">
                <div class="mobile-nav-content">
                    <a href="<?php echo 'login.php'; ?>" class="mobile-nav-link">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>
                    
                    <div class="mobile-auth-buttons">
                        <a href="login.php" class="mobile-nav-link">Masuk</a>
                        <a href="register.php" class="mobile-nav-link">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Error Section -->
    <section class="error-section">
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="error-title">500</h1>
            <p class="error-message">
                Oops! Terjadi kesalahan pada server kami. 
                Tim kami sedang bekerja untuk memperbaikinya. 
                Silakan coba lagi dalam beberapa saat.
            </p>
            <div class="error-actions">
                <a href="<?php echo 'login.php'; ?>" class="btn-error btn-primary">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
                <a href="javascript:location.reload()" class="btn-error btn-secondary">
                    <i class="fas fa-redo"></i>
                    Coba Lagi
                </a>
            </div>
        </div>
    </section>

    <script src="scripts/script.js"></script>
</body>
</html> 