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

                        <form id="loginForm" class="login-form">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
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