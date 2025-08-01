-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2025 at 01:30 PM
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
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_comments`
--

INSERT INTO `forum_comments` (`id`, `user_id`, `post_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 14, 4, 'tes', '2025-07-15 08:44:15', '2025-07-15 08:44:15');

-- --------------------------------------------------------

--
-- Table structure for table `forum_likes`
--

CREATE TABLE `forum_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_likes`
--

INSERT INTO `forum_likes` (`id`, `user_id`, `post_id`, `created_at`) VALUES
(2, 14, 4, '2025-07-15 08:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` enum('pengalaman','tips','peralatan','cuaca','tanya-jawab') NOT NULL DEFAULT 'pengalaman',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `user_id`, `title`, `content`, `category`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pengalaman Mendaki Gunung Bromo di Musim Hujan', 'Kemarin baru saja selesai mendaki Gunung Bromo bersama teman-teman. Meskipun cuaca tidak terlalu mendukung karena musim hujan, pemandangan sunrise tetap spektakuler! Beberapa tips yang bisa saya bagikan untuk pendakian di musim hujan...', 'pengalaman', '2025-07-11 07:20:39', '2025-07-11 09:20:39'),
(2, 8, '5 Tips Penting untuk Pendaki Pemula', 'Buat teman-teman yang baru mau mulai hobi mendaki, ini beberapa tips penting yang harus diketahui: 1. Pilih gunung dengan tingkat kesulitan pemula 2. Persiapkan fisik minimal 2 minggu sebelum pendakian 3. Bawa peralatan yang sesuai dan jangan berlebihan 4. Selalu informasikan rencana pendakian ke keluarga 5. Ikuti aturan dan jaga kebersihan alam', 'tips', '2025-07-11 04:20:39', '2025-07-11 09:20:39'),
(3, 9, 'Tanya: Perlengkapan Wajib untuk Mendaki Gunung Semeru?', 'Halo semuanya! Saya berencana mendaki Gunung Semeru bulan depan untuk pertama kalinya. Bisa minta saran perlengkapan apa saja yang wajib dibawa? Terutama untuk menghadapi cuaca dingin di puncak. Terima kasih!', 'tanya-jawab', '2025-07-10 09:20:39', '2025-07-11 09:20:39'),
(4, 22, 'pengalaman post', 'pengalaman post', 'pengalaman', '2025-07-11 09:31:23', '2025-07-11 09:31:23');

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
(1, 'Gunung Bromo', NULL, 'Jawa Timur', 2329, '', 'buka', 500, 8, '2025-07-04 07:34:13', '2025-07-12 06:02:25'),
(2, 'Gunung Merapi', NULL, 'Jawa Tengah', 2930, NULL, 'buka', 250, 9, '2025-07-04 07:34:13', '2025-07-04 07:49:05'),
(3, 'Gunung Semeru', NULL, 'Jawa Timur', 3676, NULL, 'buka', 600, 10, '2025-07-04 07:34:13', '2025-07-04 07:49:46'),
(4, 'Gunung Gede', NULL, 'Jawa Barat', 2958, NULL, 'buka', 800, NULL, '2025-07-04 07:34:13', '2025-07-04 07:34:13');

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
(3, 'ojek', 'Transportasi ke pos awal', 60000.00, '/orang'),
(4, 'basecamp', 'Tempat istirahat sebelum pendakian', 80000.00, '/malam');

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
(4, 16, 1, 8, 'Tugas Baru untuk Anda (Ojek)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-F0C9C4\r\n- Nama Pemesan: Annuru Wulandari\r\n- Layanan yang Dibutuhkan: ojek\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-08 09:35:53'),
(5, 17, 2, 9, 'Tugas Baru untuk Anda (Ojek)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-960141\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: ojek\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-08 09:48:23'),
(6, 20, 2, 9, 'Tugas Baru untuk Anda (Ojek)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-956929\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: ojek\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-08 09:48:32'),
(7, 19, 3, 10, 'Tugas Baru untuk Anda (Basecamp)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-742107\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: basecamp\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-12 06:52:30'),
(8, 23, 3, 10, 'Tugas Baru untuk Anda (Porter)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-5CD603\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: porter\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-12 16:49:15'),
(9, 25, 3, 10, 'Tugas Baru untuk Anda (Guide)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-733AB0\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: guide\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-12 17:34:11'),
(10, 23, 3, 10, 'Tugas Baru untuk Anda (Porter)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-2D56B3\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: porter\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-15 08:48:32'),
(11, 23, 3, 10, 'Tugas Baru untuk Anda (Porter)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-0EBAE2\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: porter\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-15 08:54:29'),
(12, 18, 3, 10, 'Tugas Baru untuk Anda (Ojek)', 'Pemberitahuan Tugas Baru:\r\n- Kode Booking: KNCD-D6A863\r\n- Nama Pemesan: Daviar Andrianoe\r\n- Layanan yang Dibutuhkan: ojek\r\n\r\nMohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.', '', NULL, 'belum', '2025-07-15 08:54:37');

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
(8, 11, 1, 'KNCD-47E2F2', '2025-08-08', '2025-08-09', 1, 35000.00, 200000.00, 235000.00, 'complete', '2025-07-04 11:33:56'),
(9, 11, 1, 'KNCD-6B710F', '2025-07-30', '2025-07-31', 1, 35000.00, 450000.00, 485000.00, 'complete', '2025-07-04 11:48:22'),
(10, 12, 1, 'KNCD-F554C1', '2025-07-31', '2025-08-01', 1, 35000.00, 300000.00, 335000.00, 'in progress', '2025-07-08 08:50:23'),
(11, 12, 2, 'KNCD-A668B5', '2025-08-09', '2025-08-10', 1, 25000.00, 200000.00, 225000.00, 'in progress', '2025-07-08 08:50:50'),
(12, 12, 3, 'KNCD-72D25A', '2025-08-01', '2025-08-02', 1, 45000.00, 150000.00, 195000.00, 'in progress', '2025-07-08 08:51:03'),
(13, 12, 4, 'KNCD-46FF4C', '2025-07-09', '2025-07-10', 1, 30000.00, 50000.00, 80000.00, 'pending', '2025-07-08 08:51:16'),
(14, 14, 3, 'KNCD-88B641', '2025-07-17', '2025-07-18', 1, 45000.00, 150000.00, 195000.00, 'complete', '2025-07-08 09:05:12'),
(15, 12, 1, 'KNCD-F0C9C4', '2025-07-17', '2025-07-18', 1, 35000.00, 50000.00, 85000.00, 'in progress', '2025-07-08 09:35:27'),
(16, 14, 2, 'KNCD-960141', '2025-07-16', '2025-07-17', 1, 25000.00, 50000.00, 75000.00, 'complete', '2025-07-08 09:47:05'),
(17, 14, 2, 'KNCD-956929', '2025-07-26', '2025-07-27', 2, 50000.00, 100000.00, 150000.00, 'complete', '2025-07-08 09:47:37'),
(22, 14, 4, 'KNCD-406204', '2025-07-19', '2025-07-20', 1, 30000.00, 60000.00, 90000.00, 'pending', '2025-07-12 06:47:16'),
(23, 14, 3, 'KNCD-742107', '2025-07-16', '2025-07-17', 1, 45000.00, 80000.00, 125000.00, 'complete', '2025-07-12 06:52:07'),
(24, 14, 3, 'KNCD-5CD603', '2025-07-17', '2025-07-18', 1, 45000.00, 100000.00, 145000.00, 'complete', '2025-07-12 16:47:49'),
(25, 14, 3, 'KNCD-733AB0', '2025-07-25', '2025-07-26', 1, 45000.00, 150000.00, 195000.00, 'complete', '2025-07-12 17:23:35'),
(26, 14, 3, 'KNCD-2D56B3', '2025-07-23', '2025-07-24', 1, 45000.00, 100000.00, 145000.00, 'complete', '2025-07-15 08:42:26'),
(27, 14, 3, 'KNCD-0EBAE2', '2025-07-24', '2025-07-25', 1, 45000.00, 100000.00, 145000.00, 'complete', '2025-07-15 08:52:48'),
(28, 14, 3, 'KNCD-D6A863', '2025-07-25', '2025-07-26', 1, 45000.00, 60000.00, 105000.00, 'complete', '2025-07-15 08:54:05');

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
(22, 15, 3, 1, 50000.00),
(23, 16, 3, 1, 50000.00),
(24, 17, 3, 2, 100000.00),
(29, 22, 3, 1, 60000.00),
(30, 23, 4, 1, 80000.00),
(31, 24, 2, 1, 100000.00),
(32, 25, 1, 1, 150000.00),
(33, 26, 2, 1, 100000.00),
(34, 27, 2, 1, 100000.00),
(35, 28, 3, 1, 60000.00);

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `role`, `gunung_bertugas_id`, `gunung_id`, `created_at`, `updated_at`, `profile_picture`) VALUES
(1, 'admin', '1', 'admin@gmail.com', '0987387421233', '$2y$10$HMoT2tenyq66/VxGt.fraeQa9J696r5tkFgo8ZZORJoz.lXmIpXSS', 'admin', NULL, NULL, '2025-07-04 09:00:09', '2025-07-04 07:00:45', NULL),
(8, 'pengelola', 'bromo', 'bromo@gmail.com', '0891292809123', '$2y$10$4Ju4TqDqKNigAkbheyY5/.jFEJZSMwpmkTIN1IQOqq7DAckGR9A6m', 'pengelola_gunung', NULL, 1, '2025-07-04 14:47:43', '2025-07-05 12:34:15', NULL),
(9, 'pengelola', 'merapi', 'merapi@gmail.com', '0891292809123', '$2y$10$PRVJaV4qAaeGMMM1FB93Qup..xT8wsmIhH3xP6ld2QWz/39tI9tFq', 'pengelola_gunung', NULL, 2, '2025-07-04 14:49:05', '2025-07-05 12:34:47', NULL),
(10, 'pengelola', 'semeru', 'semeru@gmail.com', '089628670822', '$2y$10$lxkg5LKOrJ8cbeU8GeJA5uNBGrcEiG.irVlbqARQwJdXpL7cH.MVW', 'pengelola_gunung', NULL, 3, '2025-07-04 14:49:46', '2025-07-05 12:35:00', NULL),
(11, 'Agil', 'liam', 'liam@gmail.com', '09249814871', '$2y$10$I2rvRv/KWU2PNI0MLMgHJuftQuvvKkaJy9oZPElyL6I70532sg856', 'pendaki', NULL, NULL, '2025-07-04 14:52:04', '2025-07-04 07:52:04', NULL),
(14, 'Daviar', 'Andrianoe', 'daviar.andrianoe05@gmail.com', '082122830574', '$2y$10$XGZ/6n6QwogBmeAtTLo9DuGnEqD/EeVEfFKaAhQfj5EjKQjBNWY8q', 'pendaki', NULL, NULL, '2025-07-08 16:04:42', '2025-07-12 16:54:35', 'profile_14_1752339275.jpeg'),
(15, 'porter', 'merapi', 'portermerapi@gmail.com', '111122223333', '$2y$10$we6sIGxJk.8Nj/JapyPiIe40tk5nL.dIeGfTRuUK9MryyBoRIpaBG', 'porter', NULL, 2, '2025-07-08 16:06:33', '2025-07-08 09:06:33', NULL),
(16, 'ojek', 'bromo', 'ojekbromo@gmail.com', '111122223333', '$2y$10$6FjKJmLFF9RxyhIMlbbhpO9sdSltnfdwq7QNcwbZ.x8PyGc/dbjBS', 'ojek', NULL, 1, '2025-07-08 16:08:31', '2025-07-08 09:08:31', NULL),
(17, 'ojek', 'merapi', 'ojekmerapi@gmail.com', '111122223333', '$2y$10$xpqvB16AuVvTAuZGjtY8ZupsYplecvy62gLMBXtJD5nfUPljOXDdu', 'ojek', NULL, 2, '2025-07-08 16:09:27', '2025-07-08 09:09:27', NULL),
(18, 'ojek', 'semeru', 'ojeksemeru@gmail.com', '111122223333', '$2y$10$RhHNcvDcLdC5kOBujZNULurQd059fSimtq2MzBCFvSccGOfDdQr7y', 'ojek', NULL, 3, '2025-07-08 16:10:08', '2025-07-08 09:10:08', NULL),
(19, 'basecamp', 'semeru', 'basecampsemeru@gmail.com', '111122223333', '$2y$10$4lKQfAqErwEg4m/CF8fZoO1SPZgOo09yhuKC9enO1O9Ex8RHDACvG', 'basecamp', NULL, 3, '2025-07-08 16:33:14', '2025-07-08 09:33:14', NULL),
(20, 'ojek', 'merapi2', 'ojekmerapi2@gmail.com', '111122223333', '$2y$10$rCB6oWfau8XqB0EmoHj1o.Sci5nLSO0QHk/bJkxMXy.Gp1HF0Lvfm', 'ojek', NULL, 2, '2025-07-08 16:45:28', '2025-07-08 09:45:28', NULL),
(22, 'svt', 'nice', 'svt@gmail.com', '098752436832', '$2y$10$Zl0au0RY3INXYKosr.UTQ.lVIVhMWmOLDDDtOMcVPqO9p2kPW5Sz.', 'pendaki', NULL, NULL, '2025-07-11 16:17:01', '2025-07-11 09:18:15', 'profile_22_1752225495.jpeg'),
(23, 'porter', 'semeru', 'portersemeru@gmail.com', '111122223333', '$2y$10$qTdX8FbfVXW2AR5ThdDZv.HeOxVh0UEzSCeftiKgDPTI8/cWl55ly', 'porter', NULL, 3, '2025-07-12 23:49:01', '2025-07-12 16:49:01', NULL),
(24, 'porter', 'bromo', 'porterbromo@gmail.com', '111122223333', '$2y$10$0yZeCz31prks6Wh33dbigeiG.8RrBEZGvqU3Bpk88Mhk5qCreaSTG', 'porter', NULL, 1, '2025-07-13 00:14:55', '2025-07-12 17:14:55', NULL),
(25, 'guide', 'semeru', 'guidesemeru@gmail.com', '111122223333', '$2y$10$h.tMBXLoKITzkCF1dzL8le4eUWuUTCLqtwIOS3.v3EvH5W2s14pqK', 'guide', NULL, 3, '2025-07-13 00:34:00', '2025-07-12 17:34:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_post_unique` (`user_id`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category` (`category`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `gunung`
--
ALTER TABLE `gunung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_layanan` (`nama_layanan`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penerima_id` (`penerima_id`),
  ADD KEY `pengirim_id` (`pengirim_id`),
  ADD KEY `idx_gunung_id` (`gunung_id`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `tiket_id` (`tiket_id`);

--
-- Indexes for table `pemesanan_layanan`
--
ALTER TABLE `pemesanan_layanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pemesanan_id` (`pemesanan_id`,`layanan_id`),
  ADD KEY `layanan_id` (`layanan_id`);

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
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forum_likes`
--
ALTER TABLE `forum_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gunung`
--
ALTER TABLE `gunung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pemesanan_layanan`
--
ALTER TABLE `pemesanan_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tiket_gunung`
--
ALTER TABLE `tiket_gunung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `forum_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD CONSTRAINT `forum_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gunung`
--
ALTER TABLE `gunung`
  ADD CONSTRAINT `gunung_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifikasi_ibfk_2` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`tiket_id`) REFERENCES `tiket_gunung` (`id`);

--
-- Constraints for table `pemesanan_layanan`
--
ALTER TABLE `pemesanan_layanan`
  ADD CONSTRAINT `pemesanan_layanan_ibfk_1` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_layanan_ibfk_2` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_gunung` FOREIGN KEY (`gunung_id`) REFERENCES `gunung` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
