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
    <title>Profile - KoncoNdaki</title>
    <meta name="description" content="Kelola profil akun KoncoNdaki Anda.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/profile-styles.css">
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
                                <a href="profile.php" class="profile-menu-item active">
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

                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>

                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link active">
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

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar-large">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info-header">
                        <h1 id="profileTitle"><?php echo htmlspecialchars($currentUser['nama']); ?></h1>
                        <p id="profileEmail"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                        <span class="profile-role"><?php echo ucfirst($currentUser['role']); ?></span>
                    </div>
                </div>

                <!-- Profile Form -->
                <div class="profile-form-container">
                    <form id="profileForm" class="profile-form">
                        <div class="form-section">
                            <h3>Informasi Pribadi</h3>
                            
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($currentUser['nama']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" readonly>
                                <small>Email tidak dapat diubah</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="no_telp">Nomor Telepon</label>
                                <input type="tel" id="no_telp" name="no_telp" value="" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="3"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-save" id="saveBtn">
                                <i class="fas fa-save"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="scripts/script.js"></script>
    <script>
        // Load user profile data
        document.addEventListener('DOMContentLoaded', function() {
            loadUserProfile();
        });

        function loadUserProfile() {
            fetch('api/get_user_profile.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;
                        
                        // Fill form fields
                        document.getElementById('nama_lengkap').value = user.nama_lengkap || '';
                        document.getElementById('email').value = user.email || '';
                        document.getElementById('no_telp').value = user.no_telp || '';
                        document.getElementById('alamat').value = user.alamat || '';
                        document.getElementById('jenis_kelamin').value = user.jenis_kelamin || '';
                        document.getElementById('tanggal_lahir').value = user.tanggal_lahir || '';
                        
                        // Update profile header
                        document.getElementById('profileTitle').textContent = user.nama_lengkap;
                        document.getElementById('profileEmail').textContent = user.email;
                    } else {
                        showNotification('Gagal memuat data profil', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat memuat data', 'error');
                });
        }

        // Handle form submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const saveBtn = document.getElementById('saveBtn');
            const originalText = saveBtn.innerHTML;
            
            // Show loading state
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            saveBtn.disabled = true;
            
            fetch('api/update_user_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    
                    // Update profile header
                    const namaLengkap = document.getElementById('nama_lengkap').value;
                    document.getElementById('profileTitle').textContent = namaLengkap;
                    
                    // Update navbar profile name
                    const profileNames = document.querySelectorAll('#profileName, #menuProfileName, #mobileProfileName');
                    profileNames.forEach(element => {
                        element.textContent = namaLengkap;
                    });
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        });

        // Show notification function
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            
            const icon = type === 'success' ? 'fa-check-circle' : 
                         type === 'error' ? 'fa-exclamation-circle' : 
                         'fa-info-circle';
            
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="fas ${icon}"></i>
                    <span>${message}</span>
                </div>
                <button class="notification-close">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add styles
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                padding: 16px;
                z-index: 10000;
                max-width: 400px;
                border-left: 4px solid ${type === 'success' ? '#16a34a' : type === 'error' ? '#ef4444' : '#3b82f6'};
                animation: slideIn 0.3s ease;
            `;
            
            // Add animation styles
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                .notification-content {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    color: #1f2937;
                }
                
                .notification-content i {
                    color: ${type === 'success' ? '#16a34a' : type === 'error' ? '#ef4444' : '#3b82f6'};
                }
                
                .notification-close {
                    position: absolute;
                    top: 8px;
                    right: 8px;
                    background: none;
                    border: none;
                    color: #9ca3af;
                    cursor: pointer;
                    padding: 4px;
                    border-radius: 4px;
                }
                
                .notification-close:hover {
                    background: #f3f4f6;
                    color: #6b7280;
                }
            `;
            
            document.head.appendChild(style);
            document.body.appendChild(notification);
            
            // Close button functionality
            const closeButton = notification.querySelector('.notification-close');
            closeButton.addEventListener('click', () => {
                notification.remove();
                style.remove();
            });
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                    style.remove();
                }
            }, 5000);
        }
    </script>
</body>

</html>