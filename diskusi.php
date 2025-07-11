<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in - PERBAIKAN: lebih fleksibel
$isLoggedIn = false;

// Cek berbagai kemungkinan session variables
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    $isLoggedIn = true;
} elseif (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    // Jika menggunakan user_id instead of id, normalisasi
    $_SESSION['id'] = $_SESSION['user_id'];
    $isLoggedIn = true;
} elseif (isset($_SESSION['email']) && isset($_SESSION['first_name'])) {
    // Minimal requirement: email dan first_name
    $isLoggedIn = true;
    // Set default id jika tidak ada
    if (!isset($_SESSION['id'])) {
        $_SESSION['id'] = 1; // temporary fallback
    }
}

// Hanya redirect jika benar-benar tidak ada session sama sekali
if (!$isLoggedIn) {
    header('Location: auth/login.php');
    exit;
}

// Handle AJAX requests for forum functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create_post':
            createPost($pdo);
            break;
        case 'get_posts':
            getPosts($pdo);
            break;
        case 'like_post':
            likePost($pdo);
            break;
        case 'add_comment':
            addComment($pdo);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    exit;
}

// Handle GET requests for loading posts
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    getPosts($pdo);
    exit;
}

// Forum functions
function createPost($pdo) {
    try {
        // Cek berbagai kemungkinan user_id
        $user_id = $_SESSION['id'] ?? $_SESSION['user_id'] ?? 1;
        
        $content = trim($_POST['content'] ?? '');
        $category = $_POST['category'] ?? 'pengalaman';
        $title = $_POST['title'] ?? '';
        
        if (empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Content cannot be empty']);
            return;
        }
        
        // If no title provided, create one from content
        if (empty($title)) {
            $title = substr($content, 0, 50) . (strlen($content) > 50 ? '...' : '');
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO forum_posts (user_id, title, content, category, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([$user_id, $title, $content, $category]);
        $post_id = $pdo->lastInsertId();
        
        // Get the created post with user info
        $stmt = $pdo->prepare("
            SELECT fp.*, u.first_name, u.last_name, u.email,
                   0 as like_count, 0 as comment_count,
                   DATE_FORMAT(fp.created_at, '%d/%m/%Y %H:%i') as formatted_date,
                   'Baru saja' as time_ago
            FROM forum_posts fp 
            JOIN users u ON fp.user_id = u.id 
            WHERE fp.id = ?
        ");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Post created successfully',
            'post' => $post
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error creating post: ' . $e->getMessage()]);
    }
}

function getPosts($pdo) {
    try {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $stmt = $pdo->prepare("
            SELECT fp.*, u.first_name, u.last_name, u.email,
                   COALESCE(l.like_count, 0) as like_count,
                   COALESCE(c.comment_count, 0) as comment_count,
                   DATE_FORMAT(fp.created_at, '%d/%m/%Y %H:%i') as formatted_date,
                   CASE 
                       WHEN fp.created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN 'Baru saja'
                       WHEN fp.created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) THEN CONCAT(TIMESTAMPDIFF(HOUR, fp.created_at, NOW()), ' jam yang lalu')
                       ELSE CONCAT(TIMESTAMPDIFF(DAY, fp.created_at, NOW()), ' hari yang lalu')
                   END as time_ago
            FROM forum_posts fp 
            JOIN users u ON fp.user_id = u.id 
            LEFT JOIN (
                SELECT post_id, COUNT(*) as like_count 
                FROM forum_likes 
                GROUP BY post_id
            ) l ON fp.id = l.post_id
            LEFT JOIN (
                SELECT post_id, COUNT(*) as comment_count 
                FROM forum_comments 
                GROUP BY post_id
            ) c ON fp.id = c.post_id
            ORDER BY fp.created_at DESC 
            LIMIT ? OFFSET ?
        ");
        
        $stmt->execute([$limit, $offset]);
        $posts = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'posts' => $posts]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching posts: ' . $e->getMessage()]);
    }
}

function likePost($pdo) {
    try {
        $user_id = $_SESSION['id'] ?? $_SESSION['user_id'] ?? 1;
        $post_id = $_POST['post_id'] ?? 0;
        
        // Check if already liked
        $stmt = $pdo->prepare("SELECT id FROM forum_likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$user_id, $post_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Unlike
            $stmt = $pdo->prepare("DELETE FROM forum_likes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$user_id, $post_id]);
            $liked = false;
        } else {
            // Like
            $stmt = $pdo->prepare("INSERT INTO forum_likes (user_id, post_id, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$user_id, $post_id]);
            $liked = true;
        }
        
        // Get updated like count
        $stmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM forum_likes WHERE post_id = ?");
        $stmt->execute([$post_id]);
        $result = $stmt->fetch();
        
        echo json_encode([
            'success' => true, 
            'liked' => $liked,
            'like_count' => $result['like_count']
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error processing like: ' . $e->getMessage()]);
    }
}

function addComment($pdo) {
    try {
        $user_id = $_SESSION['id'] ?? $_SESSION['user_id'] ?? 1;
        $post_id = $_POST['post_id'] ?? 0;
        $content = trim($_POST['content'] ?? '');
        
        if (empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
            return;
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO forum_comments (user_id, post_id, content, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$user_id, $post_id, $content]);
        
        // Get the created comment with user info
        $comment_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("
            SELECT fc.*, u.first_name, u.last_name,
                   DATE_FORMAT(fc.created_at, '%d/%m/%Y %H:%i') as formatted_date,
                   'Baru saja' as time_ago
            FROM forum_comments fc 
            JOIN users u ON fc.user_id = u.id 
            WHERE fc.id = ?
        ");
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Comment added successfully',
            'comment' => $comment
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error adding comment: ' . $e->getMessage()]);
    }
}

// Get existing posts for initial page load
try {
    $stmt = $pdo->prepare("
        SELECT fp.*, u.first_name, u.last_name, u.email,
               COALESCE(l.like_count, 0) as like_count,
               COALESCE(c.comment_count, 0) as comment_count,
               DATE_FORMAT(fp.created_at, '%d/%m/%Y %H:%i') as formatted_date,
               CASE 
                   WHEN fp.created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN 'Baru saja'
                   WHEN fp.created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) THEN CONCAT(TIMESTAMPDIFF(HOUR, fp.created_at, NOW()), ' jam yang lalu')
                   ELSE CONCAT(TIMESTAMPDIFF(DAY, fp.created_at, NOW()), ' hari yang lalu')
               END as time_ago
        FROM forum_posts fp 
        JOIN users u ON fp.user_id = u.id 
        LEFT JOIN (
            SELECT post_id, COUNT(*) as like_count 
            FROM forum_likes 
            GROUP BY post_id
        ) l ON fp.id = l.post_id
        LEFT JOIN (
            SELECT post_id, COUNT(*) as comment_count 
            FROM forum_comments 
            GROUP BY post_id
        ) c ON fp.id = c.post_id
        ORDER BY fp.created_at DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $initial_posts = $stmt->fetchAll();
} catch (Exception $e) {
    $initial_posts = [];
}

// Set default values untuk menghindari error
$firstName = $_SESSION['first_name'] ?? 'User';
$lastName = $_SESSION['last_name'] ?? '';
$email = $_SESSION['email'] ?? 'user@example.com';
$profilePicture = $_SESSION['profile_picture'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Diskusi - KoncoNdaki</title>
    <meta name="description" content="Forum diskusi komunitas pendaki gunung KoncoNdaki. Berbagi cerita, tips, dan pengalaman pendakian.">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/dashboard-styles.css">
    <link rel="stylesheet" href="styles/diskusi.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-content">
                <!-- Logo -->
                <div class="logo">
                    <img src="images/logo.png" alt="KoncoNdaki Logo">
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-links desktop-nav">
                    <a href="dashboard.php" class="nav-link">Home</a>
                    <a href="info-gunung.php" class="nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="nav-link active">Diskusi</a>
                    <a href="tentang.php" class="nav-link">Tentang</a>
                </div>

                <!-- User Profile -->
                <div class="user-profile desktop-nav">
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn">
                            <div class="profile-avatar">
                                <?php if (!empty($profilePicture)): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Foto Profil" class="profile-img">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <span class="profile-name" id="profileName">
                                <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>
                            </span>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </button>
                        
                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar large">
                                    <?php if (!empty($profilePicture)): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Foto Profil" class="profile-img">
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="profile-info">
                                    <h4 id="menuProfileName">
                                        <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>
                                    </h4>
                                    <p id="menuProfileEmail">
                                        <?php echo htmlspecialchars($email); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="profile-menu-items">
                                <a href="profile.php" class="profile-menu-item">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Profile Saya</span>
                                </a>
                                <a href="chatbox.php" class="profile-menu-item">
                                    <i class="fas fa-comment-alt"></i>
                                    <span>KoncoNdaki Assistant</span>
                                </a>
                                <a href="profile.php" class="profile-menu-item">
                                    <i class="fas fa-ticket-alt"></i>
                                    <span>Tiket Saya</span>
                                </a>
                                <a href="profile.php" class="profile-menu-item">
                                    <i class="fas fa-history"></i>
                                    <span>Riwayat Pemesanan</span>
                                </a>
                                <a href="profile.php" class="profile-menu-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Pengaturan</span>
                                </a>
                                <div class="profile-menu-divider"></div>
                                <a href="auth/logout.php" class="profile-menu-item logout" id="logoutBtn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Keluar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars" id="menu-icon"></i>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="mobile-nav" id="mobile-nav">
                <div class="mobile-nav-content">
                    <!-- Mobile Profile Header -->
                    <div class="mobile-profile-header">
                        <div class="profile-avatar">
                            <?php if (!empty($profilePicture)): ?>
                                <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Foto Profil" class="profile-img">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info">
                            <h4 id="mobileProfileName">
                                <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>
                            </h4>
                            <p id="mobileProfileEmail">
                                <?php echo htmlspecialchars($email); ?>
                            </p>
                        </div>
                    </div>
                    
                    <a href="dashboard.php" class="mobile-nav-link">Home</a>
                    <a href="info-gunung.php" class="mobile-nav-link">Info Gunung</a>
                    <a href="cara-pemesanan.php" class="mobile-nav-link">Cara Pemesanan</a>
                    <a href="diskusi.php" class="mobile-nav-link active">Diskusi</a>
                    <a href="tentang.php" class="mobile-nav-link">Tentang</a>
                    
                    <div class="mobile-profile-menu">
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-user-circle"></i>
                            Profile Saya
                        </a>
                        <a href="chatbox.php" class="profile-menu-item">
                            <i class="fas fa-comment-alt"></i>
                            <span>KoncoNdaki Assistant</span>
                        </a>
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-ticket-alt"></i>
                            Tiket Saya
                        </a>
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-history"></i>
                            Riwayat Pemesanan
                        </a>
                        <a href="profile.php" class="mobile-nav-link">
                            <i class="fas fa-cog"></i>
                            Pengaturan
                        </a>
                        <a href="auth/logout.php" class="mobile-nav-link logout" id="mobileLogoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <h1><i class="fas fa-comments"></i> Forum Diskusi</h1>
                <p>Berbagi cerita, tips, dan pengalaman pendakian bersama komunitas KoncoNdaki</p>
            </div>
        </div>
    </section>

    <!-- Forum Content -->
    <section class="forum-section">
        <div class="container">
            <div class="forum-layout">
                <!-- Sidebar -->
                <div class="forum-sidebar">
                    <div class="sidebar-card">
                        <h3><i class="fas fa-fire"></i> Topik Populer</h3>
                        <div class="popular-topics">
                            <div class="topic-tag active" data-category="all">
                                <i class="fas fa-globe"></i>
                                Semua Topik
                            </div>
                            <div class="topic-tag" data-category="pengalaman">
                                <i class="fas fa-mountain"></i>
                                Pengalaman Pendakian
                            </div>
                            <div class="topic-tag" data-category="tips">
                                <i class="fas fa-lightbulb"></i>
                                Tips & Trik
                            </div>
                            <div class="topic-tag" data-category="peralatan">
                                <i class="fas fa-backpack"></i>
                                Peralatan
                            </div>
                            <div class="topic-tag" data-category="cuaca">
                                <i class="fas fa-cloud-sun"></i>
                                Info Cuaca
                            </div>
                            <div class="topic-tag" data-category="tanya-jawab">
                                <i class="fas fa-question-circle"></i>
                                Tanya Jawab
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="forum-main">
                    <!-- Create Post Section -->
                    <div class="create-post-card">
                        <div class="create-post-header">
                            <div class="user-avatar">
                                <?php if (!empty($profilePicture)): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Foto Profil" class="profile-img">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <input type="text" placeholder="Bagikan pengalaman pendakian Anda..." id="createPostInput">
                        </div>
                        <div class="create-post-actions">
                            <div class="post-options">
                                <button class="post-option" id="addImageBtn">
                                    <i class="fas fa-image"></i>
                                    Foto
                                </button>
                                <button class="post-option" id="addLocationBtn">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Lokasi
                                </button>
                                <select class="category-select" id="categorySelect">
                                    <option value="pengalaman">Pengalaman Pendakian</option>
                                    <option value="tips">Tips & Trik</option>
                                    <option value="peralatan">Peralatan</option>
                                    <option value="cuaca">Info Cuaca</option>
                                    <option value="tanya-jawab">Tanya Jawab</option>
                                </select>
                            </div>
                            <button class="btn-post" id="submitPostBtn">
                                <i class="fas fa-paper-plane"></i>
                                Posting
                            </button>
                        </div>
                    </div>

                    <!-- Posts Feed -->
                    <div class="posts-feed" id="postsFeed">
                        <?php if (!empty($initial_posts)): ?>
                            <?php foreach ($initial_posts as $post): ?>
                                <?php
                                $categoryInfo = [
                                    'pengalaman' => ['name' => 'Pengalaman Pendakian', 'icon' => 'fa-mountain', 'class' => 'pengalaman'],
                                    'tips' => ['name' => 'Tips & Trik', 'icon' => 'fa-lightbulb', 'class' => 'tips'],
                                    'peralatan' => ['name' => 'Peralatan', 'icon' => 'fa-backpack', 'class' => 'peralatan'],
                                    'cuaca' => ['name' => 'Info Cuaca', 'icon' => 'fa-cloud-sun', 'class' => 'cuaca'],
                                    'tanya-jawab' => ['name' => 'Tanya Jawab', 'icon' => 'fa-question-circle', 'class' => 'tanya-jawab']
                                ];
                                $category = $categoryInfo[$post['category']] ?? $categoryInfo['pengalaman'];
                                $fullName = htmlspecialchars($post['first_name'] . ' ' . $post['last_name']);
                                ?>
                                <div class="post-card" data-category="<?php echo $post['category']; ?>" data-post-id="<?php echo $post['id']; ?>">
                                    <div class="post-header">
                                        <div class="post-author">
                                            <div class="author-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="author-info">
                                                <span class="author-name"><?php echo $fullName; ?></span>
                                                <span class="post-time"><?php echo $post['time_ago']; ?></span>
                                            </div>
                                        </div>
                                        <div class="post-category <?php echo $category['class']; ?>">
                                            <i class="fas <?php echo $category['icon']; ?>"></i>
                                            <?php echo $category['name']; ?>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                        <p><?php echo htmlspecialchars($post['content']); ?></p>
                                    </div>
                                    <div class="post-footer">
                                        <div class="post-stats">
                                            <button class="stat-btn like-btn" data-post="<?php echo $post['id']; ?>">
                                                <i class="far fa-heart"></i>
                                                <span><?php echo $post['like_count']; ?></span>
                                            </button>
                                            <button class="stat-btn comment-btn" data-post="<?php echo $post['id']; ?>">
                                                <i class="far fa-comment"></i>
                                                <span><?php echo $post['comment_count']; ?></span>
                                            </button>
                                            <button class="stat-btn share-btn">
                                                <i class="fas fa-share"></i>
                                                <span>0</span>
                                            </button>
                                        </div>
                                        <button class="btn-read-more" onclick="expandPost(<?php echo $post['id']; ?>)">
                                            Lihat Komentar
                                        </button>
                                    </div>
                                    <div class="comments-section" id="comments-<?php echo $post['id']; ?>" style="display: none;">
                                        <div class="comments-list" id="comments-list-<?php echo $post['id']; ?>">
                                            <!-- Comments will be loaded here -->
                                        </div>
                                        <div class="comment-input">
                                            <div class="user-avatar small">
                                                <?php if (!empty($profilePicture)): ?>
                                                    <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Foto Profil" class="profile-img">
                                                <?php else: ?>
                                                    <i class="fas fa-user"></i>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" placeholder="Tulis komentar..." data-post-id="<?php echo $post['id']; ?>">
                                            <button class="btn-comment" data-post-id="<?php echo $post['id']; ?>">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-posts">
                                <p>Belum ada postingan. Jadilah yang pertama untuk berbagi!</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Load More Button -->
                    <div class="load-more-section">
                        <button class="btn-load-more" id="loadMoreBtn">
                            <i class="fas fa-chevron-down"></i>
                            Muat Lebih Banyak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-mountain"></i>
                        <span>KoncoNdaki</span>
                    </div>
                    <p>Platform terpercaya untuk pemesanan tiket pendakian gunung di seluruh Pulau Jawa.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="cara-pemesanan.php" class="nav-link">Pemesanan Tiket</a></li>
                        <li><a href="info-gunung.php" class="nav-link">Info Gunung</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="cara-pemesanan.php" class="nav-link">Cara Pemesanan</a></li>
                        <li><a href="cara-pemesanan.php" class="nav-link">FAQ</a></li>
                        <li><a href="tentang.php" class="nav-link">Kontak</a></li>
                        <li><a href="diskusi.php" class="nav-link">Diskusi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Tentang</h3>
                    <ul>
                        <li><a href="tentang.php" class="nav-link">Tentang Kami</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 KoncoNdaki. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

<script>
  // Variabel ini akan menampung data pengguna dari session PHP
  const LOGGED_IN_USER = {
    firstName: "<?php echo htmlspecialchars($firstName); ?>",
    lastName: "<?php echo htmlspecialchars($lastName); ?>"
  };
</script>

<script src="scripts/script.js"></script>
<script src="scripts/diskusi.js"></script>
</body>
</html>