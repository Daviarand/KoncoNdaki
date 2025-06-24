// Chart.js Config
const trendCtx = document.getElementById('trendChart').getContext('2d');
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
            label: 'Jumlah Pendaki',
            data: [850, 920, 1100, 1350, 1180, 1247],
            borderColor: 'rgb(22, 163, 74)',
            backgroundColor: 'rgba(22, 163, 74, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

const pathCtx = document.getElementById('pathChart').getContext('2d');
new Chart(pathCtx, {
    type: 'doughnut',
    data: {
        labels: ['Jalur Selo', 'Jalur Cemoro Kandang', 'Jalur Kaliurang', 'Jalur Babadan'],
        datasets: [{
            data: [35, 28, 22, 15],
            backgroundColor: ['#16a34a', '#15803d', '#4b5563', '#9ca3af'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Kelola Kuota
function updateKuota(button) {
    const row = button.closest('tr');
    const jalur = row.cells[0].textContent;
    const kuotaBaru = row.querySelector('input').value;
    alert(`✅ Kuota untuk ${jalur} berhasil diperbarui ke ${kuotaBaru} orang.`);
}

function hapusKuota(button) {
    const row = button.closest('tr');
    row.remove();
}

function tambahKuota(e) {
    e.preventDefault();
    const jalur = document.getElementById('inputJalur').value.trim();
    const kuota = document.getElementById('inputKuota').value.trim();
    if (jalur === '' || kuota === '') {
        alert('⚠️ Mohon lengkapi semua data.');
        return;
    }
    const tbody = document.querySelector('.kuota-table tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${jalur}</td>
        <td><input type="number" value="${kuota}"></td>
        <td>0</td>
        <td>
            <button class="btn btn-primary" onclick="updateKuota(this)">Simpan</button>
            <button class="btn btn-danger" onclick="hapusKuota(this)">Hapus</button>
        </td>
    `;
    tbody.appendChild(row);
    document.getElementById('inputJalur').value = '';
    document.getElementById('inputKuota').value = '';
}

// Data Pendaki
function hapusPendaki(button) {
    const row = button.closest('tr');
    row.remove();
}

// Navigasi Sidebar Scroll
const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        navLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');

        if (link.textContent.includes('Kelola Kuota')) {
            document.getElementById('kelolaKuota').scrollIntoView({ behavior: 'smooth' });
        } else if (link.textContent.includes('Data Pendaki')) {
            document.getElementById('dataPendaki').scrollIntoView({ behavior: 'smooth' });
        } else if (link.textContent.includes('Laporan Keuangan')) {
            document.getElementById('laporanKeuangan').scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Download Laporan Keuangan sebagai PDF
function downloadKeuanganPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.text("Laporan Keuangan", 14, 15);

    const table = document.getElementById("tableKeuangan");
    let y = 25;

    for (let row of table.rows) {
        let text = "";
        for (let cell of row.cells) {
            text += cell.textContent + "   ";
        }
        doc.text(text, 14, y);
        y += 10;
    }

    doc.save("Laporan_Keuangan.pdf");
}
