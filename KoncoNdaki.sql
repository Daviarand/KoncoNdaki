-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 11:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koncondaki`
--

-- --------------------------------------------------------

--
-- Table structure for table `basecamp`
--

CREATE TABLE `basecamp` (
  `id` int(11) NOT NULL,
  `gunung_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'User ID dari pengelola basecamp',
  `nama_basecamp` varchar(100) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `fasilitas` text DEFAULT NULL,
  `harga_per_malam` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','tidak tersedia') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pendaki`
--

CREATE TABLE `detail_pendaki` (
  `id` int(11) NOT NULL,
  `pemesanan_tiket_id` int(11) NOT NULL,
  `nama_pendaki` varchar(100) NOT NULL,
  `no_identitas` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guide`
--

CREATE TABLE `guide` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gunung_id` int(11) NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','tidak tersedia') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gunung`
--

CREATE TABLE `gunung` (
  `id` int(11) NOT NULL,
  `nama_gunung` varchar(100) NOT NULL,
  `foto_gunung` varchar(255) DEFAULT NULL COMMENT 'Path ke file gambar gunung',
  `lokasi` varchar(255) NOT NULL,
  `ketinggian` int(11) NOT NULL COMMENT 'dalam meter',
  `deskripsi` text DEFAULT NULL,
  `status` enum('buka','tutup') DEFAULT 'buka',
  `kuota_pendaki_harian` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL COMMENT 'User ID dari admin yang mengelola gunung',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gunung`
--

INSERT INTO `gunung` (`id`, `nama_gunung`, `foto_gunung`, `lokasi`, `ketinggian`, `deskripsi`, `status`, `kuota_pendaki_harian`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 'Gunung Bromo', NULL, 'Jawa Timur', 2329, NULL, 'buka', 500, 8, '2025-07-04 07:34:13', '2025-07-04 07:47:43'),
(2, 'Gunung Merapi', NULL, 'Jawa Tengah', 2930, NULL, 'buka', 250, 9, '2025-07-04 07:34:13', '2025-07-04 07:49:05'),
(3, 'Gunung Semeru', NULL, 'Jawa Timur', 3676, NULL, 'buka', 600, 10, '2025-07-04 07:34:13', '2025-07-04 07:49:46'),
(4, 'Gunung Gede', NULL, 'Jawa Barat', 2958, NULL, 'buka', 800, NULL, '2025-07-04 07:34:13', '2025-07-04 07:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `jalur_pendakian`
--

CREATE TABLE `jalur_pendakian` (
  `id` int(11) NOT NULL,
  `gunung_id` int(11) NOT NULL,
  `nama_jalur` varchar(100) NOT NULL,
  `estimasi_waktu_tempuh` int(11) NOT NULL COMMENT 'dalam jam',
  `tingkat_kesulitan` enum('mudah','sedang','sulit','sangat sulit') NOT NULL,
  `deskripsi_jalur` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(50) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `harga_layanan` decimal(10,2) NOT NULL,
  `satuan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id`, `nama_layanan`, `deskripsi`, `harga_layanan`, `satuan`) VALUES
(1, 'guide', 'Pemandu berpengalaman', 150000.00, '/hari'),
(2, 'porter', 'Bantuan membawa barang', 100000.00, '/hari'),
(3, 'ojek', 'Transportasi ke pos awal', 50000.00, '/orang'),
(4, 'basecamp', 'Tempat istirahat sebelum pendakian', 75000.00, '/malam');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `level` enum('info','warning','danger') DEFAULT 'info',
  `aktivitas` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `penerima_id` int(11) NOT NULL COMMENT 'User ID porter, guide, atau ojek',
  `gunung_id` int(11) DEFAULT NULL,
  `pengirim_id` int(11) NOT NULL COMMENT 'User ID pengirim (admin)',
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('info','penting','promo') DEFAULT 'info',
  `url` varchar(255) DEFAULT NULL COMMENT 'Link jika notifikasi bisa diklik',
  `status_baca` enum('belum','sudah') DEFAULT 'belum',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `penerima_id`, `gunung_id`, `pengirim_id`, `judul`, `pesan`, `tipe`, `url`, `status_baca`, `created_at`) VALUES
(1, 15, 2, 9, 'Tugas Baru untuk Anda (Porter)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-A668B5\r\n- Nama Pemesan: Annuru Wulandari\r\n- Layanan yang Dibutuhkan: porter\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-08 09:06:45'),
(2, 15, 2, 9, 'Tugas Baru untuk Anda (Porter)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-A668B5\r\n- Nama Pemesan: Annuru Wulandari\r\n- Layanan yang Dibutuhkan: porter\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-08 09:06:59'),
(3, 19, 3, 10, 'Tugas Baru untuk Anda (Basecamp)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-88B641\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: basecamp\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-08 09:33:26'),
(4, 16, 1, 8, 'Tugas Baru untuk Anda (Ojek)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-F0C9C4\r\n- Nama Pemesan: Annuru Wulandari\r\n- Layanan yang Dibutuhkan: ojek\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-08 09:35:53');

-- --------------------------------------------------------

--
-- Table structure for table `ojek`
--

CREATE TABLE `ojek` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gunung_id` int(11) NOT NULL,
  `nomor_plat` varchar(20) NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL,
  `harga_per_trip` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','tidak tersedia') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `pemesanan_tiket_id` int(11) DEFAULT NULL,
  `pemesanan_porter_id` int(11) DEFAULT NULL,
  `pemesanan_guide_id` int(11) DEFAULT NULL,
  `pemesanan_ojek_id` int(11) DEFAULT NULL,
  `pemesanan_basecamp_id` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `jumlah_pembayaran` decimal(10,2) NOT NULL,
  `tanggal_pembayaran` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_pembayaran` enum('berhasil','gagal','menunggu') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tiket_id` int(11) NOT NULL,
  `kode_booking` varchar(20) NOT NULL,
  `tanggal_pendakian` date NOT NULL,
  `tanggal_turun` date NOT NULL,
  `jumlah_pendaki` int(11) NOT NULL,
  `subtotal_tiket` decimal(10,2) NOT NULL,
  `subtotal_layanan` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_harga` decimal(10,2) NOT NULL,
  `status_pemesanan` enum('menunggu pembayaran','pending','in progress','complete','rejected','dibatalkan') NOT NULL DEFAULT 'menunggu pembayaran',
  `tanggal_pemesanan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `user_id`, `tiket_id`, `kode_booking`, `tanggal_pendakian`, `tanggal_turun`, `jumlah_pendaki`, `subtotal_tiket`, `subtotal_layanan`, `total_harga`, `status_pemesanan`, `tanggal_pemesanan`) VALUES
(7, 11, 1, 'KNCD-7145C0', '2025-07-06', '2025-07-07', 1, 35000.00, 500000.00, 535000.00, 'complete', '2025-07-04 11:29:11'),
(8, 11, 1, 'KNCD-47E2F2', '2025-08-08', '2025-08-09', 1, 35000.00, 200000.00, 235000.00, 'in progress', '2025-07-04 11:33:56'),
(9, 11, 1, 'KNCD-6B710F', '2025-07-30', '2025-07-31', 1, 35000.00, 450000.00, 485000.00, 'in progress', '2025-07-04 11:48:22'),
(10, 12, 1, 'KNCD-F554C1', '2025-07-31', '2025-08-01', 1, 35000.00, 300000.00, 335000.00, 'in progress', '2025-07-08 08:50:23'),
(11, 12, 2, 'KNCD-A668B5', '2025-08-09', '2025-08-10', 1, 25000.00, 200000.00, 225000.00, 'in progress', '2025-07-08 08:50:50'),
(12, 12, 3, 'KNCD-72D25A', '2025-08-01', '2025-08-02', 1, 45000.00, 150000.00, 195000.00, 'in progress', '2025-07-08 08:51:03'),
(13, 12, 4, 'KNCD-46FF4C', '2025-07-09', '2025-07-10', 1, 30000.00, 50000.00, 80000.00, 'pending', '2025-07-08 08:51:16'),
(14, 14, 3, 'KNCD-88B641', '2025-07-17', '2025-07-18', 1, 45000.00, 150000.00, 195000.00, 'in progress', '2025-07-08 09:05:12'),
(15, 12, 1, 'KNCD-F0C9C4', '2025-07-17', '2025-07-18', 1, 35000.00, 50000.00, 85000.00, 'in progress', '2025-07-08 09:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_basecamp`
--

CREATE TABLE `pemesanan_basecamp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `basecamp_id` int(11) NOT NULL,
  `pemesanan_tiket_id` int(11) NOT NULL,
  `tanggal_check_in` date NOT NULL,
  `tanggal_check_out` date NOT NULL,
  `jumlah_malam` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('menunggu','dibayar','dibatalkan','selesai') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_guide`
--

CREATE TABLE `pemesanan_guide` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `pemesanan_tiket_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jumlah_hari` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('menunggu','dibayar','dibatalkan','selesai') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_layanan`
--

CREATE TABLE `pemesanan_layanan` (
  `id` int(11) NOT NULL,
  `pemesanan_id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `harga_saat_pesan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan_layanan`
--

INSERT INTO `pemesanan_layanan` (`id`, `pemesanan_id`, `layanan_id`, `jumlah`, `harga_saat_pesan`) VALUES
(11, 7, 1, 2, 300000.00),
(12, 7, 2, 2, 200000.00),
(13, 8, 3, 1, 50000.00),
(14, 8, 4, 2, 150000.00),
(15, 9, 1, 2, 300000.00),
(16, 9, 4, 2, 150000.00),
(17, 10, 1, 2, 300000.00),
(18, 11, 2, 2, 200000.00),
(19, 12, 4, 2, 150000.00),
(20, 13, 3, 1, 50000.00),
(21, 14, 4, 2, 150000.00),
(22, 15, 3, 1, 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_ojek`
--

CREATE TABLE `pemesanan_ojek` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ojek_id` int(11) NOT NULL,
  `pemesanan_tiket_id` int(11) NOT NULL,
  `tanggal_jemput` datetime NOT NULL,
  `lokasi_jemput` varchar(255) NOT NULL,
  `lokasi_tujuan` varchar(255) NOT NULL,
  `jumlah_penumpang` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('menunggu','dibayar','dibatalkan','selesai') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_porter`
--

CREATE TABLE `pemesanan_porter` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `porter_id` int(11) NOT NULL,
  `pemesanan_tiket_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jumlah_hari` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('menunggu','dibayar','dibatalkan','selesai') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_tiket`
--

CREATE TABLE `pemesanan_tiket` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tiket_id` int(11) NOT NULL,
  `tanggal_pendakian` date NOT NULL,
  `tanggal_turun` date NOT NULL,
  `jumlah_pendaki` int(11) NOT NULL,
  `status_pemesanan` enum('menunggu','dibayar','dibatalkan','selesai') DEFAULT 'menunggu',
  `total_harga` decimal(10,2) NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal_pemesanan` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `porter`
--

CREATE TABLE `porter` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gunung_id` int(11) NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','tidak tersedia') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `id` int(11) NOT NULL,
  `gunung_id` int(11) NOT NULL,
  `jalur_id` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiket_gunung`
--

CREATE TABLE `tiket_gunung` (
  `id` int(11) NOT NULL,
  `nama_gunung` varchar(100) NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `harga_tiket` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiket_gunung`
--

INSERT INTO `tiket_gunung` (`id`, `nama_gunung`, `lokasi`, `harga_tiket`, `created_at`) VALUES
(1, 'Gunung Bromo', 'Jawa Timur', 35000.00, '2025-07-04 10:27:55'),
(2, 'Gunung Merapi', 'Jawa Tengah', 25000.00, '2025-07-04 10:27:55'),
(3, 'Gunung Semeru', 'Jawa Timur', 45000.00, '2025-07-04 10:27:55'),
(4, 'Gunung Gede', 'Jawa Barat', 30000.00, '2025-07-04 10:27:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pendaki','porter','guide','ojek','admin','basecamp','pengelola_gunung') NOT NULL DEFAULT 'pendaki',
  `gunung_bertugas_id` int(11) DEFAULT NULL,
  `gunung_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `role`, `gunung_bertugas_id`, `gunung_id`, `created_at`, `updated_at`) VALUES
(1, 'admin', '1', 'admin@gmail.com', '0987387421233', '$2y$10$HMoT2tenyq66/VxGt.fraeQa9J696r5tkFgo8ZZORJoz.lXmIpXSS', 'admin', NULL, NULL, '2025-07-04 09:00:09', '2025-07-04 07:00:45'),
(8, 'pengelola', 'bromo', 'bromo@gmail.com', '0891292809123', '$2y$10$4Ju4TqDqKNigAkbheyY5/.jFEJZSMwpmkTIN1IQOqq7DAckGR9A6m', 'pengelola_gunung', NULL, 1, '2025-07-04 14:47:43', '2025-07-05 12:34:15'),
(9, 'pengelola', 'merapi', 'merapi@gmail.com', '0891292809123', '$2y$10$PRVJaV4qAaeGMMM1FB93Qup..xT8wsmIhH3xP6ld2QWz/39tI9tFq', 'pengelola_gunung', NULL, 2, '2025-07-04 14:49:05', '2025-07-05 12:34:47'),
(10, 'pengelola', 'semeru', 'semeru@gmail.com', '089628670822', '$2y$10$lxkg5LKOrJ8cbeU8GeJA5uNBGrcEiG.irVlbqARQwJdXpL7cH.MVW', 'pengelola_gunung', NULL, 3, '2025-07-04 14:49:46', '2025-07-05 12:35:00'),
(11, 'Agil', 'liam', 'liam@gmail.com', '09249814871', '$2y$10$I2rvRv/KWU2PNI0MLMgHJuftQuvvKkaJy9oZPElyL6I70532sg856', 'pendaki', NULL, NULL, '2025-07-04 14:52:04', '2025-07-04 07:52:04'),
(14, 'Daviar', 'Andrianoe', 'daviar.andrianoe05@gmail.com', '082122830574', '$2y$10$XGZ/6n6QwogBmeAtTLo9DuGnEqD/EeVEfFKaAhQfj5EjKQjBNWY8q', 'pendaki', NULL, NULL, '2025-07-08 16:04:42', '2025-07-08 09:04:42'),
(15, 'porter', 'merapi', 'portermerapi@gmail.com', '111122223333', '$2y$10$we6sIGxJk.8Nj/JapyPiIe40tk5nL.dIeGfTRuUK9MryyBoRIpaBG', 'porter', NULL, 2, '2025-07-08 16:06:33', '2025-07-08 09:06:33'),
(16, 'ojek', 'bromo', 'ojekbromo@gmail.com', '111122223333', '$2y$10$6FjKJmLFF9RxyhIMlbbhpO9sdSltnfdwq7QNcwbZ.x8PyGc/dbjBS', 'ojek', NULL, 1, '2025-07-08 16:08:31', '2025-07-08 09:08:31'),
(17, 'ojek', 'merapi', 'ojekmerapi@gmail.com', '111122223333', '$2y$10$xpqvB16AuVvTAuZGjtY8ZupsYplecvy62gLMBXtJD5nfUPljOXDdu', 'ojek', NULL, 2, '2025-07-08 16:09:27', '2025-07-08 09:09:27'),
(18, 'ojek', 'semeru', 'ojeksemeru@gmail.com', '111122223333', '$2y$10$RhHNcvDcLdC5kOBujZNULurQd059fSimtq2MzBCFvSccGOfDdQr7y', 'ojek', NULL, 3, '2025-07-08 16:10:08', '2025-07-08 09:10:08'),
(19, 'basecamp', 'semeru', 'basecampsemeru@gmail.com', '111122223333', '$2y$10$4lKQfAqErwEg4m/CF8fZoO1SPZgOo09yhuKC9enO1O9Ex8RHDACvG', 'basecamp', NULL, 3, '2025-07-08 16:33:14', '2025-07-08 09:33:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basecamp`
--
ALTER TABLE `basecamp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `gunung_id` (`gunung_id`);

--
-- Indexes for table `detail_pendaki`
--
ALTER TABLE `detail_pendaki`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pemesanan_tiket_id` (`pemesanan_tiket_id`);

--
-- Indexes for table `guide`
--
ALTER TABLE `guide`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `gunung_id` (`gunung_id`);

--
-- Indexes for table `gunung`
--
ALTER TABLE `gunung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `jalur_pendakian`
--
ALTER TABLE `jalur_pendakian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gunung_id` (`gunung_id`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_layanan` (`nama_layanan`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penerima_id` (`penerima_id`),
  ADD KEY `pengirim_id` (`pengirim_id`),
  ADD KEY `idx_gunung_id` (`gunung_id`);

--
-- Indexes for table `ojek`
--
ALTER TABLE `ojek`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `gunung_id` (`gunung_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `tiket_id` (`tiket_id`);

--
-- Indexes for table `pemesanan_basecamp`
--
ALTER TABLE `pemesanan_basecamp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `basecamp_id` (`basecamp_id`),
  ADD KEY `pemesanan_tiket_id` (`pemesanan_tiket_id`);

--
-- Indexes for table `pemesanan_guide`
--
ALTER TABLE `pemesanan_guide`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `pemesanan_tiket_id` (`pemesanan_tiket_id`);

--
-- Indexes for table `pemesanan_layanan`
--
ALTER TABLE `pemesanan_layanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pemesanan_id` (`pemesanan_id`,`layanan_id`),
  ADD KEY `layanan_id` (`layanan_id`);

--
-- Indexes for table `pemesanan_ojek`
--
ALTER TABLE `pemesanan_ojek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ojek_id` (`ojek_id`),
  ADD KEY `pemesanan_tiket_id` (`pemesanan_tiket_id`);

--
-- Indexes for table `pemesanan_porter`
--
ALTER TABLE `pemesanan_porter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `porter_id` (`porter_id`),
  ADD KEY `pemesanan_tiket_id` (`pemesanan_tiket_id`);

--
-- Indexes for table `pemesanan_tiket`
--
ALTER TABLE `pemesanan_tiket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tiket_id` (`tiket_id`);

--
-- Indexes for table `porter`
--
ALTER TABLE `porter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `gunung_id` (`gunung_id`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gunung_id` (`gunung_id`),
  ADD KEY `jalur_id` (`jalur_id`);

--
-- Indexes for table `tiket_gunung`
--
ALTER TABLE `tiket_gunung`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_gunung` (`gunung_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `basecamp`
--
ALTER TABLE `basecamp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pendaki`
--
ALTER TABLE `detail_pendaki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guide`
--
ALTER TABLE `guide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gunung`
--
ALTER TABLE `gunung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jalur_pendakian`
--
ALTER TABLE `jalur_pendakian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ojek`
--
ALTER TABLE `ojek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pemesanan_basecamp`
--
ALTER TABLE `pemesanan_basecamp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_guide`
--
ALTER TABLE `pemesanan_guide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_layanan`
--
ALTER TABLE `pemesanan_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pemesanan_ojek`
--
ALTER TABLE `pemesanan_ojek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_porter`
--
ALTER TABLE `pemesanan_porter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_tiket`
--
ALTER TABLE `pemesanan_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `porter`
--
ALTER TABLE `porter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiket_gunung`
--
ALTER TABLE `tiket_gunung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `basecamp`
--
ALTER TABLE `basecamp`
  ADD CONSTRAINT `basecamp_ibfk_1` FOREIGN KEY (`gunung_id`) REFERENCES `gunung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `basecamp_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `detail_pendaki`
--
ALTER TABLE `detail_pendaki`
  ADD CONSTRAINT `detail_pendaki_ibfk_1` FOREIGN KEY (`pemesanan_tiket_id`) REFERENCES `pemesanan_tiket` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guide`
--
ALTER TABLE `guide`
  ADD CONSTRAINT `guide_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `guide_ibfk_2` FOREIGN KEY (`gunung_id`) REFERENCES `gunung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gunung`
--
ALTER TABLE `gunung`
  ADD CONSTRAINT `gunung_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jalur_pendakian`
--
ALTER TABLE `jalur_pendakian`
  ADD CONSTRAINT `jalur_pendakian_ibfk_1` FOREIGN KEY (`gunung_id`) REFERENCES `gunung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifikasi_ibfk_2` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ojek`
--
ALTER TABLE `ojek`
  ADD CONSTRAINT `ojek_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ojek_ibfk_2` FOREIGN KEY (`gunung_id`) REFERENCES `gunung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`tiket_id`) REFERENCES `tiket_gunung` (`id`);

--
-- Constraints for table `pemesanan_basecamp`
--
ALTER TABLE `pemesanan_basecamp`
  ADD CONSTRAINT `pemesanan_basecamp_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_basecamp_ibfk_2` FOREIGN KEY (`basecamp_id`) REFERENCES `basecamp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_basecamp_ibfk_3` FOREIGN KEY (`pemesanan_tiket_id`) REFERENCES `pemesanan_tiket` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan_guide`
--
ALTER TABLE `pemesanan_guide`
  ADD CONSTRAINT `pemesanan_guide_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_guide_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guide` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_guide_ibfk_3` FOREIGN KEY (`pemesanan_tiket_id`) REFERENCES `pemesanan_tiket` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan_layanan`
--
ALTER TABLE `pemesanan_layanan`
  ADD CONSTRAINT `pemesanan_layanan_ibfk_1` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_layanan_ibfk_2` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`);

--
-- Constraints for table `pemesanan_ojek`
--
ALTER TABLE `pemesanan_ojek`
  ADD CONSTRAINT `pemesanan_ojek_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_ojek_ibfk_2` FOREIGN KEY (`ojek_id`) REFERENCES `ojek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_ojek_ibfk_3` FOREIGN KEY (`pemesanan_tiket_id`) REFERENCES `pemesanan_tiket` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan_porter`
--
ALTER TABLE `pemesanan_porter`
  ADD CONSTRAINT `pemesanan_porter_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_porter_ibfk_2` FOREIGN KEY (`porter_id`) REFERENCES `porter` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_porter_ibfk_3` FOREIGN KEY (`pemesanan_tiket_id`) REFERENCES `pemesanan_tiket` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan_tiket`
--
ALTER TABLE `pemesanan_tiket`
  ADD CONSTRAINT `pemesanan_tiket_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_tiket_ibfk_2` FOREIGN KEY (`tiket_id`) REFERENCES `tiket` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `porter`
--
ALTER TABLE `porter`
  ADD CONSTRAINT `porter_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `porter_ibfk_2` FOREIGN KEY (`gunung_id`) REFERENCES `gunung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `tiket_ibfk_1` FOREIGN KEY (`gunung_id`) REFERENCES `gunung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tiket_ibfk_2` FOREIGN KEY (`jalur_id`) REFERENCES `jalur_pendakian` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_gunung` FOREIGN KEY (`gunung_id`) REFERENCES `gunung` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
