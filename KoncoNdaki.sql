--
-- File: KoncoNdaki.sql
-- Deskripsi: Skema database lengkap untuk aplikasi KoncoNdaki.
-- Revisi: 3.1 (Role 'basecamp' ditambahkan, tabel basecamp disesuaikan)
--

-- Membuat database jika belum ada
CREATE DATABASE IF NOT EXISTS koncondaki;
USE koncondaki;

--
-- Struktur tabel untuk `users`
-- Keterangan: Tabel utama untuk semua pengguna aplikasi.
-- Revisi: Role 'pengelola' dihapus dan menjadi bagian dari 'admin'.
-- Revisi 3.1: Role 'basecamp' ditambahkan.
--
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    -- UPDATED: Menambahkan 'basecamp' ke dalam ENUM
    role ENUM('pendaki', 'porter', 'guide', 'ojek', 'admin', 'basecamp') NOT NULL DEFAULT 'pendaki',
    created_at DATETIME NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--
-- Struktur tabel untuk `gunung`
-- Keterangan: Menyimpan data master gunung.
-- Revisi: Kolom 'pengelola_id' diubah menjadi 'admin_id'.
--
CREATE TABLE gunung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_gunung VARCHAR(100) NOT NULL,
    foto_gunung VARCHAR(255) NULL COMMENT 'Path ke file gambar gunung',
    lokasi VARCHAR(255) NOT NULL,
    ketinggian INT NOT NULL COMMENT 'dalam meter',
    deskripsi TEXT,
    status ENUM('buka', 'tutup') DEFAULT 'buka',
    kuota_pendaki_harian INT NOT NULL,
    admin_id INT NULL COMMENT 'User ID dari admin yang mengelola gunung',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL
);

--
-- Struktur tabel untuk `jalur_pendakian`
--
CREATE TABLE jalur_pendakian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gunung_id INT NOT NULL,
    nama_jalur VARCHAR(100) NOT NULL,
    estimasi_waktu_tempuh INT NOT NULL COMMENT 'dalam jam',
    tingkat_kesulitan ENUM('mudah', 'sedang', 'sulit', 'sangat sulit') NOT NULL,
    deskripsi_jalur TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk `tiket`
--
CREATE TABLE tiket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gunung_id INT NOT NULL,
    jalur_id INT NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE,
    FOREIGN KEY (jalur_id) REFERENCES jalur_pendakian(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk `pemesanan_tiket`
--
CREATE TABLE pemesanan_tiket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tiket_id INT NOT NULL,
    tanggal_pendakian DATE NOT NULL,
    tanggal_turun DATE NOT NULL,
    jumlah_pendaki INT NOT NULL,
    status_pemesanan ENUM('menunggu', 'dibayar', 'dibatalkan', 'selesai') DEFAULT 'menunggu',
    total_harga DECIMAL(10, 2) NOT NULL,
    bukti_pembayaran VARCHAR(255),
    tanggal_pemesanan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tiket_id) REFERENCES tiket(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk `detail_pendaki`
--
CREATE TABLE detail_pendaki (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pemesanan_tiket_id INT NOT NULL,
    nama_pendaki VARCHAR(100) NOT NULL,
    no_identitas VARCHAR(20) NOT NULL,
    alamat TEXT,
    no_telp VARCHAR(20),
    jenis_kelamin ENUM('Laki-laki', 'Perempuan'),
    tanggal_lahir DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pemesanan_tiket_id) REFERENCES pemesanan_tiket(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk layanan `porter`
--
CREATE TABLE porter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    gunung_id INT NOT NULL,
    harga_per_hari DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk layanan `guide`
--
CREATE TABLE guide (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    gunung_id INT NOT NULL,
    harga_per_hari DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk layanan `ojek`
--
CREATE TABLE ojek (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    gunung_id INT NOT NULL,
    nomor_plat VARCHAR(20) NOT NULL,
    jenis_kendaraan VARCHAR(50) NOT NULL,
    harga_per_trip DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk `basecamp`
-- Revisi 3.1: Menambahkan user_id untuk pengelola basecamp.
--
CREATE TABLE basecamp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gunung_id INT NOT NULL,
    -- UPDATED: Menambahkan user_id untuk menautkan ke pengelola
    user_id INT NULL UNIQUE COMMENT 'User ID dari pengelola basecamp',
    nama_basecamp VARCHAR(100) NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    kapasitas INT NOT NULL,
    fasilitas TEXT,
    harga_per_malam DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE,
    -- UPDATED: Menambahkan Foreign Key Constraint untuk user_id
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

--
-- Struktur tabel untuk `pemesanan_porter`
--
CREATE TABLE pemesanan_porter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    porter_id INT NOT NULL,
    pemesanan_tiket_id INT NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    jumlah_hari INT NOT NULL,
    total_harga DECIMAL(10, 2) NOT NULL,
    status ENUM('menunggu', 'dibayar', 'dibatalkan', 'selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (porter_id) REFERENCES porter(id) ON DELETE CASCADE,
    FOREIGN KEY (pemesanan_tiket_id) REFERENCES pemesanan_tiket(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk `pemesanan_guide`
--
CREATE TABLE pemesanan_guide (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    guide_id INT NOT NULL,
    pemesanan_tiket_id INT NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    jumlah_hari INT NOT NULL,
    total_harga DECIMAL(10, 2) NOT NULL,
    status ENUM('menunggu', 'dibayar', 'dibatalkan', 'selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (guide_id) REFERENCES guide(id) ON DELETE CASCADE,
    FOREIGN KEY (pemesanan_tiket_id) REFERENCES pemesanan_tiket(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk `pemesanan_ojek`
--
CREATE TABLE pemesanan_ojek (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ojek_id INT NOT NULL,
    pemesanan_tiket_id INT NOT NULL,
    tanggal_jemput DATETIME NOT NULL,
    lokasi_jemput VARCHAR(255) NOT NULL,
    lokasi_tujuan VARCHAR(255) NOT NULL,
    jumlah_penumpang INT NOT NULL,
    total_harga DECIMAL(10, 2) NOT NULL,
    status ENUM('menunggu', 'dibayar', 'dibatalkan', 'selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ojek_id) REFERENCES ojek(id) ON DELETE CASCADE,
    FOREIGN KEY (pemesanan_tiket_id) REFERENCES pemesanan_tiket(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk `pemesanan_basecamp`
--
CREATE TABLE pemesanan_basecamp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    basecamp_id INT NOT NULL,
    pemesanan_tiket_id INT NOT NULL,
    tanggal_check_in DATE NOT NULL,
    tanggal_check_out DATE NOT NULL,
    jumlah_malam INT NOT NULL,
    total_harga DECIMAL(10, 2) NOT NULL,
    status ENUM('menunggu', 'dibayar', 'dibatalkan', 'selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (basecamp_id) REFERENCES basecamp(id) ON DELETE CASCADE,
    FOREIGN KEY (pemesanan_tiket_id) REFERENCES pemesanan_tiket(id) ON DELETE CASCADE
);

--
-- Struktur tabel untuk `pembayaran`
--
CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pemesanan_tiket_id INT NULL,
    pemesanan_porter_id INT NULL,
    pemesanan_guide_id INT NULL,
    pemesanan_ojek_id INT NULL,
    pemesanan_basecamp_id INT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    jumlah_pembayaran DECIMAL(10, 2) NOT NULL,
    tanggal_pembayaran TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_pembayaran ENUM('berhasil', 'gagal', 'menunggu') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


--
-- ====================================================================================
-- TABEL BARU BERDASARKAN KEBUTUHAN
-- ====================================================================================
--

--
-- BARU: Struktur tabel untuk `notifikasi`
-- Keterangan: Digunakan oleh admin untuk mengirim pesan ke penyedia layanan.
--
CREATE TABLE notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    penerima_id INT NOT NULL COMMENT 'User ID porter, guide, atau ojek',
    pengirim_id INT NOT NULL COMMENT 'User ID pengirim (admin)',
    judul VARCHAR(255) NOT NULL,
    pesan TEXT NOT NULL,
    tipe ENUM('info', 'penting', 'promo') DEFAULT 'info',
    url VARCHAR(255) NULL COMMENT 'Link jika notifikasi bisa diklik',
    status_baca ENUM('belum', 'sudah') DEFAULT 'belum',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penerima_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pengirim_id) REFERENCES users(id) ON DELETE CASCADE
);

--
-- BARU: Struktur tabel untuk `log_aktivitas`
-- Keterangan: (Opsional) Mencatat aktivitas penting yang dilakukan admin.
--
CREATE TABLE log_aktivitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    level ENUM('info', 'warning', 'danger') DEFAULT 'info',
    aktivitas VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);