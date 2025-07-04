<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pageTitle = "Dashboard Super Admin";

// Inisialisasi variabel untuk menampung data form jika ada error
$firstName = ''; $lastName = ''; $email = ''; $phone = '';
$createUserError = ''; $createUserSuccess = '';

if (isset($_POST['submit_create_user'])) {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $gunung_id = $_POST['gunung_id'] ?? null;
    $role = 'pengelola_gunung';

    if (empty($firstName) || empty($email) || empty($password) || empty($gunung_id)) {
        $createUserError = 'Semua field wajib diisi, termasuk gunung yang akan dikelola.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $createUserError = 'Email sudah terdaftar. Gunakan email lain.';
            } else {
                $pdo->beginTransaction();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmtUser = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmtUser->execute([$firstName, $lastName, $email, $phone, $hashedPassword, $role]);
                $newUserId = $pdo->lastInsertId();
                $stmtGunung = $pdo->prepare("UPDATE gunung SET admin_id = ? WHERE id = ?");
                $stmtGunung->execute([$newUserId, $gunung_id]);
                $pdo->commit();
                $createUserSuccess = "Akun Pengelola Gunung untuk " . htmlspecialchars($firstName) . " berhasil dibuat.";
                $firstName = $lastName = $email = $phone = '';
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $createUserError = 'Gagal membuat akun: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles/dashboard-pengelola.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo"><h2>🏔️ Super Admin</h2><p>Sistem Pengelolaan</p></div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#" class="nav-link active" data-target="dashboard">📊 Dashboard</a></li>
                    <li><a href="#" class="nav-link" data-target="buatPengguna">➕ Buat Pengelola</a></li>
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

            <section id="dashboard" class="section active">
                <div class="section-header"><h2>Ringkasan Seluruh Gunung</h2></div>
                <p>Selamat datang di dashboard Super Admin.</p>
            </section>

            <section id="buatPengguna" class="section">
                <div class="section-header"><h2>Buat Akun Pengelola Gunung</h2></div>
                <div class="form-container">
                    <?php if ($createUserError) echo "<div class='message error'>$createUserError</div>"; ?>
                    <?php if ($createUserSuccess) echo "<div class='message success'>$createUserSuccess</div>"; ?>
                    
                    <form method="POST" action="admin.php#buatPengguna" id="create-user-form">
                        <div class="form-group">
                            <label for="firstName">Nama Depan</label>
                            <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Nama Belakang</label>
                            <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="gunung_id">Tugaskan ke Gunung</label>
                            <select id="gunung_id" name="gunung_id" required>
                                <option value="" disabled selected>-- Pilih Gunung --</option>
                                <?php
                                    $stmtGunung = $pdo->prepare("SELECT id, nama_gunung FROM gunung WHERE admin_id IS NULL");
                                    $stmtGunung->execute();
                                    foreach ($stmtGunung->fetchAll() as $gunung) {
                                        echo "<option value='".htmlspecialchars($gunung['id'])."'>".htmlspecialchars($gunung['nama_gunung'])."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" name="submit_create_user" class="btn-primary">Buat Akun</button>
                    </form>
                </div>
            </section>
        </main>
    </div>
    
    <script src="scripts/dashboard-nav.js"></script>
    <script>
        document.getElementById('create-user-form').addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>