<?php
session_start();
require_once 'auth/check_auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - KoncoNdaki</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/syarat-ketentuan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <h1><i class="fas fa-file-contract"></i> Syarat & Ketentuan</h1>
                <p>Ketentuan penggunaan layanan KoncoNdaki yang harus dipatuhi oleh semua pengguna</p>
            </div>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="terms-content">
        <div class="container">
            <div class="terms-wrapper">
                <div class="terms-main">
                    <div class="terms-section" id="ketentuan-umum">
                        <h2><i class="fas fa-info-circle"></i> Ketentuan Umum</h2>
                        <div class="terms-content-block">
                            <p>Dengan menggunakan layanan KoncoNdaki, Anda menyetujui untuk terikat dengan syarat dan ketentuan berikut:</p>
                            <ul>
                                <li>Pengguna harus berusia minimal 17 tahun atau memiliki izin dari orang tua/wali</li>
                                <li>Informasi yang diberikan harus akurat dan terkini</li>
                                <li>Pengguna bertanggung jawab atas keamanan akun dan kata sandi</li>
                                <li>Dilarang menggunakan layanan untuk tujuan ilegal atau merugikan pihak lain</li>
                                <li>KoncoNdaki berhak menangguhkan atau menghentikan akun yang melanggar ketentuan</li>
                            </ul>
                        </div>
                    </div>

                    <div class="terms-section" id="pemesanan">
                        <h2><i class="fas fa-ticket-alt"></i> Pemesanan Tiket</h2>
                        <div class="terms-content-block">
                            <h3>Proses Pemesanan</h3>
                            <ul>
                                <li>Pemesanan dapat dilakukan melalui website KoncoNdaki</li>
                                <li>Pilih gunung, jalur, tanggal, dan jumlah pendaki sesuai kebutuhan</li>
                                <li>Pastikan data yang dimasukkan sudah benar sebelum melanjutkan pembayaran</li>
                                <li>Pemesanan dianggap sah setelah pembayaran dikonfirmasi</li>
                            </ul>
                            
                            <h3>Persyaratan Pendakian</h3>
                            <ul>
                                <li>Pendaki harus dalam kondisi sehat jasmani dan rohani</li>
                                <li>Wajib membawa identitas diri yang masih berlaku (KTP/SIM/Paspor)</li>
                                <li>Mengikuti protokol kesehatan yang berlaku</li>
                                <li>Mematuhi peraturan taman nasional dan otoritas setempat</li>
                            </ul>
                        </div>
                    </div>

                    <div class="terms-section" id="pembayaran">
                        <h2><i class="fas fa-credit-card"></i> Pembayaran</h2>
                        <div class="terms-content-block">
                            <h3>Metode Pembayaran</h3>
                            <ul>
                                <li>Transfer bank ke rekening resmi KoncoNdaki</li>
                                <li>Pembayaran digital melalui e-wallet yang tersedia</li>
                                <li>Kartu kredit/debit (jika tersedia)</li>
                            </ul>
                            
                            <h3>Ketentuan Pembayaran</h3>
                            <ul>
                                <li>Pembayaran harus dilakukan dalam waktu 24 jam setelah pemesanan</li>
                                <li>Pemesanan akan otomatis dibatalkan jika tidak ada pembayaran</li>
                                <li>Konfirmasi pembayaran akan dikirim melalui email dan WhatsApp</li>
                                <li>Harga sudah termasuk pajak yang berlaku</li>
                            </ul>
                        </div>
                    </div>

                    <div class="terms-section" id="pembatalan">
                        <h2><i class="fas fa-times-circle"></i> Pembatalan & Refund</h2>
                        <div class="terms-content-block">
                            <h3>Pembatalan oleh Pengguna</h3>
                            <ul>
                                <li>Pembatalan 7 hari sebelum tanggal pendakian: refund 100%</li>
                                <li>Pembatalan 3-6 hari sebelum tanggal pendakian: refund 50%</li>
                                <li>Pembatalan kurang dari 3 hari: tidak ada refund</li>
                                <li>Biaya admin sebesar Rp 25.000 akan dipotong dari refund</li>
                            </ul>
                            
                            <h3>Pembatalan oleh KoncoNdaki</h3>
                            <ul>
                                <li>Cuaca buruk atau kondisi darurat: refund 100%</li>
                                <li>Penutupan jalur oleh otoritas: refund 100% atau reschedule</li>
                                <li>Force majeure: refund sesuai kebijakan yang berlaku</li>
                            </ul>
                        </div>
                    </div>

                    <div class="terms-section" id="tanggung-jawab">
                        <h2><i class="fas fa-shield-alt"></i> Tanggung Jawab</h2>
                        <div class="terms-content-block">
                            <h3>Tanggung Jawab Pengguna</h3>
                            <ul>
                                <li>Memastikan kondisi fisik dan mental siap untuk pendakian</li>
                                <li>Membawa perlengkapan pendakian yang memadai</li>
                                <li>Mengikuti instruksi guide dan peraturan yang berlaku</li>
                                <li>Menjaga kelestarian alam dan lingkungan</li>
                                <li>Bertanggung jawab atas barang bawaan pribadi</li>
                            </ul>
                            
                            <h3>Tanggung Jawab KoncoNdaki</h3>
                            <ul>
                                <li>Menyediakan guide berpengalaman dan berlisensi</li>
                                <li>Memberikan informasi akurat tentang kondisi jalur</li>
                                <li>Menyediakan layanan sesuai dengan yang dipesan</li>
                                <li>Membantu dalam situasi darurat sesuai kemampuan</li>
                            </ul>
                        </div>
                    </div>

                    <div class="terms-footer">
                        <p><strong>Terakhir diperbarui:</strong> <?php echo date('d F Y'); ?></p>
                        <p>Dengan menggunakan layanan KoncoNdaki, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan di atas.</p>
                        
                        <div class="contact-info">
                            <h3>Kontak</h3>
                            <p>Jika ada pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi:</p>
                            <ul>
                                <li><i class="fas fa-envelope"></i> Email: info@konco-ndaki.com</li>
                                <li><i class="fas fa-phone"></i> Telepon: +62 812-3456-7890</li>
                                <li><i class="fas fa-map-marker-alt"></i> Alamat: Jl. Pendakian No. 123, Yogyakarta</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="scripts/tentang.js"></script>
</body>
</html>
