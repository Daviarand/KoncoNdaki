# KoncoNdaki - Platform Pemesanan Tiket Pendakian Gunung

KoncoNdaki adalah platform web untuk pemesanan tiket pendakian gunung di Pulau Jawa. Sistem ini dibangun dengan PHP, MySQL, dan JavaScript dengan fitur autentikasi user dan manajemen role.

## 🏔️ Fitur Utama

### Untuk Pendaki (Role: pendaki)
- ✅ Registrasi dan login akun
- ✅ Pemesanan tiket pendakian
- ✅ Informasi gunung dan jalur pendakian
- ✅ Manajemen profil pribadi
- ✅ Riwayat pemesanan tiket

### Untuk Layanan (Role: layanan)
- ✅ Dashboard khusus layanan
- ✅ Kelola layanan pendakian
- ✅ Monitoring pemesanan
- ✅ Laporan kinerja

### Untuk Admin (Role: admin)
- ✅ Dashboard admin
- ✅ Manajemen user
- ✅ Manajemen gunung dan jalur
- ✅ Monitoring sistem

### Untuk Pengelola (Role: pengelola)
- ✅ Dashboard pengelola
- ✅ Manajemen tiket
- ✅ Laporan keuangan

## 🚀 Cara Instalasi

### Prerequisites
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- XAMPP/WAMP/MAMP

### Langkah Instalasi

1. **Clone atau download project**
   ```bash
   git clone [repository-url]
   cd KoncoNdaki
   ```

2. **Setup Database**
   - Buka phpMyAdmin
   - Buat database baru dengan nama `koncondaki`
   - Import file `KoncoNdaki.sql`

3. **Konfigurasi Database**
   - Edit file `config/database.php`
   - Sesuaikan kredensial database:
   ```php
   $host = 'localhost';
   $dbname = 'koncondaki';
   $username = 'root';
   $password = '';
   ```

4. **Setup Web Server**
   - Letakkan folder project di direktori web server
   - Pastikan folder dapat diakses melalui browser

5. **Test Aplikasi**
   - Buka browser
   - Akses `http://localhost/KoncoNdaki`
   - Halaman login akan muncul

## 📁 Struktur File

```
KoncoNdaki/
├── auth/                    # Sistem autentikasi
│   ├── login.php           # Proses login
│   ├── register.php        # Proses registrasi
│   ├── logout.php          # Proses logout
│   └── check_auth.php      # Validasi autentikasi
├── api/                    # API endpoints
│   ├── get_user_profile.php
│   └── update_user_profile.php
├── config/                 # Konfigurasi
│   └── database.php        # Koneksi database
├── images/                 # Asset gambar
├── scripts/                # JavaScript files
├── styles/                 # CSS files
├── dashboard.php           # Dashboard pendaki
├── dashboard-layanan.php   # Dashboard layanan
├── login.php              # Halaman login
├── register.php           # Halaman registrasi
├── profile.php            # Halaman profil
├── form-pemesanan.php     # Form pemesanan
├── info-gunung.php        # Info gunung
├── cara-pemesanan.php     # Panduan pemesanan
└── KoncoNdaki.sql         # Database structure
```

## 🔐 Sistem Autentikasi

### Role User
1. **pendaki** - User biasa yang dapat memesan tiket
2. **layanan** - Penyedia layanan pendakian
3. **admin** - Administrator sistem
4. **pengelola** - Pengelola tiket dan keuangan

### Flow Autentikasi
1. User mengakses halaman login/register
2. Data divalidasi dan disimpan ke database
3. Session dibuat dengan informasi user
4. Redirect ke dashboard sesuai role
5. Halaman dilindungi dengan middleware autentikasi

## 🗄️ Database Schema

### Tabel Utama
- **users** - Data user dengan role
- **gunung** - Informasi gunung
- **jalur_pendakian** - Jalur pendakian per gunung
- **tiket** - Harga tiket per jalur
- **pemesanan_tiket** - Data pemesanan
- **detail_pendaki** - Data pendaki per pemesanan

### Struktur Tabel Users
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_telp VARCHAR(20) NOT NULL,
    alamat TEXT,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan'),
    tanggal_lahir DATE,
    role ENUM('pendaki', 'layanan', 'admin', 'pengelola') DEFAULT 'pendaki',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🎨 Fitur UI/UX

### Design System
- **Color Scheme**: Green (#16a34a) sebagai primary color
- **Typography**: Modern sans-serif fonts
- **Layout**: Responsive grid system
- **Components**: Card-based design

### Responsive Design
- Mobile-first approach
- Breakpoints: 480px, 768px, 1024px
- Flexible grid layouts
- Touch-friendly interactions

## 🔧 API Endpoints

### Authentication
- `POST auth/login.php` - Login user
- `POST auth/register.php` - Registrasi user
- `GET auth/logout.php` - Logout user

### User Profile
- `GET api/get_user_profile.php` - Ambil data profil
- `POST api/update_user_profile.php` - Update profil

## 🛡️ Security Features

### Password Security
- Password hashing menggunakan `password_hash()`
- Validasi password strength
- Protection against brute force

### Session Management
- Secure session handling
- Session timeout
- CSRF protection

### Input Validation
- Server-side validation
- SQL injection prevention
- XSS protection

## 📱 Responsive Features

### Mobile Optimization
- Touch-friendly buttons
- Swipe gestures
- Mobile navigation menu
- Optimized images

### Cross-browser Support
- Chrome, Firefox, Safari, Edge
- Progressive enhancement
- Fallback styles

## 🚀 Deployment

### Production Setup
1. **Server Requirements**
   - PHP 7.4+
   - MySQL 5.7+
   - HTTPS enabled
   - SSL certificate

2. **Security Checklist**
   - Update database credentials
   - Enable error logging
   - Set proper file permissions
   - Configure backup system

3. **Performance Optimization**
   - Enable caching
   - Optimize images
   - Minify CSS/JS
   - Database indexing

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials
   - Verify MySQL service running
   - Test connection manually

2. **Session Not Working**
   - Check PHP session configuration
   - Verify session directory permissions
   - Clear browser cookies

3. **Upload Issues**
   - Check file permissions
   - Verify upload directory exists
   - Check PHP upload limits

## 📞 Support

Untuk bantuan dan dukungan:
- Email: support@koncondaki.com
- Documentation: [docs.koncondaki.com]
- Issues: [GitHub Issues]

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

1. Fork the project
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📈 Roadmap

### Version 2.0 (Planned)
- [ ] Mobile app development
- [ ] Payment gateway integration
- [ ] Real-time notifications
- [ ] Advanced reporting
- [ ] Multi-language support

### Version 1.1 (In Progress)
- [ ] Email verification
- [ ] Password reset functionality
- [ ] Social media login
- [ ] Enhanced security features

---

**KoncoNdaki** - Your Mountain Adventure Partner 🏔️ 