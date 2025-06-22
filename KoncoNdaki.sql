-- Create database
CREATE DATABASE IF NOT EXISTS koncondaki;
USE koncondaki;

-- Table users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'pendaki',
    created_at DATETIME NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table gunung
CREATE TABLE gunung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_gunung VARCHAR(100) NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    ketinggian INT NOT NULL COMMENT 'dalam meter',
    deskripsi TEXT,
    status ENUM('aktif', 'tidak aktif') DEFAULT 'aktif',
    kuota_pendaki_harian INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table jalur_pendakian
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

-- Table tiket
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

-- Table pemesanan_tiket
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

-- Table detail_pendaki
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

-- Table porter
CREATE TABLE porter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_porter VARCHAR(100) NOT NULL,
    gunung_id INT NOT NULL,
    harga_per_hari DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE
);

-- Table guide
CREATE TABLE guide (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_guide VARCHAR(100) NOT NULL,
    gunung_id INT NOT NULL,
    harga_per_hari DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE
);

-- Table ojek
CREATE TABLE ojek (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_driver VARCHAR(100) NOT NULL,
    gunung_id INT NOT NULL,
    nomor_plat VARCHAR(20) NOT NULL,
    jenis_kendaraan VARCHAR(50) NOT NULL,
    harga_per_trip DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE
);

-- Table basecamp
CREATE TABLE basecamp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gunung_id INT NOT NULL,
    nama_basecamp VARCHAR(100) NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    kapasitas INT NOT NULL,
    fasilitas TEXT,
    harga_per_malam DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gunung_id) REFERENCES gunung(id) ON DELETE CASCADE
);

-- Table pemesanan_porter
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

-- Table pemesanan_guide
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

-- Table pemesanan_ojek
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

-- Table pemesanan_basecamp
CREATE TABLE pemesanan_basecamp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    basecamp_id INT NOT NULL,
    pemesanan_tiket_id INT NOT NULL,
    tanggal_check_in DATE NOT NULL,
    tanggal_check_out DATE NOT NULL,
    jumlah_malam INT NOT NULL,
    jumlah_orang INT NOT NULL,
    total_harga DECIMAL(10, 2) NOT NULL,
    status ENUM('menunggu', 'dibayar', 'dibatalkan', 'selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (basecamp_id) REFERENCES basecamp(id) ON DELETE CASCADE,
    FOREIGN KEY (pemesanan_tiket_id) REFERENCES pemesanan_tiket(id) ON DELETE CASCADE
);

-- Table pembayaran
CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pemesanan_tiket_id INT NOT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    total_pembayaran DECIMAL(10, 2) NOT NULL,
    status_pembayaran ENUM('pending', 'berhasil', 'gagal') DEFAULT 'pending',
    bukti_pembayaran VARCHAR(255),
    tanggal_pembayaran TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pemesanan_tiket_id) REFERENCES pemesanan_tiket(id) ON DELETE CASCADE
);

-- Tambahkan data contoh untuk gunung
INSERT INTO gunung (nama_gunung, lokasi, ketinggian, deskripsi, kuota_pendaki_harian) VALUES
('Gunung Semeru', 'Jawa Timur', 3676, 'Gunung tertinggi di Pulau Jawa', 150),
('Gunung Merbabu', 'Jawa Tengah', 3145, 'Gunung yang terletak di antara Kabupaten Magelang dan Kabupaten Boyolali', 100),
('Gunung Lawu', 'Jawa Tengah/Jawa Timur', 3265, 'Gunung yang terletak di perbatasan Jawa Tengah dan Jawa Timur', 120),
('Gunung Gede', 'Jawa Barat', 2958, 'Gunung di Taman Nasional Gunung Gede Pangrango', 90),
('Gunung Prau', 'Jawa Tengah', 2565, 'Gunung yang terkenal dengan pemandangan sunrise yang spektakuler', 150),
('Gunung Arjuno', 'Jawa Timur', 3339, 'Salah satu gunung berapi aktif di Jawa Timur yang memiliki pemandangan indah', 100),
('Gunung Sindoro', 'Jawa Tengah', 3150, 'Gunung berapi kerucut yang memiliki kawah di puncaknya', 120),
('Gunung Sumbing', 'Jawa Tengah', 3371, 'Gunung berapi dengan pemandangan savana yang luas di sekitarnya', 120),
('Gunung Slamet', 'Jawa Tengah', 3428, 'Gunung berapi tertinggi di Jawa Tengah', 100),
('Gunung Ciremai', 'Jawa Barat', 3078, 'Gunung tertinggi di Jawa Barat yang berada di perbatasan Kuningan dan Majalengka', 90),
('Gunung Pangrango', 'Jawa Barat', 3019, 'Gunung yang berdampingan dengan Gunung Gede di Taman Nasional Gede Pangrango', 90),
('Gunung Salak', 'Jawa Barat', 2211, 'Gunung kompleks dengan beberapa puncak yang terletak di selatan Bogor', 80),
('Gunung Ungaran', 'Jawa Tengah', 2050, 'Gunung berapi yang tidak aktif dengan pemandian air panas di sekitarnya', 120),
('Gunung Ijen', 'Jawa Timur', 2386, 'Gunung dengan danau kawah berwarna biru kehijauan dan fenomena api biru', 100),
('Gunung Welirang', 'Jawa Timur', 3156, 'Gunung berapi yang memiliki tambang belerang di kawahnya', 90),
('Gunung Raung', 'Jawa Timur', 3344, 'Gunung dengan kaldera yang luas dan tebing yang curam', 80),
('Gunung Bromo', 'Jawa Timur', 2329, 'Gunung berapi aktif yang terkenal dengan lautan pasirnya', 200),
('Gunung Argopuro', 'Jawa Timur', 3088, 'Gunung dengan jalur pendakian terpanjang di Pulau Jawa', 90),
('Gunung Papandayan', 'Jawa Barat', 2665, 'Gunung berapi dengan kawah aktif dan hutan edelweiss', 110),
('Gunung Penanggungan', 'Jawa Timur', 1653, 'Gunung yang memiliki banyak situs purbakala dan candi', 130),
('Gunung Guntur', 'Jawa Barat', 2249, 'Gunung berapi aktif dengan medan pendakian berupa batuan vulkanik', 100),
('Gunung Tangkuban Perahu', 'Jawa Barat', 2084, 'Gunung berapi yang terkenal dengan bentuknya menyerupai perahu terbalik', 200),
('Gunung Malabar', 'Jawa Barat', 2321, 'Gunung yang terletak di selatan kota Bandung', 90),
('Gunung Halimun', 'Jawa Barat/Banten', 1929, 'Gunung yang terletak di Taman Nasional Halimun Salak', 85),
('Gunung Cikuray', 'Jawa Barat', 2821, 'Gunung dengan bentuk kerucut sempurna di Garut', 95),
('Gunung Merapi', 'Jawa Tengah/Yogyakarta', 2930, 'Gunung berapi aktif yang terkenal dengan aktivitas vulkaniknya', 100),
('Gunung Rogojembangan', 'Jawa Tengah', 2117, 'Gunung yang terletak di Kabupaten Pekalongan', 80),
('Gunung Bismo', 'Jawa Tengah', 2365, 'Gunung yang terletak di Kabupaten Wonosobo', 85),
('Gunung Kawi', 'Jawa Timur', 2651, 'Gunung yang terkenal dengan tempat spiritualnya', 90),
('Gunung Butak', 'Jawa Timur', 2868, 'Gunung yang terletak di Kabupaten Malang', 85),
('Gunung Pulosari', 'Banten', 1346, 'Gunung berapi tidak aktif yang terletak di Kabupaten Pandeglang', 100),
('Gunung Karang', 'Banten', 1778, 'Gunung tertinggi di provinsi Banten', 95);

-- Tambahkan data contoh untuk jalur pendakian
INSERT INTO jalur_pendakian (gunung_id, nama_jalur, estimasi_waktu_tempuh, tingkat_kesulitan, deskripsi_jalur) VALUES
(1, 'Jalur Ranu Pani', 12, 'sulit', 'Jalur paling populer untuk mendaki Gunung Semeru'),
(1, 'Jalur Tumpang', 15, 'sangat sulit', 'Jalur alternatif pendakian Gunung Semeru'),
(2, 'Jalur Selo', 8, 'sedang', 'Jalur pendakian dari desa Selo'),
(2, 'Jalur Wekas', 10, 'sulit', 'Jalur pendakian dari Kopeng'),
(3, 'Jalur Cemoro Sewu', 6, 'mudah', 'Jalur pendakian yang paling populer di Gunung Lawu'),
(3, 'Jalur Cemoro Kandang', 7, 'sedang', 'Jalur alternatif pendakian Gunung Lawu'),
(4, 'Jalur Cibodas', 5, 'mudah', 'Jalur pendakian dari Cibodas'),
(4, 'Jalur Gunung Putri', 7, 'sedang', 'Jalur alternatif pendakian Gunung Gede'),
(5, 'Jalur Patak Banteng', 3, 'mudah', 'Jalur pendakian paling populer di Gunung Prau'),
(5, 'Jalur Dieng', 4, 'mudah', 'Jalur alternatif pendakian Gunung Prau');

-- Tambahkan data contoh untuk tiket
INSERT INTO tiket (gunung_id, jalur_id, harga) VALUES
(1, 1, 25000),
(1, 2, 25000),
(2, 3, 20000),
(2, 4, 20000),
(3, 5, 15000),
(3, 6, 15000),
(4, 7, 18000),
(4, 8, 18000),
(5, 9, 15000),
(5, 10, 15000);

-- Tambahkan data contoh untuk porter
INSERT INTO porter (nama_porter, gunung_id, harga_per_hari, deskripsi, status) VALUES
('Bambang', 1, 150000, 'Porter berpengalaman di Gunung Semeru', 'tersedia'),
('Joko', 1, 150000, 'Porter berpengalaman di Gunung Semeru', 'tersedia'),
('Dedi', 2, 120000, 'Porter berpengalaman di Gunung Merbabu', 'tersedia'),
('Rudi', 2, 120000, 'Porter berpengalaman di Gunung Merbabu', 'tersedia'),
('Agus', 3, 100000, 'Porter berpengalaman di Gunung Lawu', 'tersedia'),
('Budi', 3, 100000, 'Porter berpengalaman di Gunung Lawu', 'tersedia'),
('Eko', 4, 120000, 'Porter berpengalaman di Gunung Gede', 'tersedia'),
('Feri', 4, 120000, 'Porter berpengalaman di Gunung Gede', 'tersedia'),
('Hadi', 5, 80000, 'Porter berpengalaman di Gunung Prau', 'tersedia'),
('Irwan', 5, 80000, 'Porter berpengalaman di Gunung Prau', 'tersedia');

-- Tambahkan data contoh untuk guide
INSERT INTO guide (nama_guide, gunung_id, harga_per_hari, deskripsi, status) VALUES
('Surya', 1, 200000, 'Guide berpengalaman di Gunung Semeru', 'tersedia'),
('Teguh', 1, 200000, 'Guide berpengalaman di Gunung Semeru', 'tersedia'),
('Umar', 2, 170000, 'Guide berpengalaman di Gunung Merbabu', 'tersedia'),
('Wahyu', 2, 170000, 'Guide berpengalaman di Gunung Merbabu', 'tersedia'),
('Yanto', 3, 150000, 'Guide berpengalaman di Gunung Lawu', 'tersedia'),
('Zaenal', 3, 150000, 'Guide berpengalaman di Gunung Lawu', 'tersedia'),
('Amri', 4, 180000, 'Guide berpengalaman di Gunung Gede', 'tersedia'),
('Bayu', 4, 180000, 'Guide berpengalaman di Gunung Gede', 'tersedia'),
('Candra', 5, 130000, 'Guide berpengalaman di Gunung Prau', 'tersedia'),
('Dwi', 5, 130000, 'Guide berpengalaman di Gunung Prau', 'tersedia');

-- Tambahkan data contoh untuk ojek
INSERT INTO ojek (nama_driver, gunung_id, nomor_plat, jenis_kendaraan, harga_per_trip, deskripsi, status) VALUES
('Edi', 1, 'N 1234 AB', 'Motor Trail', 50000, 'Ojek ke base camp Gunung Semeru', 'tersedia'),
('Faisal', 1, 'N 2345 BC', 'Motor Trail', 50000, 'Ojek ke base camp Gunung Semeru', 'tersedia'),
('Guntur', 2, 'AA 3456 CD', 'Motor Trail', 40000, 'Ojek ke base camp Gunung Merbabu', 'tersedia'),
('Herman', 2, 'AA 4567 DE', 'Motor Trail', 40000, 'Ojek ke base camp Gunung Merbabu', 'tersedia'),
('Iwan', 3, 'AD 5678 EF', 'Motor Trail', 35000, 'Ojek ke base camp Gunung Lawu', 'tersedia'),
('Jafar', 3, 'AD 6789 FG', 'Motor Trail', 35000, 'Ojek ke base camp Gunung Lawu', 'tersedia'),
('Komar', 4, 'F 7890 GH', 'Motor Trail', 45000, 'Ojek ke base camp Gunung Gede', 'tersedia'),
('Lukman', 4, 'F 8901 HI', 'Motor Trail', 45000, 'Ojek ke base camp Gunung Gede', 'tersedia'),
('Malik', 5, 'AA 9012 IJ', 'Motor Trail', 30000, 'Ojek ke base camp Gunung Prau', 'tersedia'),
('Niko', 5, 'AA 0123 JK', 'Motor Trail', 30000, 'Ojek ke base camp Gunung Prau', 'tersedia');

-- Tambahkan data contoh untuk basecamp
INSERT INTO basecamp (gunung_id, nama_basecamp, lokasi, kapasitas, fasilitas, harga_per_malam, deskripsi, status) VALUES
('1', 'Basecamp Ranu Pani', 'Desa Ranu Pani', 50, 'Toilet, Kamar Mandi, Warung Makan', 75000, 'Basecamp untuk pendakian Gunung Semeru', 'tersedia'),
('1', 'Basecamp Tumpang', 'Desa Tumpang', 40, 'Toilet, Kamar Mandi, Musala', 70000, 'Basecamp alternatif untuk pendakian Gunung Semeru', 'tersedia'),
('2', 'Basecamp Selo', 'Desa Selo', 30, 'Toilet, Kamar Mandi, Warung Makan', 60000, 'Basecamp untuk pendakian Gunung Merbabu', 'tersedia'),
('2', 'Basecamp Kopeng', 'Desa Kopeng', 35, 'Toilet, Kamar Mandi, Musala', 65000, 'Basecamp alternatif untuk pendakian Gunung Merbabu', 'tersedia'),
('3', 'Basecamp Cemoro Sewu', 'Desa Cemoro Sewu', 40, 'Toilet, Kamar Mandi, Warung Makan', 55000, 'Basecamp untuk pendakian Gunung Lawu', 'tersedia'),
('3', 'Basecamp Cemoro Kandang', 'Desa Cemoro Kandang', 35, 'Toilet, Kamar Mandi, Musala', 50000, 'Basecamp alternatif untuk pendakian Gunung Lawu', 'tersedia'),
('4', 'Basecamp Cibodas', 'Cibodas', 45, 'Toilet, Kamar Mandi, Warung Makan', 65000, 'Basecamp untuk pendakian Gunung Gede', 'tersedia'),
('4', 'Basecamp Gunung Putri', 'Desa Gunung Putri', 30, 'Toilet, Kamar Mandi, Musala', 60000, 'Basecamp alternatif untuk pendakian Gunung Gede', 'tersedia'),
('5', 'Basecamp Patak Banteng', 'Patak Banteng', 25, 'Toilet, Kamar Mandi, Warung Makan', 50000, 'Basecamp untuk pendakian Gunung Prau', 'tersedia'),
('5', 'Basecamp Dieng', 'Dieng', 30, 'Toilet, Kamar Mandi, Musala', 55000, 'Basecamp alternatif untuk pendakian Gunung Prau', 'tersedia');
