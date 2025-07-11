<?php

// Process registration form
$error_message = '';
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';
    
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword)) {
        $error_message = 'Semua field harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Format email tidak valid';
    } elseif (strlen($password) < 6) {
        $error_message = 'Password minimal 6 karakter';
    } elseif ($password !== $confirmPassword) {
        $error_message = 'Konfirmasi password tidak cocok';
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error_message = 'Email sudah terdaftar';
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, 'pendaki', NOW())");
                $stmt->execute([$firstName, $lastName, $email, $phone, $hashedPassword]);
                
                $success_message = 'Registrasi berhasil! Silakan login dengan akun Anda.';
                
                // Clear form data
                $_POST = array();
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
    <title>Daftar - KoncoNdaki</title>
    <meta name="description" content="Daftar akun KoncoNdaki untuk memesan tiket pendakian gunung di Pulau Jawa.">
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

    <!-- Register Section -->
    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-content">
                <!-- Left Side - Image -->
                <div class="auth-image">
                    <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop"
                        alt="Pendakian Gunung">
                    <div class="image-overlay">
                        <h2>Mulai Petualangan Anda!</h2>
                        <p>Bergabunglah dengan ribuan pendaki lainnya di KoncoNdaki</p>
                    </div>
                </div>

                <!-- Right Side - Form -->
                <div class="auth-form-container">
                    <div class="auth-form">
                        <div class="form-header">
                            <h1>Buat Akun Baru</h1>
                            <p>Daftar untuk memulai petualangan pendakian Anda</p>
                        </div>

                        <?php if (!empty($error_message)): ?>
                        <div class="error-message" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: center;">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($success_message)): ?>
                        <div class="success-message" style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: center;">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="" class="register-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">Nama Depan</label>
                                    <div class="input-group">
                                        <i class="fas fa-user"></i>
                                        <input type="text" id="firstName" name="firstName" placeholder="Nama depan"
                                            required value="<?php echo htmlspecialchars($users['firstName'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="lastName">Nama Belakang</label>
                                    <div class="input-group">
                                        <i class="fas fa-user"></i>
                                        <input type="text" id="lastName" name="lastName" placeholder="Nama belakang"
                                            required value="<?php echo htmlspecialchars($users['lastName'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="registerEmail">Email</label>
                                <div class="input-group">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="registerEmail" name="email"
                                        placeholder="Masukkan email Anda" required value="<?php echo htmlspecialchars($users['email'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone">Nomor Telepon</label>
                                <div class="input-group">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" id="phone" name="phone" placeholder="Masukkan nomor telepon"
                                        required value="<?php echo htmlspecialchars($users['phone'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="registerPassword">Password</label>
                                <div class="input-group">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" id="registerPassword" name="password"
                                        placeholder="Buat password" required>
                                    <button type="button" class="toggle-password" id="toggleRegisterPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength" id="passwordStrength">
                                    <div class="strength-bar">
                                        <div class="strength-fill"></div>
                                    </div>
                                    <span class="strength-text">Password lemah</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword">Konfirmasi Password</label>
                                <div class="input-group">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" id="confirmPassword" name="confirmPassword"
                                        placeholder="Konfirmasi password" required>
                                    <button type="button" class="toggle-password" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-options">
                                <label class="checkbox-container">
                                    <input type="checkbox" id="terms" required>
                                    <span class="checkmark"></span>
                                    Saya setuju dengan <a href="syarat-ketentuan.php"> Syarat & Ketentuan </a>
                                </label>
                            </div>

                            <div class="form-options">
                                <label class="checkbox-container">
                                    <input type="checkbox" id="newsletter">
                                    <span class="checkmark"></span>
                                    Saya ingin menerima informasi dan penawaran menarik
                                </label>
                            </div>

                            <button type="submit" class="btn-submit">
                                <span class="btn-text">Daftar Sekarang</span>
                                <i class="fas fa-arrow-right btn-icon"></i>
                            </button>
                        </form>

                        <div class="form-divider">
                            <span>atau</span>
                        </div>

                        <div class="social-login">
                            <button class="btn-social google">
                                <i class="fab fa-google"></i>
                                <span>Daftar dengan Google</span>
                            </button>
                            <button class="btn-social facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Daftar dengan Facebook</span>
                            </button>
                        </div>

                        <div class="form-footer">
                            <p>Sudah punya akun? <a href="login.php">Masuk sekarang</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="scripts/auth-script.js"></script>
</body>

</html>