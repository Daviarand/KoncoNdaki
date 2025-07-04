// File: scripts/dashboard-nav.js (Versi Sederhana Tanpa API)

document.addEventListener('DOMContentLoaded', function() {
    // --- Bagian 1: Navigasi Tab ---
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.section');

    const activateTab = (targetId) => {
        sections.forEach(sec => sec.classList.remove('active'));
        navLinks.forEach(nav => nav.classList.remove('active'));

        const targetSection = document.getElementById(targetId);
        const targetLink = document.querySelector(`.nav-link[data-target='${targetId}']`);

        if (targetSection && targetLink) {
            targetSection.classList.add('active');
            targetLink.classList.add('active');
        }
    };
    
    const hash = window.location.hash.substring(1);
    if (hash) {
        activateTab(hash);
    }

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            activateTab(targetId);
            window.history.pushState(null, null, `#${targetId}`);
        });
    });

    // --- Bagian 2: Logika Dropdown Notifikasi Partner ---
    const tipePartnerSelect = document.getElementById('tipePartner');
    const pilihPartnerSelect = document.getElementById('pilihPartner');

    if (tipePartnerSelect && pilihPartnerSelect) {
        tipePartnerSelect.addEventListener('change', function() {
            const tipe = this.value;
            
            // Kosongkan dropdown partner
            pilihPartnerSelect.innerHTML = ''; 

            if (!tipe) {
                pilihPartnerSelect.innerHTML = '<option value="">-- Pilih Tipe Dulu --</option>';
                pilihPartnerSelect.disabled = true;
                return;
            }

            // Ambil data dari variabel global 'allPartnersData' yang sudah di-inject oleh PHP
            const partners = allPartnersData[tipe] || [];

            if (partners.length > 0) {
                pilihPartnerSelect.innerHTML = '<option value="">-- Pilih Partner --</option>';
                partners.forEach(partner => {
                    const option = document.createElement('option');
                    option.value = partner.id;
                    option.textContent = partner.nama;
                    pilihPartnerSelect.appendChild(option);
                });
                pilihPartnerSelect.disabled = false;
            } else {
                pilihPartnerSelect.innerHTML = '<option value="">-- Tidak ada partner ditemukan --</option>';
                pilihPartnerSelect.disabled = true;
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    
    // ... (kode navigasi tab dan dropdown partner Anda yang sudah ada) ...

    // --- Bagian 3: Logika Tombol "Kirim Notif" ---
    const notifButtons = document.querySelectorAll('.btn-kirim-notif');
    const pesanNotifTextarea = document.getElementById('pesanNotifikasi');

    // Fungsi untuk mengaktifkan tab (pastikan ini ada dari kode sebelumnya)
    const activateTab = (targetId) => {
        document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
        document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
        const targetSection = document.getElementById(targetId);
        const targetLink = document.querySelector(`.nav-link[data-target='${targetId}']`);
        if (targetSection && targetLink) {
            targetSection.classList.add('active');
            targetLink.classList.add('active');
        }
    };

    notifButtons.forEach(button => {
        button.addEventListener('click', function() {
            // 1. Ambil data dari atribut data-* tombol yang diklik
            const bookingData = this.dataset;

            // 2. Buat template pesan otomatis
            const pesanOtomatis = `Pemberitahuan Tugas Baru:
- Kode Booking: ${bookingData.kodeBooking}
- Nama Pemesan: ${bookingData.namaPendaki}
- Layanan yang Dibutuhkan: ${bookingData.layanan}

Mohon segera persiapkan layanan sesuai detail pesanan. Terima kasih.`;
            
            // 3. Isi textarea di form notifikasi dengan pesan otomatis
            if (pesanNotifTextarea) {
                pesanNotifTextarea.value = pesanOtomatis;
            }

            // 4. Pindahkan tampilan ke tab "Kirim Notifikasi"
            activateTab('kirimNotifikasi');

            // 5. (Opsional) Scroll ke atas agar form terlihat jelas
            window.scrollTo(0, 0);
        });
    });
});