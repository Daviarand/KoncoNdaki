<?php
session_start();
require_once 'config/database.php';

// Keamanan: Pastikan hanya Super Admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pageTitle = "Dashboard Super Admin";
$actionMessage = '';
$createUserError = '';

// --- LOGIKA AKSI POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aksi Menonaktifkan Akun
    if (isset($_POST['deactivate_user'])) {
        $user_id = intval($_POST['user_id']);
        try {
            $stmtRole = $pdo->prepare("SELECT role FROM users WHERE id = ?");
            $stmtRole->execute([$user_id]);
            $userToDelete = $stmtRole->fetch();

            $pdo->beginTransaction();
            if ($userToDelete && $userToDelete['role'] === 'pengelola_gunung') {
                 $pdo->prepare("UPDATE gunung SET admin_id = NULL WHERE admin_id = ?")->execute([$user_id]);
            }
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
            $pdo->commit();
            $_SESSION['actionMessage'] = "Akun pengguna berhasil dinonaktifkan.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['actionMessage'] = "Gagal menonaktifkan akun: " . $e->getMessage();
        }
        header("Location: admin.php#manajemen");
        exit;
    }

    // Aksi Membuat Akun Pengelola Baru
    if (isset($_POST['submit_create_user'])) {
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $gunung_id = $_POST['gunung_id'] ?? null;
        if (empty($firstName) || empty($email) || empty($password) || empty($gunung_id)) {
            $createUserError = 'Semua field wajib diisi.';
        } else {
             try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $createUserError = 'Email sudah terdaftar. Gunakan email lain.';
                } else {
                    $pdo->beginTransaction();
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmtUser = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, 'pengelola_gunung', NOW())");
                    $stmtUser->execute([$firstName, $lastName, $email, $phone, $hashedPassword]);
                    $newUserId = $pdo->lastInsertId();
                    $stmtGunung = $pdo->prepare("UPDATE gunung SET admin_id = ? WHERE id = ?");
                    $stmtGunung->execute([$newUserId, $gunung_id]);
                    $pdo->commit();
                    $_SESSION['actionMessage'] = "Akun Pengelola Gunung untuk " . htmlspecialchars($firstName) . " berhasil dibuat.";
                    header("Location: admin.php#manajemen");
                    exit;
                }
            } catch (PDOException $e) {
                $pdo->rollBack();
                $createUserError = 'Gagal membuat akun: ' . $e->getMessage();
            }
        }
    }

    // CRUD Gunung
    if (isset($_POST['action_gunung'])) {
        $action = $_POST['action_gunung'];
        
        if ($action === 'create') {
            $nama_gunung = trim($_POST['nama_gunung']);
            $lokasi = trim($_POST['lokasi']);
            $ketinggian = intval($_POST['ketinggian']);
            $kuota_pendaki_harian = intval($_POST['kuota_pendaki_harian']);
            $status = $_POST['status'];
            $deskripsi = trim($_POST['deskripsi']);
            
            try {
                $stmt = $pdo->prepare("INSERT INTO gunung (nama_gunung, lokasi, ketinggian, kuota_pendaki_harian, status, deskripsi, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$nama_gunung, $lokasi, $ketinggian, $kuota_pendaki_harian, $status, $deskripsi]);
                $_SESSION['actionMessage'] = "Data gunung berhasil ditambahkan.";
            } catch (PDOException $e) {
                $_SESSION['actionMessage'] = "Gagal menambahkan data gunung: " . $e->getMessage();
            }
        }
        
        if ($action === 'update') {
            $id = intval($_POST['gunung_id']);
            $nama_gunung = trim($_POST['nama_gunung']);
            $lokasi = trim($_POST['lokasi']);
            $ketinggian = intval($_POST['ketinggian']);
            $kuota_pendaki_harian = intval($_POST['kuota_pendaki_harian']);
            $status = $_POST['status'];
            $deskripsi = trim($_POST['deskripsi']);
            
            try {
                $stmt = $pdo->prepare("UPDATE gunung SET nama_gunung = ?, lokasi = ?, ketinggian = ?, kuota_pendaki_harian = ?, status = ?, deskripsi = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$nama_gunung, $lokasi, $ketinggian, $kuota_pendaki_harian, $status, $deskripsi, $id]);
                $_SESSION['actionMessage'] = "Data gunung berhasil diperbarui.";
            } catch (PDOException $e) {
                $_SESSION['actionMessage'] = "Gagal memperbarui data gunung: " . $e->getMessage();
            }
        }
        
        if ($action === 'delete') {
            $id = intval($_POST['gunung_id']);
            try {
                $pdo->beginTransaction();
                // Update admin_id menjadi NULL untuk pengelola yang mengelola gunung ini
                $pdo->prepare("UPDATE gunung SET admin_id = NULL WHERE id = ?")->execute([$id]);
                // Hapus data gunung
                $pdo->prepare("DELETE FROM gunung WHERE id = ?")->execute([$id]);
                $pdo->commit();
                $_SESSION['actionMessage'] = "Data gunung berhasil dihapus.";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $_SESSION['actionMessage'] = "Gagal menghapus data gunung: " . $e->getMessage();
            }
        }
        
        header("Location: admin.php#manajemen");
        exit;
    }

    // CRUD Layanan
    if (isset($_POST['action_layanan'])) {
        $action = $_POST['action_layanan'];
        
        if ($action === 'create') {
            $nama_layanan = trim($_POST['nama_layanan']);
            $deskripsi = trim($_POST['deskripsi']);
            $harga_layanan = floatval($_POST['harga_layanan']);
            $satuan = trim($_POST['satuan']);
            
            try {
                $stmt = $pdo->prepare("INSERT INTO layanan (nama_layanan, deskripsi, harga_layanan, satuan) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nama_layanan, $deskripsi, $harga_layanan, $satuan]);
                $_SESSION['actionMessage'] = "Data layanan berhasil ditambahkan.";
            } catch (PDOException $e) {
                $_SESSION['actionMessage'] = "Gagal menambahkan data layanan: " . $e->getMessage();
            }
        }
        
        if ($action === 'update') {
            $id = intval($_POST['layanan_id']);
            $nama_layanan = trim($_POST['nama_layanan']);
            $deskripsi = trim($_POST['deskripsi']);
            $harga_layanan = floatval($_POST['harga_layanan']);
            $satuan = trim($_POST['satuan']);
            
            try {
                $stmt = $pdo->prepare("UPDATE layanan SET nama_layanan = ?, deskripsi = ?, harga_layanan = ?, satuan = ? WHERE id = ?");
                $stmt->execute([$nama_layanan, $deskripsi, $harga_layanan, $satuan, $id]);
                $_SESSION['actionMessage'] = "Data layanan berhasil diperbarui.";
            } catch (PDOException $e) {
                $_SESSION['actionMessage'] = "Gagal memperbarui data layanan: " . $e->getMessage();
            }
        }
        
        if ($action === 'delete') {
            $id = intval($_POST['layanan_id']);
            try {
                $pdo->prepare("DELETE FROM layanan WHERE id = ?")->execute([$id]);
                $_SESSION['actionMessage'] = "Data layanan berhasil dihapus.";
            } catch (PDOException $e) {
                $_SESSION['actionMessage'] = "Gagal menghapus data layanan: " . $e->getMessage();
            }
        }
        
        header("Location: admin.php#manajemen");
        exit;
    }
}

if (isset($_SESSION['actionMessage'])) {
    $actionMessage = $_SESSION['actionMessage'];
    unset($_SESSION['actionMessage']);
}

// --- PENGAMBILAN DATA (SEMUA BAGIAN) ---
// Data Dashboard
$stmtKpi = $pdo->query("SELECT (SELECT COUNT(*) FROM users WHERE role = 'pendaki') as total_pendaki, (SELECT COUNT(*) FROM pemesanan WHERE status_pemesanan IN ('pending', 'menunggu pembayaran')) as total_booking_baru, (SELECT SUM(total_harga) FROM pemesanan WHERE status_pemesanan = 'complete' AND MONTH(tanggal_pemesanan) = MONTH(CURDATE())) as total_pendapatan_bulan_ini, (SELECT g.nama_gunung FROM pemesanan p JOIN tiket_gunung tg ON p.tiket_id = tg.id JOIN gunung g ON tg.id = g.id GROUP BY g.nama_gunung ORDER BY COUNT(p.id) DESC LIMIT 1) as gunung_populer");
$kpi = $stmtKpi->fetch(PDO::FETCH_ASSOC);
$stmtPendapatanGunung = $pdo->query("SELECT g.nama_gunung, SUM(p.total_harga) as pendapatan FROM pemesanan p JOIN tiket_gunung tg ON p.tiket_id = tg.id JOIN gunung g ON tg.id = g.id WHERE p.status_pemesanan = 'complete' AND MONTH(p.tanggal_pemesanan) = MONTH(CURDATE()) GROUP BY g.nama_gunung ORDER BY pendapatan DESC");
$pendapatanPerGunung = $stmtPendapatanGunung->fetchAll(PDO::FETCH_ASSOC);
$labelsPendapatan = array_column($pendapatanPerGunung, 'nama_gunung');
$dataPendapatan = array_column($pendapatanPerGunung, 'pendapatan');
$stmtRole = $pdo->query("SELECT role, COUNT(*) as jumlah FROM users GROUP BY role");
$roleData = $stmtRole->fetchAll(PDO::FETCH_KEY_PAIR);

// Data Laporan
$filterGunung = $_GET['filter_gunung'] ?? 'semua';
$filterWaktu = $_GET['filter_waktu'] ?? 'bulanan';
$baseSqlLaporan = " FROM pemesanan p JOIN tiket_gunung tg ON p.tiket_id = tg.id JOIN gunung g ON tg.id = g.id WHERE p.status_pemesanan = 'complete'";
$params = [];
$filterSql = "";
if ($filterGunung !== 'semua') {
    $filterSql .= " AND g.id = :gunung_id";
    $params[':gunung_id'] = $filterGunung;
}
if ($filterWaktu === 'mingguan') {
    $filterSql .= " AND p.tanggal_pemesanan >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
} else {
    $filterSql .= " AND MONTH(p.tanggal_pemesanan) = MONTH(CURDATE()) AND YEAR(p.tanggal_pemesanan) = YEAR(CURDATE())";
}
$sqlLaporan = "SELECT p.kode_booking, g.nama_gunung, p.tanggal_pemesanan, p.subtotal_tiket, p.subtotal_layanan, p.total_harga " . $baseSqlLaporan . $filterSql . " ORDER BY p.tanggal_pemesanan DESC";
$stmtLaporan = $pdo->prepare($sqlLaporan);
$stmtLaporan->execute($params);
$laporanKeuangan = $stmtLaporan->fetchAll(PDO::FETCH_ASSOC);
$sqlAnalisis = "SELECT SUM(p.subtotal_tiket) as total_tiket, SUM(p.subtotal_layanan) as total_layanan " . $baseSqlLaporan . $filterSql;
$stmtAnalisisPendapatan = $pdo->prepare($sqlAnalisis);
$stmtAnalisisPendapatan->execute($params);
$analisisPendapatan = $stmtAnalisisPendapatan->fetch(PDO::FETCH_ASSOC);
$stmtOkupansi = $pdo->query("SELECT g.nama_gunung, g.kuota_pendaki_harian, COALESCE(SUM(p.jumlah_pendaki), 0) as total_pendaki, (COALESCE(SUM(p.jumlah_pendaki), 0) / (g.kuota_pendaki_harian * 30)) * 100 AS persentase_okupansi FROM gunung g LEFT JOIN tiket_gunung tg ON g.id = tg.id LEFT JOIN pemesanan p ON tg.id = p.tiket_id AND p.status_pemesanan = 'complete' AND MONTH(p.tanggal_pendakian) = MONTH(CURDATE()) GROUP BY g.id ORDER BY persentase_okupansi DESC");
$laporanOkupansi = $stmtOkupansi->fetchAll(PDO::FETCH_ASSOC);

// Data Manajemen Sistem
$stmtPengelola = $pdo->query("SELECT u.id, u.first_name, u.last_name, u.email, u.phone, g.nama_gunung FROM users u LEFT JOIN gunung g ON u.id = g.admin_id WHERE u.role = 'pengelola_gunung' ORDER BY u.created_at DESC");
$daftarPengelola = $stmtPengelola->fetchAll(PDO::FETCH_ASSOC);
$stmtPendaki = $pdo->query("SELECT id, first_name, last_name, email, phone FROM users WHERE role = 'pendaki' ORDER BY created_at DESC");
$daftarPendaki = $stmtPendaki->fetchAll(PDO::FETCH_ASSOC);
$stmtDaftarGunung = $pdo->query("SELECT * FROM gunung ORDER BY nama_gunung ASC");
$daftarGunung = $stmtDaftarGunung->fetchAll(PDO::FETCH_ASSOC);
$stmtDaftarLayanan = $pdo->query("SELECT * FROM layanan ORDER BY nama_layanan ASC");
$daftarLayanan = $stmtDaftarLayanan->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles/dashboard-pengelola.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
/* Reset dan Base Styles */
* {
    box-sizing: border-box;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    animation: fadeIn 0.3s ease;
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    padding: 0;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: modalSlideIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.modal-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-header h3::before {
    content: "📝";
    font-size: 1.1rem;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6b7280;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    background: #f3f4f6;
    color: #374151;
    transform: scale(1.1);
}

/* Form Container */
.form-container {
    padding: 2rem;
    max-height: calc(90vh - 120px);
    overflow-y: auto;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    background: white;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
    margin-top: 1.5rem;
}

/* Button Styles */
.btn-primary {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 120px;
    justify-content: center;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
}

.btn-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 120px;
    justify-content: center;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
    transform: translateY(-2px);
}

.btn-add {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-add:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
}

/* Table Actions */
.table-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem 0;
    border-bottom: 2px solid #f1f5f9;
}

.table-actions h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.table-actions h3::before {
    content: "📊";
    font-size: 1.1rem;
}

/* Message Styles */
.message {
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid;
}

.message.success {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #16a34a;
    border-color: #bbf7d0;
}

.message.success::before {
    content: "✅";
    font-size: 1.1rem;
}

.message.error {
    background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
    color: #dc2626;
    border-color: #fca5a5;
}

.message.error::before {
    content: "❌";
    font-size: 1.1rem;
}

/* Data Table Improvements */
.booking-table-wrapper {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.data-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.data-table thead th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 700;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f3f4f6;
}

.data-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transform: scale(1.001);
}

.data-table tbody tr:last-child {
    border-bottom: none;
}

.data-table tbody td {
    padding: 1rem 1.5rem;
    color: #4b5563;
    vertical-align: middle;
}

/* Actions Cell */
.actions-cell {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.btn-action {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    min-width: 80px;
    justify-content: center;
}

.btn-action.btn-verify {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white;
}

.btn-action.btn-verify:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(22, 163, 74, 0.25);
}

.btn-action.btn-reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-action.btn-reject:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
}

/* Status Badge */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-badge.complete {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.status-badge.rejected {
    background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
    color: #dc2626;
    border: 1px solid #fca5a5;
}

/* Report Section */
.report-section {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
}

.report-section h3 {
    margin: 0 0 1.5rem 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

/* Form Container in Main Content */
.main-content .form-container {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 2rem;
    border-radius: 1rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .modal-header {
        padding: 1rem 1.5rem;
    }
    
    .form-container {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
    }
    
    .table-actions {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .actions-cell {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-action {
        width: 100%;
    }
    
    .data-table {
        font-size: 0.875rem;
    }
    
    .data-table thead th,
    .data-table tbody td {
        padding: 0.75rem 1rem;
    }
}

@media (max-width: 480px) {
    .data-table thead th,
    .data-table tbody td {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .btn-add {
        width: 100%;
        justify-content: center;
    }
}

/* Loading States */
.btn-primary.loading,
.btn-secondary.loading {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none !important;
}

.btn-primary.loading::before,
.btn-secondary.loading::before {
    content: "";
    width: 1rem;
    height: 1rem;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 0.5rem;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Improved Scrollbar */
.form-container::-webkit-scrollbar {
    width: 6px;
}

.form-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.form-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.form-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Dashboard & Laporan Styling Improvements */

/* Stats Grid Improvements */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #16a34a, #f59e0b, #ef4444);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}

.stat-card:nth-child(1) .stat-icon {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
}

.stat-card:nth-child(2) .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
}

.stat-card:nth-child(3) .stat-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}

.stat-card:nth-child(4) .stat-icon {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1;
}

.stat-label {
    font-size: 0.95rem;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Charts Section Improvements */
.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.chart-container {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
    position: relative;
    overflow: hidden;
}

.chart-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #16a34a);
}

.chart-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

.chart-title::before {
    content: "📊";
    font-size: 1.1rem;
}

/* Section Header Improvements */
.section-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 3px solid #f1f5f9;
}

.section-header h2 {
    font-size: 1.875rem;
    font-weight: 800;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-header h2::before {
    content: "📈";
    font-size: 1.5rem;
}

#dashboard .section-header h2::before {
    content: "📊";
}

/* Filter Form Improvements */
.filter-form {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 1rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.filter-form select {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    font-size: 0.95rem;
    font-weight: 600;
    background: white;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 180px;
}

.filter-form select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.filter-form select:hover {
    border-color: #d1d5db;
    transform: translateY(-1px);
}

/* Report Section Specific Improvements */
#laporan .report-section {
    position: relative;
}

#laporan .report-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #16a34a, #3b82f6);
    border-radius: 1rem 1rem 0 0;
}

#laporan .report-section h3::before {
    content: "💰";
    font-size: 1.1rem;
}

#laporan .booking-table-wrapper h3 {
    margin: 1.5rem 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 0.75rem 0.75rem 0 0;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 0;
}

#laporan .booking-table-wrapper h3::before {
    content: "📊";
    font-size: 1.1rem;
}

/* Dashboard Specific Improvements */
#dashboard .stats-grid {
    animation: fadeInUp 0.6s ease;
}

#dashboard .chart-container {
    animation: fadeInUp 0.8s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Improvements */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 1.75rem;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .chart-container {
        padding: 1.5rem;
    }
    
    .filter-form {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-form select {
        min-width: auto;
        width: 100%;
    }
    
    .section-header h2 {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .stat-card {
        padding: 1.25rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .stat-icon {
        width: 3rem;
        height: 3rem;
        font-size: 1.25rem;
    }
    
    .chart-container {
        padding: 1.25rem;
    }
    
    .chart-title {
        font-size: 1rem;
    }
}

/* Loading States for Charts */
.chart-container.loading {
    position: relative;
}

.chart-container.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 2rem;
    height: 2rem;
    border: 3px solid #f3f4f6;
    border-top: 3px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    transform: translate(-50%, -50%);
}

/* Enhanced Table in Laporan Section */
#laporan .data-table tbody tr:nth-child(even) {
    background: linear-gradient(135deg, #fafbfc 0%, #f8fafc 100%);
}

#laporan .data-table tbody tr:hover {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    transform: scale(1.001);
}

/* Improved spacing for better visual hierarchy */
.section {
    padding: 2rem 0;
}

.section:not(:last-child) {
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 2rem;
}
</style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo"><h2>🏔️ Super Admin</h2><p>KoncoNdaki MIS</p></div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#dashboard" class="nav-link" data-target="dashboard">📊 Dashboard</a></li>
                    <li><a href="#laporan" class="nav-link" data-target="laporan">📈 Laporan & Analisis</a></li>
                    <li><a href="#manajemen" class="nav-link" data-target="manajemen">⚙️ Manajemen Sistem</a></li>
                    <li><a href="#tambahPengelola" class="nav-link" data-target="tambahPengelola">➕ Tambah Pengelola</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="header">
                <h1><?php echo $pageTitle; ?></h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
                    <a href="auth/logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <section id="dashboard" class="section">
                <div class="section-header"><h2>Ringkasan Seluruh Gunung</h2></div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                        <div class="stat-number">Rp <?php echo number_format($kpi['total_pendapatan_bulan_ini'] ?? 0, 0, ',', '.'); ?></div>
                        <div class="stat-label">Pendapatan Bulan Ini</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-book"></i></div>
                        <div class="stat-number"><?php echo $kpi['total_booking_baru'] ?? 0; ?></div>
                        <div class="stat-label">Booking Perlu Diproses</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-number"><?php echo $kpi['total_pendaki'] ?? 0; ?></div>
                        <div class="stat-label">Total Pendaki Terdaftar</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                        <div class="stat-number"><?php echo htmlspecialchars($kpi['gunung_populer'] ?? '-'); ?></div>
                        <div class="stat-label">Gunung Terpopuler</div>
                    </div>
                </div>
                <div class="charts-section">
                    <div class="chart-container">
                        <h3 class="chart-title">Performa Pendapatan Antar Gunung (Bulan Ini)</h3>
                        <canvas id="pendapatanChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3 class="chart-title">Komposisi Peran Pengguna</h3>
                        <canvas id="roleChart"></canvas>
                    </div>
                </div>
            </section>

            <section id="laporan" class="section">
                <div class="section-header"><h2>Laporan & Analisis Lintas Gunung</h2></div>
                <div class="report-section">
                    <h3>Laporan Keuangan Konsolidasi</h3>
                    <form method="get" class="filter-form" action="admin.php#laporan">
                        <select name="filter_gunung" onchange="this.form.submit()">
                            <option value="semua">Semua Gunung</option>
                            <?php
                            $stmtGunungList = $pdo->query("SELECT id, nama_gunung FROM gunung");
                            foreach ($stmtGunungList->fetchAll() as $gunung) {
                                $selected = ($filterGunung == $gunung['id']) ? 'selected' : '';
                                echo "<option value='{$gunung['id']}' {$selected}>" . htmlspecialchars($gunung['nama_gunung']) . "</option>";
                            }
                            ?>
                        </select>
                        <select name="filter_waktu" onchange="this.form.submit()">
                            <option value="bulanan" <?php echo ($filterWaktu === 'bulanan') ? 'selected' : ''; ?>>Bulan Ini</option>
                            <option value="mingguan" <?php echo ($filterWaktu === 'mingguan') ? 'selected' : ''; ?>>7 Hari Terakhir</option>
                        </select>
                    </form>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>Kode Booking</th><th>Nama Gunung</th><th>Tgl. Pesan</th><th>Subtotal Tiket</th><th>Subtotal Layanan</th><th>Total Harga</th></tr></thead>
                            <tbody>
                                <?php if (empty($laporanKeuangan)): ?>
                                    <tr><td colspan="6" style="text-align:center;">Tidak ada data untuk filter yang dipilih.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($laporanKeuangan as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['kode_booking']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_gunung']); ?></td>
                                        <td><?php echo date('d M Y', strtotime($row['tanggal_pemesanan'])); ?></td>
                                        <td>Rp <?php echo number_format($row['subtotal_tiket'], 0, ',', '.'); ?></td>
                                        <td>Rp <?php echo number_format($row['subtotal_layanan'], 0, ',', '.'); ?></td>
                                        <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="charts-section">
                    <div class="chart-container">
                        <h3 class="chart-title">Analisis Pendapatan (Sesuai Filter)</h3>
                        <canvas id="analisisPendapatanChart"></canvas>
                    </div>
                    <div class="booking-table-wrapper">
                        <h3 class="chart-title">Tingkat Okupansi Kuota (Bulan Ini)</h3>
                        <table class="data-table">
                            <thead><tr><th>Nama Gunung</th><th>Pendaki Aktual</th><th>Okupansi</th></tr></thead>
                            <tbody>
                                <?php foreach ($laporanOkupansi as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nama_gunung']); ?></td>
                                    <td><?php echo number_format($row['total_pendaki'], 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($row['persentase_okupansi'], 2, ',', '.'); ?>%</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            
            <section id="manajemen" class="section">
                <div class="section-header"><h2>Manajemen Sistem</h2></div>
                <?php if ($actionMessage): ?>
                    <div class="message success" style="margin-bottom: 1rem;"><?php echo htmlspecialchars($actionMessage); ?></div>
                <?php endif; ?>

                <div class="report-section">
                    <h3>Daftar Akun Pengelola Gunung</h3>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>Nama</th><th>Email & Telp</th><th>Mengelola Gunung</th><th>Aksi</th></tr></thead>
                            <tbody>
                                <?php if (empty($daftarPengelola)): ?>
                                    <tr><td colspan="4" style="text-align: center;">Belum ada akun pengelola.</td></tr>
                                <?php else: ?>
                                <?php foreach ($daftarPengelola as $pengelola): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pengelola['first_name'] . ' ' . $pengelola['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($pengelola['email']); ?><br><small><?php echo htmlspecialchars($pengelola['phone']); ?></small></td>
                                    <td><?php echo htmlspecialchars($pengelola['nama_gunung'] ?? '<i>Tidak ditugaskan</i>'); ?></td>
                                    <td class="actions-cell">
                                        <form method="POST" action="admin.php#manajemen" onsubmit="return confirm('Anda yakin ingin menonaktifkan akun ini?');" style="display:inline;">
                                            <input type="hidden" name="deactivate_user" value="1">
                                            <input type="hidden" name="user_id" value="<?php echo $pengelola['id']; ?>">
                                            <button type="submit" class="btn-action btn-reject">Nonaktifkan</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="report-section">
                    <h3>Daftar User Pendaki</h3>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Aksi</th></tr></thead>
                            <tbody>
                                <?php if (empty($daftarPendaki)): ?>
                                    <tr><td colspan="4" style="text-align: center;">Belum ada user pendaki.</td></tr>
                                <?php else: ?>
                                <?php foreach ($daftarPendaki as $pendaki): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pendaki['first_name'] . ' ' . $pendaki['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($pendaki['email']); ?></td>
                                    <td><?php echo htmlspecialchars($pendaki['phone']); ?></td>
                                    <td class="actions-cell">
                                        <form method="POST" action="admin.php#manajemen" onsubmit="return confirm('Anda yakin ingin menonaktifkan akun ini?');" style="display:inline;">
                                            <input type="hidden" name="deactivate_user" value="1">
                                            <input type="hidden" name="user_id" value="<?php echo $pendaki['id']; ?>">
                                            <button type="submit" class="btn-action btn-reject">Nonaktifkan</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="report-section">
                    <div class="table-actions">
                        <h3>Daftar Data Gunung</h3>
                        <button class="btn-add" onclick="openGunungModal('create')">
                            <i class="fas fa-plus"></i> Tambah Gunung
                        </button>
                    </div>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                           <thead><tr><th>Nama Gunung</th><th>Lokasi</th><th>Ketinggian</th><th>Kuota Harian</th><th>Status</th><th>Aksi</th></tr></thead>
                           <tbody>
                               <?php foreach ($daftarGunung as $gunung): ?>
                               <tr>
                                   <td><?php echo htmlspecialchars($gunung['nama_gunung']); ?></td>
                                   <td><?php echo htmlspecialchars($gunung['lokasi']); ?></td>
                                   <td><?php echo number_format($gunung['ketinggian']); ?> mdpl</td>
                                   <td><?php echo number_format($gunung['kuota_pendaki_harian']); ?> orang</td>
                                   <td><span class="status-badge <?php echo $gunung['status'] === 'buka' ? 'complete' : 'rejected'; ?>"><?php echo ucfirst($gunung['status']); ?></span></td>
                                   <td class="actions-cell">
                                       <button class="btn-action btn-verify" onclick="openGunungModal('edit', <?php echo htmlspecialchars(json_encode($gunung)); ?>)">Edit</button>
                                       <form method="POST" action="admin.php#manajemen" onsubmit="return confirm('Anda yakin ingin menghapus data gunung ini?');" style="display:inline;">
                                           <input type="hidden" name="action_gunung" value="delete">
                                           <input type="hidden" name="gunung_id" value="<?php echo $gunung['id']; ?>">
                                           <button type="submit" class="btn-delete">Hapus</button>
                                       </form>
                                   </td>
                               </tr>
                               <?php endforeach; ?>
                           </tbody>
                        </table>
                    </div>
                </div>

                <div class="report-section">
                    <div class="table-actions">
                        <h3>Daftar Layanan Tambahan</h3>
                        <button class="btn-add" onclick="openLayananModal('create')">
                            <i class="fas fa-plus"></i> Tambah Layanan
                        </button>
                    </div>
                    <div class="booking-table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>Nama Layanan</th><th>Deskripsi</th><th>Harga</th><th>Satuan</th><th>Aksi</th></tr></thead>
                            <tbody>
                                <?php foreach ($daftarLayanan as $layanan): ?>
                                <tr>
                                    <td><?php echo ucfirst(htmlspecialchars($layanan['nama_layanan'])); ?></td>
                                    <td><?php echo htmlspecialchars($layanan['deskripsi']); ?></td>
                                    <td>Rp <?php echo number_format($layanan['harga_layanan'], 0, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($layanan['satuan']); ?></td>
                                    <td class="actions-cell">
                                        <button class="btn-action btn-verify" onclick="openLayananModal('edit', <?php echo htmlspecialchars(json_encode($layanan)); ?>)">Edit</button>
                                        <form method="POST" action="admin.php#manajemen" onsubmit="return confirm('Anda yakin ingin menghapus layanan ini?');" style="display:inline;">
                                            <input type="hidden" name="action_layanan" value="delete">
                                            <input type="hidden" name="layanan_id" value="<?php echo $layanan['id']; ?>">
                                            <button type="submit" class="btn-delete">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="tambahPengelola" class="section">
                 <div class="section-header"><h2>Tambah Pengelola Baru</h2></div>
                 <div class="form-container">
                    <?php if ($createUserError): ?>
                        <div class='message error'><?php echo $createUserError; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="admin.php#tambahPengelola" id="create-user-form">
                        <div class="form-group"><label for="firstName">Nama Depan</label><input type="text" id="firstName" name="firstName" required></div>
                        <div class="form-group"><label for="lastName">Nama Belakang</label><input type="text" id="lastName" name="lastName"></div>
                        <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
                        <div class="form-group"><label for="phone">Telepon</label><input type="tel" id="phone" name="phone" required></div>
                        <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" required></div>
                        <div class="form-group"><label for="gunung_id">Tugaskan ke Gunung</label><select id="gunung_id" name="gunung_id" required><option value="" disabled selected>-- Pilih Gunung --</option><?php $stmtGunungListForm = $pdo->query("SELECT id, nama_gunung FROM gunung WHERE admin_id IS NULL"); foreach ($stmtGunungListForm->fetchAll() as $gunung) { echo "<option value='".htmlspecialchars($gunung['id'])."'>".htmlspecialchars($gunung['nama_gunung'])."</option>"; } ?></select></div>
                        <button type="submit" name="submit_create_user" class="btn-primary">Buat Akun</button>
                    </form>
                 </div>
            </section>
        </main>
    </div>

    <!-- Modal Gunung -->
    <div id="gunungModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="gunungModalTitle">Tambah Data Gunung</h3>
                <button type="button" class="close-btn" onclick="closeGunungModal()">&times;</button>
            </div>
            <div class="form-container">
                <form id="gunungForm" method="POST" action="admin.php#manajemen">
                    <input type="hidden" name="action_gunung" id="gunungAction" value="create">
                    <input type="hidden" name="gunung_id" id="gunungId" value="">
                    
                    <div class="form-group">
                        <label for="nama_gunung">Nama Gunung</label>
                        <input type="text" id="nama_gunung" name="nama_gunung" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ketinggian">Ketinggian (mdpl)</label>
                        <input type="number" id="ketinggian" name="ketinggian" required min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="kuota_pendaki_harian">Kuota Pendaki Harian</label>
                        <input type="number" id="kuota_pendaki_harian" name="kuota_pendaki_harian" required min="1">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="buka">Buka</option>
                            <option value="tutup">Tutup</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeGunungModal()">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Layanan -->
    <div id="layananModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="layananModalTitle">Tambah Layanan</h3>
                <button type="button" class="close-btn" onclick="closeLayananModal()">&times;</button>
            </div>
            <div class="form-container">
                <form id="layananForm" method="POST" action="admin.php#manajemen">
                    <input type="hidden" name="action_layanan" id="layananAction" value="create">
                    <input type="hidden" name="layanan_id" id="layananId" value="">
                    
                    <div class="form-group">
                        <label for="nama_layanan">Nama Layanan</label>
                        <input type="text" id="nama_layanan" name="nama_layanan" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi_layanan">Deskripsi</label>
                        <textarea id="deskripsi_layanan" name="deskripsi" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="harga_layanan">Harga</label>
                        <input type="number" id="harga_layanan" name="harga_layanan" required min="0" step="0.01">
                    </div>
                    
                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" id="satuan" name="satuan" required placeholder="contoh: /hari, /orang, /malam">
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeLayananModal()">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.section');
        function activateTab(targetId) {
            sections.forEach(section => {
                section.classList.remove('active');
            });
            navLinks.forEach(link => {
                link.classList.remove('active');
            });
            const activeSection = document.getElementById(targetId);
            const activeLink = document.querySelector(`.nav-link[data-target='${targetId}']`);
            if (activeSection && activeLink) {
                activeSection.classList.add('active');
                activeLink.classList.add('active');
            }
        }
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.dataset.target;
                window.history.pushState({path: '#' + targetId}, '', '#' + targetId);
                activateTab(targetId);
            });
        });
        let currentHash = window.location.hash.substring(1);
        if (currentHash && document.getElementById(currentHash)) {
            activateTab(currentHash);
        } else {
            activateTab('dashboard'); 
        }

        // --- Inisialisasi Chart.js ---
        if (document.getElementById('pendapatanChart')) {
            new Chart(document.getElementById('pendapatanChart').getContext('2d'), {
                type: 'bar', data: { labels: <?php echo json_encode($labelsPendapatan); ?>, datasets: [{ label: 'Pendapatan (Rp)', data: <?php echo json_encode($dataPendapatan); ?>, backgroundColor: 'rgba(22, 163, 74, 0.7)' }] }, options: { responsive: true, maintainAspectRatio: false }
            });
        }
        if (document.getElementById('roleChart')) {
            new Chart(document.getElementById('roleChart').getContext('2d'), {
                type: 'doughnut', data: { labels: <?php echo json_encode(array_keys($roleData)); ?>, datasets: [{ label: 'Jumlah Pengguna', data: <?php echo json_encode(array_values($roleData)); ?>, backgroundColor: ['#34d399', '#60a5fa', '#facc15', '#a78bfa', '#f87171', '#fb923c'] }] }, options: { responsive: true, maintainAspectRatio: false }
            });
        }
        if (document.getElementById('analisisPendapatanChart')) {
            new Chart(document.getElementById('analisisPendapatanChart').getContext('2d'), {
                type: 'doughnut', data: { labels: ['Pendapatan Tiket', 'Pendapatan Layanan'], datasets: [{ data: [<?php echo $analisisPendapatan['total_tiket'] ?? 0; ?>, <?php echo $analisisPendapatan['total_layanan'] ?? 0; ?>], backgroundColor: ['#36A2EB', '#FFCE56'] }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { tooltip: { callbacks: { label: function(context) { return context.label + ': ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.raw); } } } } }
            });
        }
    });

    // Modal Functions for Gunung
    function openGunungModal(action, data = null) {
        const modal = document.getElementById('gunungModal');
        const title = document.getElementById('gunungModalTitle');
        const form = document.getElementById('gunungForm');
        
        if (action === 'create') {
            title.textContent = 'Tambah Data Gunung';
            document.getElementById('gunungAction').value = 'create';
            form.reset();
            document.getElementById('gunungId').value = '';
        } else if (action === 'edit' && data) {
            title.textContent = 'Edit Data Gunung';
            document.getElementById('gunungAction').value = 'update';
            document.getElementById('gunungId').value = data.id;
            document.getElementById('nama_gunung').value = data.nama_gunung;
            document.getElementById('lokasi').value = data.lokasi;
            document.getElementById('ketinggian').value = data.ketinggian;
            document.getElementById('kuota_pendaki_harian').value = data.kuota_pendaki_harian;
            document.getElementById('status').value = data.status;
            document.getElementById('deskripsi').value = data.deskripsi || '';
        }
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeGunungModal() {
        const modal = document.getElementById('gunungModal');
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Modal Functions for Layanan
    function openLayananModal(action, data = null) {
        const modal = document.getElementById('layananModal');
        const title = document.getElementById('layananModalTitle');
        const form = document.getElementById('layananForm');
        
        if (action === 'create') {
            title.textContent = 'Tambah Layanan';
            document.getElementById('layananAction').value = 'create';
            form.reset();
            document.getElementById('layananId').value = '';
        } else if (action === 'edit' && data) {
            title.textContent = 'Edit Layanan';
            document.getElementById('layananAction').value = 'update';
            document.getElementById('layananId').value = data.id;
            document.getElementById('nama_layanan').value = data.nama_layanan;
            document.getElementById('deskripsi_layanan').value = data.deskripsi || '';
            document.getElementById('harga_layanan').value = data.harga_layanan;
            document.getElementById('satuan').value = data.satuan;
        }
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeLayananModal() {
        const modal = document.getElementById('layananModal');
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const gunungModal = document.getElementById('gunungModal');
        const layananModal = document.getElementById('layananModal');
        
        if (event.target === gunungModal) {
            closeGunungModal();
        }
        if (event.target === layananModal) {
            closeLayananModal();
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeGunungModal();
            closeLayananModal();
        }
    });
    </script>
</body>
</html>
