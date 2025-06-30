document.addEventListener('DOMContentLoaded', function() {
    // Chart.js data and options
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const pathCtx = document.getElementById('pathChart').getContext('2d');

    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Jumlah Pendaki',
                data: [850, 920, 1100, 1050, 1300, 1247],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: false,
                    text: 'Tren Jumlah Pendaki (6 Bulan Terakhir)'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pendaki'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        }
    });

    const pathChart = new Chart(pathCtx, {
        type: 'doughnut',
        data: {
            labels: ['Jalur Selo', 'Jalur Cemoro Kandang', 'Jalur Kaliurang', 'Jalur Lainnya'],
            datasets: [{
                label: 'Distribusi Pendaki',
                data: [350, 280, 200, 417],
                backgroundColor: [
                    '#4facfe',
                    '#00f2fe',
                    '#a8edea',
                    '#fed6e3'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: false,
                    text: 'Distribusi Jalur Pendakian'
                },
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Global variable to keep track of active section (needed for programmatically switching tabs)
    let currentActiveSection = 'dashboard'; // Initialize with the default active section

    // Navigation and Section Switching Logic
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove 'active' from all links and sections
            navLinks.forEach(nav => nav.classList.remove('active'));
            sections.forEach(sec => sec.classList.remove('active'));

            // Add 'active' to the clicked link
            this.classList.add('active');

            // Show the target section
            const targetId = this.dataset.target;
            document.getElementById(targetId).classList.add('active');
            currentActiveSection = targetId; // Update current active section
        });
    });
    window.navLinks = navLinks; // Membuat navLinks dapat diakses oleh fungsi kirimPesan

    // --- NEW: Event Listener for "Tambah Partner" button ---
    const addPartnerFieldBtn = document.getElementById('addPartnerFieldBtn');
    if (addPartnerFieldBtn) {
        addPartnerFieldBtn.addEventListener('click', addPartnerSelectionField);
    }
    // Initial update of counter
    updatePartnerFieldCounter();

});

// --- NEW GLOBAL VARIABLES / HELPERS ---
let partnerFieldIndex = 0; // To keep track of unique IDs for dynamic fields
const MAX_ADDITIONAL_PARTNERS = 4; // Max partners to add (total 5 including initial)

// Functions for Kelola Kuota Section
function tambahKuota(event) {
    event.preventDefault();
    const inputJalur = document.getElementById('inputJalur');
    const inputKuota = document.getElementById('inputKuota');
    const tbody = document.querySelector('#kelolaKuota .data-table tbody');

    const newRow = `
        <tr>
            <td>${inputJalur.value}</td>
            <td><input type="number" value="${inputKuota.value}"></td>
            <td>0</td>
            <td>
                <button class="btn-primary" onclick="updateKuota(this)">Simpan</button>
                <button class="btn-danger" onclick="hapusKuota(this)">Hapus</button>
            </td>
        </tr>
    `;
    tbody.insertAdjacentHTML('beforeend', newRow);

    // Clear form fields
    inputJalur.value = '';
    inputKuota.value = '';
    alert('Kuota jalur berhasil ditambahkan!');
}

function updateKuota(button) {
    const row = button.closest('tr');
    const jalur = row.children[0].textContent;
    const inputElement = row.children[1].querySelector('input');
    const kuotaBaru = inputElement.value;
    alert(`Kuota untuk jalur ${jalur} berhasil diperbarui menjadi ${kuotaBaru}!`);
}

function hapusKuota(button) {
    const row = button.closest('tr');
    const jalur = row.children[0].textContent;
    if (confirm(`Anda yakin ingin menghapus jalur ${jalur}?`)) {
        row.remove();
        alert(`Jalur ${jalur} berhasil dihapus.`);
    }
}

// Functions for Data Pendaki Section
function hapusPendaki(button) {
    const row = button.closest('tr');
    const nama = row.children[0].textContent;
    if (confirm(`Anda yakin ingin menghapus data pendaki ${nama}?`)) {
        row.remove();
        alert(`Data pendaki ${nama} berhasil dihapus.`);
    }
}

// Functions for Laporan Keuangan Section (Requires jsPDF library)
function downloadKeuanganPDF() {
    alert('Fungsi download PDF memerlukan library jsPDF. Unduhan dimulai...');
}

// Functions for Partner Network Section
let selectedPartnerType = ''; // To store the currently selected partner type (for the cards)

function selectPartner(type, cardElement) {
    // Remove 'selected' class from all partner cards
    document.querySelectorAll('.partner-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add 'selected' class to the clicked card if element is provided
    if (cardElement) {
        cardElement.classList.add('selected');
    }

    // This function will now only pre-select the *first* dropdown if cards are clicked.
    // For multiple partners, admin uses "Tambah Partner" button.
    const firstPartnerTypeDropdown = document.getElementById('partnerType_0');
    if (firstPartnerTypeDropdown) {
        firstPartnerTypeDropdown.value = type;
        populateSpecificPartners(firstPartnerTypeDropdown); // Populate its specific partner dropdown
    }
    selectedPartnerType = type; // Keep track for old logic if needed elsewhere
}


// FUNGSI UNTUK MEMBUAT KARTU PESANAN SEPERTI GAMBAR
function createOrderCardHtml(orderData) {
    let iconClass = '';
    switch (orderData.type) {
        case 'ojek':
            iconClass = 'fas fa-biking'; // Ikon untuk ojek
            break;
        case 'porter':
            iconClass = 'fas fa-box'; // Ikon untuk porter
            break;
        case 'guide':
            iconClass = 'fas fa-map-marked-alt'; // Ikon untuk guide
            break;
        case 'basecamp':
            iconClass = 'fas fa-campground'; // Ikon untuk basecamp
            break;
        default:
            iconClass = 'fas fa-info-circle';
    }

    // Format nilai uang
    const formattedFare = `Rp ${orderData.fare.toLocaleString('id-ID')}`;

    return `
        <div class="order-card new-order">
            <div class="order-header">
                <span class="new-order-badge">PESANAN BARU</span>
            </div>
            <div class="order-title">
                <h2>Pesanan ${orderData.type.charAt(0).toUpperCase() + orderData.type.slice(1)} Baru</h2>
                <p><i class="far fa-clock"></i> ${orderData.timeAgo}</p>
            </div>
            <div class="order-summary">
                <i class="${iconClass} large-icon"></i>
                <p>Permintaan antar jemput dari ${orderData.pickup} ke ${orderData.destination}</p>
            </div>

            <div class="order-details-box">
                <h4><i class="fas fa-info-circle"></i> Informasi Pesanan</h4>
                <div class="detail-row">
                    <span class="detail-label">Nama:</span>
                    <span class="detail-value">${orderData.customerName}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Telepon:</span>
                    <span class="detail-value phone-number">${orderData.customerPhone}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal:</span>
                    <span class="detail-value">${orderData.date}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jam:</span>
                    <span class="detail-value">${orderData.time}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nilai:</span>
                    <span class="detail-value amount">${formattedFare}</span>
                </div>
                <div class="order-route">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${orderData.pickup} -- ${orderData.destination}</span>
                </div>
            </div>
        </div>
    `;
}

// MODIFIKASI FUNGSI kirimPesan untuk menangani multiple partners
function kirimPesan(event) {
    event.preventDefault(); // Mencegah submit form default

    const currentBookingIdInput = document.getElementById('currentBookingId');
    const bookingIdFromAssignment = currentBookingIdInput.value;

    const partnerSelections = [];
    let allPartnersSelected = true;
    let specificPartnerMissing = false;
    let messageContentMissing = false;

    // Iterate through all partner selection groups
    const partnerGroups = document.querySelectorAll('.partner-selection-group');
    partnerGroups.forEach(group => {
        const typeSelect = group.querySelector('.partner-type-select');
        const specificSelect = group.querySelector('.specific-partner-select');
        const messageTextarea = group.querySelector('.message-content-textarea');

        const partnerType = typeSelect.value;
        const specificPartner = specificSelect ? specificSelect.value : ''; // specificSelect might not exist if 'Semua Partner' is chosen
        const messageContent = messageTextarea ? messageTextarea.value : '';


        if (partnerType === '') {
            allPartnersSelected = false;
        }
        // Check if a specific partner is required but not selected
        if (partnerType !== '' && partnerType !== 'semua' && specificPartner === '') {
            specificPartnerMissing = true;
        }
        // Check if message content is missing for any selected partner
        if (partnerType !== '' && messageContent === '') {
            messageContentMissing = true;
        }


        if (partnerType !== '') { // Only add if a type is selected
            partnerSelections.push({
                type: partnerType,
                specific: specificPartner,
                message: messageContent
            });
        }
    });

    if (partnerSelections.length === 0) {
        alert('Mohon pilih setidaknya satu tipe partner.');
        return;
    }
    if (!allPartnersSelected) {
        alert('Mohon lengkapi semua pilihan tipe partner yang ditambahkan.');
        return;
    }
    if (specificPartnerMissing) {
        alert('Mohon pilih partner spesifik untuk semua tipe partner yang membutuhkan.');
        return;
    }
    if (messageContentMissing) {
        alert('Mohon isi pesan untuk setiap partner yang dipilih.');
        return;
    }

    const messageHistory = document.getElementById('messageHistory');
    const serviceOrdersList = document.getElementById('serviceOrdersList');

    const now = new Date();
    const timeString = `${now.getDate()} ${now.toLocaleString('id-ID', { month: 'short' })} ${now.getFullYear()}, ${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`;

    let allPartnersInMessage = [];
    let anyOrderCardCreated = false;

    partnerSelections.forEach(selection => {
        let icon = '';
        switch (selection.type) {
            case 'ojek': icon = '🏍️'; break;
            case 'guide': icon = '🧭'; break;
            case 'porter': icon = '🎒'; break;
            case 'basecamp': icon = '🏠'; break;
            case 'semua': icon = '👥'; break;
        }
        let recipient = selection.specific || selection.type.charAt(0).toUpperCase() + selection.type.slice(1);
        if (selection.type === 'semua') recipient = 'Semua Partner';
        allPartnersInMessage.push(`${icon} ${recipient}`);

        const messageContent = selection.message; // Get specific message content

        const generalMessageHtml = `
            <div class="message-item">
                <div class="message-header">
                    <span class="message-recipient">${icon} ${recipient}</span>
                    <span class="message-time">${timeString}</span>
                </div>
                <div class="message-content">
                    ${messageContent}
                </div>
            </div>
        `;
        messageHistory.insertAdjacentHTML('afterbegin', generalMessageHtml);


        // Logic for creating new service order cards from message content
        // This will create a card for each *specific* partner type if chosen
        if (selection.type !== 'semua' && selection.specific !== '') { // Only create card if specific partner selected
            const lines = messageContent.split('\n').map(line => line.trim()).filter(line => line.length > 0);

            let pickup = 'Tidak Diketahui';
            let destination = 'Tidak Diketahui';
            let customerName = 'Tidak Diketahui';
            let customerPhone = 'Tidak Diketahui';
            let orderDate = now.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
            let orderTime = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}) + ' WIB';
            let fare = 0;

            lines.forEach(line => {
                if (line.toLowerCase().includes('dari') && line.toLowerCase().includes('ke')) {
                    const parts = line.split(/dari/i)[1]?.split(/ke/i);
                    if (parts && parts.length === 2) {
                        pickup = parts[0].trim();
                        destination = parts[1].trim();
                    }
                } else if (line.toLowerCase().includes('nama:')) {
                    customerName = line.split(/:/)[1]?.trim() || customerName;
                } else if (line.toLowerCase().includes('telepon:')) {
                    customerPhone = line.split(/:/)[1]?.trim() || customerPhone;
                } else if (line.toLowerCase().includes('tanggal:')) {
                    orderDate = line.split(/:/)[1]?.trim() || orderDate;
                } else if (line.toLowerCase().includes('jam:')) {
                    orderTime = line.split(/:/)[1]?.trim() || orderTime;
                } else if (line.toLowerCase().includes('nilai:')) {
                    const fareStr = line.split(/:/)[1]?.trim().replace('Rp', '').replace(/\./g, '');
                    fare = parseInt(fareStr) || 0;
                }
            });

            const orderData = {
                type: selection.type,
                timeAgo: 'baru saja',
                pickup: pickup,
                destination: destination,
                customerName: customerName,
                customerPhone: customerPhone,
                date: orderDate,
                time: orderTime,
                fare: fare
            };

            const orderCardHtml = createOrderCardHtml(orderData);
            serviceOrdersList.insertAdjacentHTML('afterbegin', orderCardHtml);
            anyOrderCardCreated = true;
        }
    });


    // If from booking assignment, clear ID and inform admin.
    if (bookingIdFromAssignment) {
        // Find the booking row and update its status
        const bookingRow = document.querySelector(`tr[data-booking-id="${bookingIdFromAssignment}"]`);
        if (bookingRow) {
            updateBookingStatus(bookingRow, 'confirmed'); // Automatically confirm the booking
        }
        currentBookingIdInput.value = '';
        alert(`Pesan berhasil dikirim ke ${allPartnersInMessage.length} partner untuk Booking ID ${bookingIdFromAssignment}! Status booking otomatis diubah menjadi "Confirmed".`);
    } else {
        alert('Pesan berhasil dikirim!');
    }

    // Pindah ke tab "Pesanan Layanan" secara otomatis HANYA JIKA BUKAN DARI BOOKING ASSIGNMENT
    // dan jika ada setidaknya satu kartu pesanan baru yang dibuat (yaitu, ada specific partner dipilih)
    if (!bookingIdFromAssignment && anyOrderCardCreated) {
        if (window.navLinks) {
            window.navLinks.forEach(link => {
                if (link.dataset.target === 'pesananLayanan') {
                    link.click();
                }
            });
        }
    }


    // Bersihkan form
    document.getElementById('messageForm').reset();
    document.querySelectorAll('.partner-card').forEach(card => {
        card.classList.remove('selected');
    });
    // Clear all dynamically added partner selection fields except the first one
    resetPartnerSelectionFields();
}


// --- NEW FUNCTION: Dynamically add partner selection fields ---
function addPartnerSelectionField() {
    const partnerSelectionGroupsDiv = document.getElementById('partnerSelectionGroups');
    const currentPartnerFields = partnerSelectionGroupsDiv.querySelectorAll('.partner-selection-group').length;

    if (currentPartnerFields >= MAX_ADDITIONAL_PARTNERS + 1) { // +1 for the initial field
        alert(`Anda hanya dapat menambahkan maksimal ${MAX_ADDITIONAL_PARTNERS} partner tambahan.`);
        return;
    }

    partnerFieldIndex++; // Increment for unique IDs

    const newPartnerGroup = document.createElement('div');
    newPartnerGroup.classList.add('partner-selection-group');
    newPartnerGroup.setAttribute('data-partner-index', partnerFieldIndex);

    newPartnerGroup.innerHTML = `
        <div class="form-group">
            <label for="partnerType_${partnerFieldIndex}">Tipe Partner ${partnerFieldIndex + 1}:</label>
            <select id="partnerType_${partnerFieldIndex}" class="partner-type-select" onchange="populateSpecificPartners(this)">
                <option value="">Pilih Tipe Partner</option>
                <option value="ojek">🏍️ Ojek</option>
                <option value="guide">🧭 Guide</option>
                <option value="porter">🎒 Porter</option>
                <option value="basecamp">🏠 Basecamp</option>
                </select>
        </div>

        <div class="form-group specific-partner-selection" id="specificPartnerSelection_${partnerFieldIndex}" style="display: none;">
            <label for="specificPartner_${partnerFieldIndex}">Pilih Partner Spesifik ${partnerFieldIndex + 1}:</label>
            <select id="specificPartner_${partnerFieldIndex}" class="specific-partner-select">
                <option value="">Pilih Partner</option>
            </select>
            <button type="button" class="btn-danger remove-partner-field" style="margin-left: 10px;" onclick="removePartnerSelectionField(this)">Hapus</button>
        </div>
         <div class="form-group">
            <label for="messageContent_${partnerFieldIndex}">Isi Pesan ${partnerFieldIndex + 1}:</label>
            <textarea id="messageContent_${partnerFieldIndex}" class="message-content-textarea" placeholder="Tulis pesan untuk partner ini... (Jika ini adalah pesanan baru, tulis detailnya di sini)" required></textarea>
        </div>
    `;

    partnerSelectionGroupsDiv.appendChild(newPartnerGroup);
    updatePartnerFieldCounter();
}

// --- NEW FUNCTION: Remove a dynamically added partner selection field ---
function removePartnerSelectionField(button) {
    const groupToRemove = button.closest('.partner-selection-group');
    groupToRemove.remove();
    updatePartnerFieldCounter();
    // Re-index labels if desired, but for now, simple removal is enough.
}

// --- NEW FUNCTION: Update the counter for dynamically added fields ---
function updatePartnerFieldCounter() {
    const currentFields = document.querySelectorAll('.partner-selection-group').length -1 ; // -1 for initial field
    const counterSpan = document.getElementById('partnerFieldCounter');
    if (counterSpan) {
        counterSpan.textContent = `${currentFields}/${MAX_ADDITIONAL_PARTNERS}`;
        const addBtn = document.getElementById('addPartnerFieldBtn');
        if (addBtn) {
            if (currentFields >= MAX_ADDITIONAL_PARTNERS) {
                addBtn.style.display = 'none'; // Hide button if max reached
            } else {
                addBtn.style.display = ''; // Show button
            }
        }
    }
    // Show/hide remove button for the first field if it's the only one left
    const firstRemoveBtn = document.querySelector('.partner-selection-group[data-partner-index="0"] .remove-partner-field');
    if (firstRemoveBtn) {
        if (currentFields === 0) {
            firstRemoveBtn.style.display = 'none';
        } else {
            firstRemoveBtn.style.display = '';
        }
    }
}

// --- NEW FUNCTION: Reset partner selection fields to initial state ---
function resetPartnerSelectionFields() {
    const partnerSelectionGroupsDiv = document.getElementById('partnerSelectionGroups');
    // Remove all groups except the first one (index 0)
    partnerSelectionGroupsDiv.querySelectorAll('.partner-selection-group[data-partner-index]:not([data-partner-index="0"])').forEach(group => {
        group.remove();
    });

    // Reset the first group
    const firstGroup = document.querySelector('.partner-selection-group[data-partner-index="0"]');
    if (firstGroup) {
        const firstTypeSelect = firstGroup.querySelector('.partner-type-select');
        const firstSpecificSelectDiv = firstGroup.querySelector('.specific-partner-selection');
        const firstMessageTextarea = firstGroup.querySelector('.message-content-textarea');

        if (firstTypeSelect) {
            firstTypeSelect.value = ''; // Reset selected type
        }
        if (firstSpecificSelectDiv) {
            firstSpecificSelectDiv.style.display = 'none'; // Hide specific dropdown
            firstSpecificSelectDiv.querySelector('.specific-partner-select').innerHTML = '<option value="">Pilih Partner</option>'; // Clear options
        }
        if (firstMessageTextarea) {
            firstMessageTextarea.value = ''; // Clear message content
        }
    }
    partnerFieldIndex = 0; // Reset index counter
    updatePartnerFieldCounter(); // Update counter and button visibility
}


// MODIFIED FUNCTION: Populate Specific Partners Dropdown (now takes the changed select element)
function populateSpecificPartners(typeSelectElement) {
    const partnerType = typeSelectElement.value;
    const parentGroup = typeSelectElement.closest('.partner-selection-group');
    const specificPartnerSelectionDiv = parentGroup.querySelector('.specific-partner-selection');
    const specificPartnerSelect = parentGroup.querySelector('.specific-partner-select');
    specificPartnerSelect.innerHTML = '<option value="">Pilih Partner</option>'; // Clear previous options

    if (partnerType === '' || partnerType === 'semua') {
        specificPartnerSelectionDiv.style.display = 'none'; // Hide if no type or "All Partners" is selected
        return;
    }

    specificPartnerSelectionDiv.style.display = 'block'; // Show the dropdown

    // Get partners from the "Kelola Partner" table
    const partnerTableRows = document.querySelectorAll('#kelolaPartner .data-table tbody tr');
    const partners = [];

    partnerTableRows.forEach(row => {
        const rowPartnerType = row.dataset.partnerType;
        if (rowPartnerType === partnerType) {
            const partnerName = row.querySelector('.partner-name').textContent;
            partners.push(partnerName);
        }
    });

    // Populate the specific partner dropdown
    partners.forEach(partner => {
        const option = document.createElement('option');
        option.value = partner;
        option.textContent = partner;
        specificPartnerSelect.appendChild(option);
    });
}

// Fungsi untuk mengelola status booking
function updateBookingStatus(rowElement, newStatus) {
    const bookingId = rowElement.dataset.bookingId;
    const statusBadge = rowElement.querySelector('.status-badge');
    const verifyButton = rowElement.querySelector('.btn-verify');
    const assignButton = rowElement.querySelector('.btn-assign');
    const cancelButton = rowElement.querySelector('.btn-danger');

    if (!bookingId || !statusBadge) {
        console.error('Booking ID atau status badge tidak ditemukan untuk baris ini.');
        return;
    }

    // Hapus kelas status yang ada dan tambahkan yang baru
    statusBadge.classList.remove('pending', 'in-progress', 'confirmed', 'cancelled');
    statusBadge.classList.add(newStatus);
    statusBadge.textContent = newStatus.replace('-', ' ');

    // Perbarui visibilitas tombol berdasarkan status baru
    if (newStatus === 'pending') {
        if (verifyButton) verifyButton.style.display = '';
        if (assignButton) assignButton.style.display = 'none';
        if (cancelButton) cancelButton.style.display = '';
    } else if (newStatus === 'in-progress') {
        if (verifyButton) verifyButton.style.display = 'none';
        if (assignButton) assignButton.style.display = ''; // Tampilkan tombol assign
        if (cancelButton) cancelButton.style.display = '';
    } else if (newStatus === 'confirmed') {
        if (verifyButton) verifyButton.style.display = 'none';
        if (assignButton) assignButton.style.display = 'none';
        if (cancelButton) cancelButton.style.display = '';
    } else if (newStatus === 'cancelled') {
        if (verifyButton) verifyButton.style.display = 'none';
        if (assignButton) assignButton.style.display = 'none';
        if (cancelButton) cancelButton.style.display = 'none';
    }

    alert(`Status booking ${bookingId} berhasil diperbarui menjadi: ${newStatus.toUpperCase()}!`);
}

// Fungsi untuk memverifikasi pembayaran
function verifyBookingPayment(button) {
    const row = button.closest('tr');
    const currentStatus = row.querySelector('.status-badge').textContent.trim().toLowerCase(); // Ambil status dari badge saat ini
    if (currentStatus === 'pending') {
        updateBookingStatus(row, 'in-progress');
    } else {
        alert('Pembayaran sudah diverifikasi atau booking tidak dalam status pending.');
    }
}

// MODIFIKASI FUNGSI assignBookingPartner
function assignBookingPartner(button) {
    const row = button.closest('tr');
    const bookingId = row.dataset.bookingId;
    const statusBadgeText = row.querySelector('.status-badge').textContent.trim().toLowerCase(); // Dapatkan status terbaru dari badge

    // Kondisi ini lebih robust: pastikan statusnya memang in-progress ATAU tombol assign memang sedang terlihat
    // Jika tombol assign terlihat, berarti updateBookingStatus sudah menjalankannya
    if (statusBadgeText === 'in-progress' || button.style.display !== 'none') {
        document.getElementById('currentBookingId').value = bookingId;

        const partnerIconsElement = row.querySelector('.partner-icons');
        const neededPartners = [];
        if (partnerIconsElement) {
            if (partnerIconsElement.querySelector('.fa-map-marked-alt')) neededPartners.push('Guide');
            if (partnerIconsElement.querySelector('.fa-box')) neededPartners.push('Porter');
            if (partnerIconsElement.querySelector('.fa-motorcycle')) neededPartners.push('Ojek');
        }

        // Generate suggested message content
        let suggestedMessage = `Permintaan layanan untuk Booking ID: ${bookingId}\n`;
        if (neededPartners.length > 0) {
            suggestedMessage += `Partner Dibutuhkan: ${neededPartners.join(', ')}\n`;
        }
        suggestedMessage += `Detail singkat pesanan:\n`;
        const pendakiName = row.children[1].textContent.trim();
        const gunungName = row.children[2].textContent.trim();
        const tanggal = row.children[3].textContent.trim();
        suggestedMessage += `Nama Pendaki: ${pendakiName}\nGunung: ${gunungName}\nTanggal: ${tanggal}\n`;
        suggestedMessage += `Mohon konfirmasi ketersediaan dan nilai layanannya.`;

        // Reset all dynamic fields and pre-fill the first one
        resetPartnerSelectionFields();
        const firstMessageTextarea = document.getElementById('messageContent_0');
        if (firstMessageTextarea) {
            firstMessageTextarea.value = suggestedMessage;
        }

        if (neededPartners.length > 0) {
            const firstPartnerTypeSelect = document.getElementById('partnerType_0');
            if (firstPartnerTypeSelect) {
                // Try to pre-select the first needed partner type
                const firstNeededType = neededPartners[0].toLowerCase();
                if ([...firstPartnerTypeSelect.options].some(opt => opt.value === firstNeededType)) {
                    firstPartnerTypeSelect.value = firstNeededType;
                    populateSpecificPartners(firstPartnerTypeSelect);
                }
            }
            // If more than one needed, admin can use "Tambah Partner"
            if (neededPartners.length > 1) {
                alert(`Booking ini membutuhkan ${neededPartners.length} partner. Bidang pertama telah diisi, silakan gunakan tombol "Tambah Partner" untuk memilih partner lainnya.`);
            }
        }


        if (window.navLinks) {
            window.navLinks.forEach(link => {
                if (link.dataset.target === 'partnerNetwork') {
                    link.click();
                }
            });
        }
        alert('Anda telah dialihkan ke "Partner Network". Silakan pilih partner yang relevan dan kirim pesan untuk booking ini.');

    } else {
        alert('Booking belum diverifikasi pembayarannya atau sudah terkonfirmasi.');
    }
}

// Fungsi untuk membatalkan booking
function cancelBooking(button) {
    const row = button.closest('tr');
    const bookingId = row.dataset.bookingId;
    if (confirm(`Anda yakin ingin membatalkan booking ${bookingId}?`)) {
        updateBookingStatus(row, 'cancelled');
    }
}