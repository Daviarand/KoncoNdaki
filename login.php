<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'];
    switch ($role) {
        case 'pendaki':
            header('Location: dashboard.php');
            exit;
        case 'layanan':
            header('Location: dashboard-layanan.php');
            exit;
        case 'admin':
        case 'pengelola':
            header('Location: dashboard.php');
            exit;
        default:
            header('Location: dashboard.php');
            exit;
    }
}

// Process login form
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error_message = 'Email dan password harus diisi';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, email, password, role, first_name, last_name FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                
                // Redirect based on role
                switch ($user['role']) {
                    case 'pendaki':
                        header('Location: dashboard.php');
                        exit;
                    case 'layanan':
                        header('Location: dashboard-layanan.php');
                        exit;
                    case 'admin':
                    case 'pengelola':
                        header('Location: dashboard.php');
                        exit;
                    default:
                        header('Location: dashboard.php');
                        exit;
                }
            } else {
                $error_message = 'Email atau password salah';
            }
        } catch (PDOException $e) {
            $error_message = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - KoncoNdaki</title>
    <meta name="description" content="Masuk ke akun KoncoNdaki untuk memesan tiket pendakian gunung di Pulau Jawa.">
    <link rel="stylesheet" href="styles/auth-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="#" class="logo-link">
                        <i class="fas fa-mountain"></i>
                        <span>KoncoNdaki</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-content">
                <!-- Left Side - Image -->
                <div class="auth-image">
                    <img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop" alt="Gunung Indonesia">
                    <div class="image-overlay">
                        <h2>Selamat Datang Kembali!</h2>
                        <p>Lanjutkan petualangan pendakian Anda bersama KoncoNdaki</p>
                    </div>
                </div>

                <!-- Right Side - Form -->
                <div class="auth-form-container">
                    <div class="auth-form">
                        <div class="form-header">
                            <h1>Masuk ke Akun</h1>
                            <p>Masukkan email dan password untuk melanjutkan</p>
                        </div>

                        <?php if (!empty($error_message)): ?>
                        <div class="error-message" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: center;">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="" class="login-form">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
                                    <button type="button" class="toggle-password" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-options">
                                <label class="checkbox-container">
                                    <input type="checkbox" id="remember">
                                    <span class="checkmark"></span>
                                    Ingat saya
                                </label>
                                <a href="#" class="forgot-password">Lupa password?</a>
                            </div>

                            <button type="submit" class="btn-submit">
                                <span class="btn-text">Masuk</span>
                                <i class="fas fa-arrow-right btn-icon"></i>
                            </button>
                        </form>

                        <div class="form-divider">
                            <span>atau</span>
                        </div>

                        <div class="social-login">
                            <button class="btn-social google">
                                <i class="fab fa-google"></i>
                                <span>Masuk dengan Google</span>
                            </button>
                            <button class="btn-social facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Masuk dengan Facebook</span>
                            </button>
                        </div>

                        <div class="form-footer">
                            <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="scripts/auth-script.js"></script>
</body>
</html>