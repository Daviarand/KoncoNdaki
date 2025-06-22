<?php
require_once 'auth/check_auth.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Tiket Gunung - KoncoNdaki</title>
    <meta name="description" content="Form pemesanan tiket pendakian gunung di KoncoNdaki.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/form-pemesanan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-content">
                <div class="logo">
                    <img src="images/logo.png" alt="logo">
                </div>
            </div>
        </div>
    </nav>
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <h1><i class="fas fa-clipboard-list"></i> Form Pemesanan Tiket</h1>
                <p>Isi data pemesanan tiket pendakian gunung dengan mudah dan cepat</p>
            </div>
        </div>
    </section>
    <section class="booking-form-section">
        <div class="container">
            <div class="booking-layout">
                <div class="booking-form-container">
                    <div class="form-header">
                        <h2>Mulai Pemesanan</h2>
                        <p>Isi form di bawah untuk memulai proses pemesanan tiket</p>
                    </div>
                    <form id="bookingForm" class="booking-form">
                        <!-- Step 1: Pilih Gunung -->
                        <div class="form-step active" id="step-1">
                            <div class="step-header">
                                <h3><i class="fas fa-mountain"></i> Pilih Gunung</h3>
                                <p>Pilih gunung yang ingin Anda daki</p>
                            </div>
                            <div class="mountain-selection">
                                <div class="mountain-option" data-mountain="bromo">
                                    <img src="https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Bromo">
                                    <div class="mountain-info">
                                        <h4>Gunung Bromo</h4>
                                        <p>Jawa Timur • 2.329 mdpl</p>
                                        <span class="price">Rp 35.000</span>
                                    </div>
                                </div>
                                <div class="mountain-option" data-mountain="merapi">
                                    <img src="https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Merapi">
                                    <div class="mountain-info">
                                        <h4>Gunung Merapi</h4>
                                        <p>Jawa Tengah • 2.930 mdpl</p>
                                        <span class="price">Rp 25.000</span>
                                    </div>
                                </div>
                                <div class="mountain-option" data-mountain="semeru">
                                    <img src="https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Semeru">
                                    <div class="mountain-info">
                                        <h4>Gunung Semeru</h4>
                                        <p>Jawa Timur • 3.676 mdpl</p>
                                        <span class="price">Rp 45.000</span>
                                    </div>
                                </div>
                                <div class="mountain-option" data-mountain="gede">
                                    <img src="https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Gunung Gede">
                                    <div class="mountain-info">
                                        <h4>Gunung Gede</h4>
                                        <p>Jawa Barat • 2.958 mdpl</p>
                                        <span class="price">Rp 30.000</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-back" id="backStep1">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                           </button>
                                <button type="button" class="btn-next" id="nextStep1" disabled>
                                    Lanjutkan <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Step 2: Pilih Jalur & Tanggal -->
                        <div class="form-step" id="step-2">
                            <div class="step-header">
                                <h3><i class="fas fa-route"></i> Pilih Jalur & Tanggal</h3>
                                <p>Tentukan jalur pendakian dan tanggal keberangkatan</p>
                            </div>
                            <div class="route-selection" id="routeSelection">
                                <!-- Routes will be populated dynamically -->
                            </div>
                            <div class="date-selection">
                                <div class="form-group">
                                    <label for="hikingDate">Tanggal Pendakian</label>
                                    <input type="date" id="hikingDate" name="hikingDate" required>
                                </div>
                                <div class="form-group">
                                    <label for="participants">Jumlah Pendaki</label>
                                    <select id="participants" name="participants" required>
                                        <option value="">Pilih jumlah pendaki</option>
                                        <option value="1">1 Orang</option>
                                        <option value="2">2 Orang</option>
                                        <option value="3">3 Orang</option>
                                        <option value="4">4 Orang</option>
                                        <option value="5">5 Orang</option>
                                        <option value="6+">6+ Orang (Grup)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-back" id="backStep2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn-next" id="nextStep2" disabled>
                                    Lanjutkan <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Step 3: Layanan Tambahan -->
                        <div class="form-step" id="step-3">
                            <div class="step-header">
                                <h3><i class="fas fa-plus-circle"></i> Layanan Tambahan</h3>
                                <p>Pilih layanan tambahan untuk kenyamanan pendakian Anda</p>
                            </div>
                            <div class="services-grid">
                                <div class="service-card" data-service="guide">
                                    <div class="service-icon">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="service-info">
                                        <h4>Jasa Guide</h4>
                                        <p>Pemandu berpengalaman untuk memandu perjalanan Anda</p>
                                        <span class="service-price">Rp 150.000/hari</span>
                                    </div>
                                    <div class="service-toggle">
                                        <input type="checkbox" id="guide" name="services[]" value="guide">
                                        <label for="guide" class="toggle-switch"></label>
                                    </div>
                                </div>
                                <div class="service-card" data-service="porter">
                                    <div class="service-icon">
                                        <i class="fas fa-hiking"></i>
                                    </div>
                                    <div class="service-info">
                                        <h4>Jasa Porter</h4>
                                        <p>Bantuan membawa barang bawaan hingga 15kg</p>
                                        <span class="service-price">Rp 100.000/hari</span>
                                    </div>
                                    <div class="service-toggle">
                                        <input type="checkbox" id="porter" name="services[]" value="porter">
                                        <label for="porter" class="toggle-switch"></label>
                                    </div>
                                </div>
                                <div class="service-card" data-service="ojek">
                                    <div class="service-icon">
                                        <i class="fas fa-motorcycle"></i>
                                    </div>
                                    <div class="service-info">
                                        <h4>Jasa Ojek</h4>
                                        <p>Transportasi dari basecamp ke pos pendakian</p>
                                        <span class="service-price">Rp 50.000/orang</span>
                                    </div>
                                    <div class="service-toggle">
                                        <input type="checkbox" id="ojek" name="services[]" value="ojek">
                                        <label for="ojek" class="toggle-switch"></label>
                                    </div>
                                </div>
                                <div class="service-card" data-service="basecamp">
                                    <div class="service-icon">
                                        <i class="fas fa-campground"></i>
                                    </div>
                                    <div class="service-info">
                                        <h4>Sewa Basecamp</h4>
                                        <p>Tempat istirahat sebelum dan sesudah pendakian</p>
                                        <span class="service-price">Rp 75.000/malam</span>
                                    </div>
                                    <div class="service-toggle">
                                        <input type="checkbox" id="basecamp" name="services[]" value="basecamp">
                                        <label for="basecamp" class="toggle-switch"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-back" id="backStep3">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn-next" id="nextStep3">
                                    Lanjutkan <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Step 4: Konfirmasi & Pembayaran -->
                        <div class="form-step" id="step-4">
                            <div class="step-header">
                                <h3><i class="fas fa-credit-card"></i> Konfirmasi & Pembayaran</h3>
                                <p>Periksa detail pemesanan dan lakukan pembayaran</p>
                            </div>
                            <div class="booking-summary" id="bookingSummary">
                                <!-- Summary will be populated dynamically -->
                            </div>
                            <div class="payment-methods">
                                <h4>Metode Pembayaran</h4>
                                <div class="payment-options">
                                    <div class="payment-option">
                                        <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" checked>
                                        <label for="bank_transfer">
                                            <i class="fas fa-university"></i>
                                            Transfer Bank
                                        </label>
                                    </div>
                                    <div class="payment-option">
                                        <input type="radio" id="e_wallet" name="payment_method" value="e_wallet">
                                        <label for="e_wallet">
                                            <i class="fas fa-mobile-alt"></i>
                                            E-Wallet
                                        </label>
                                    </div>
                                    <div class="payment-option">
                                        <input type="radio" id="credit_card" name="payment_method" value="credit_card">
                                        <label for="credit_card">
                                            <i class="fas fa-credit-card"></i>
                                            Kartu Kredit
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="terms-agreement">
                                <label class="checkbox-container">
                                    <input type="checkbox" id="agreeTerms" required>
                                    <span class="checkmark"></span>
                                    Saya setuju dengan <a href="#" target="_blank">syarat dan ketentuan</a> yang berlaku
                                </label>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-back" id="backStep4">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="submit" class="btn-submit" id="submitBooking">
                                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Booking Summary Sidebar -->
                <div class="booking-sidebar">
                    <div class="sidebar-card">
                        <h3>Ringkasan Pemesanan</h3>
                        <div class="summary-content" id="sidebarSummary">
                            <div class="summary-placeholder">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Pilih gunung untuk melihat ringkasan pemesanan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="scripts/form-pemesanan.js"></script>
</body>

</html>