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

                        <form id="registerForm" class="register-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">Nama Depan</label>
                                    <div class="input-group">
                                        <i class="fas fa-user"></i>
                                        <input type="text" id="firstName" name="firstName" placeholder="Nama depan"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="lastName">Nama Belakang</label>
                                    <div class="input-group">
                                        <i class="fas fa-user"></i>
                                        <input type="text" id="lastName" name="lastName" placeholder="Nama belakang"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="registerEmail">Email</label>
                                <div class="input-group">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="registerEmail" name="email"
                                        placeholder="Masukkan email Anda" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone">Nomor Telepon</label>
                                <div class="input-group">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" id="phone" name="phone" placeholder="Masukkan nomor telepon"
                                        required>
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
                                    Saya setuju dengan <a href="#"> Syarat & Ketentuan </a> dan <a href="#"> Kebijakan
                                        Privasi</a>
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