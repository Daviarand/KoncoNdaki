// Dashboard Layanan JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard
    initializeDashboard();
    initializeEventListeners();
    loadNotifications();
});

function initializeDashboard() {
    // Set active category based on hash or default to all
    const hash = window.location.hash.substring(1) || 'all';
    showCategory(hash);
    
    // Update active menu item
    const activeMenuItem = document.querySelector(`[data-category="${hash}"]`);
    if (activeMenuItem) {
        updateActiveMenuItem(activeMenuItem);
    }
}

function initializeEventListeners() {
    // Category item clicks
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            showCategory(category);
            updateActiveMenuItem(this);
            
            // Update URL hash
            window.location.hash = category;
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            handleSearch(this.value);
        });
    }

    // Sort functionality
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            handleSort(this.value);
        });
    }

    // Load more button
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            loadMoreNotifications();
        });
    }

    // Modal functionality
    initializeModal();
}

function showCategory(category) {
    // Update category title and description
    const categoryTitles = {
        'all': 'Semua Layanan',
        'ojek': 'Pemesanan Ojek',
        'porter': 'Pemesanan Porter',
        'guide': 'Pemesanan Guide',
        'basecamp': 'Pemesanan Basecamp'
    };

    const categoryDescriptions = {
        'all': 'Menampilkan semua notifikasi pesanan dari berbagai layanan',
        'ojek': 'Menampilkan pesanan layanan ojek gunung',
        'porter': 'Menampilkan pesanan layanan porter',
        'guide': 'Menampilkan pesanan layanan guide pendakian',
        'basecamp': 'Menampilkan pesanan layanan basecamp'
    };

    const currentCategoryEl = document.getElementById('currentCategory');
    const categoryDescriptionEl = document.getElementById('categoryDescription');

    if (currentCategoryEl) {
        currentCategoryEl.textContent = categoryTitles[category] || 'Semua Layanan';
    }
    if (categoryDescriptionEl) {
        categoryDescriptionEl.textContent = categoryDescriptions[category] || 'Menampilkan semua notifikasi pesanan';
    }

    // Filter notifications
    filterNotifications(category);
}

function updateActiveMenuItem(clickedItem) {
    // Remove active class from all menu items
    const menuItems = document.querySelectorAll('.category-item');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });

    // Add active class to clicked item
    clickedItem.classList.add('active');
}

function handleSearch(query) {
    const notifications = document.querySelectorAll('.notification-card');
    
    notifications.forEach(notification => {
        const text = notification.textContent.toLowerCase();
        const isVisible = text.includes(query.toLowerCase());
        notification.style.display = isVisible ? 'block' : 'none';
    });
}

function handleSort(sortType) {
    const grid = document.getElementById('notificationsGrid');
    const notifications = Array.from(grid.children);

    notifications.sort((a, b) => {
        switch(sortType) {
            case 'newest':
                return new Date(b.dataset.time) - new Date(a.dataset.time);
            case 'oldest':
                return new Date(a.dataset.time) - new Date(b.dataset.time);
            case 'priority':
                return getPriorityValue(b.dataset.priority) - getPriorityValue(a.dataset.priority);
            case 'amount':
                return parseFloat(b.dataset.amount) - parseFloat(a.dataset.amount);
            default:
                return 0;
        }
    });

    // Re-append sorted notifications
    notifications.forEach(notification => {
        grid.appendChild(notification);
    });
}

function getPriorityValue(priority) {
    const priorities = { 'urgent': 3, 'high': 2, 'medium': 1, 'low': 0 };
    return priorities[priority] || 0;
}

function filterNotifications(category) {
    const notifications = document.querySelectorAll('.notification-card');
    
    notifications.forEach(notification => {
        const notificationCategory = notification.dataset.category;
        const isVisible = category === 'all' || notificationCategory === category;
        notification.style.display = isVisible ? 'block' : 'none';
    });
}

function loadNotifications() {
    const grid = document.getElementById('notificationsGrid');
    if (!grid) return;

    // Sample notification data
    const notifications = [
        {
            id: 1,
            category: 'ojek',
            type: 'new',
            title: 'Pesanan Ojek Baru - Gunung Merapi',
            description: 'Permintaan ojek dari Basecamp Selo ke Pos 1 untuk 3 orang pendaki.',
            customer: 'Ahmad Rizki',
            phone: '081234567890',
            amount: 150000,
            location: 'Basecamp Selo - Pos 1',
            time: '2024-01-15T10:30:00',
            priority: 'high'
        },
        {
            id: 2,
            category: 'porter',
            type: 'confirmed',
            title: 'Pesanan Porter Dikonfirmasi',
            description: 'Porter untuk membawa peralatan camping 2 hari 1 malam.',
            customer: 'Sari Dewi',
            phone: '081234567891',
            amount: 300000,
            location: 'Jalur Babadan',
            time: '2024-01-15T09:15:00',
            priority: 'medium'
        },
        {
            id: 3,
            category: 'guide',
            type: 'ongoing',
            title: 'Guide Sedang Bertugas',
            description: 'Memandu grup 5 orang pendaki menuju puncak Gunung Lawu.',
            customer: 'Budi Santoso',
            phone: '081234567892',
            amount: 500000,
            location: 'Gunung Lawu',
            time: '2024-01-15T06:00:00',
            priority: 'high'
        },
        {
            id: 4,
            category: 'basecamp',
            type: 'completed',
            title: 'Pemesanan Basecamp Selesai',
            description: 'Sewa basecamp untuk 1 malam telah selesai dengan rating 5 bintang.',
            customer: 'Rina Maharani',
            phone: '081234567893',
            amount: 200000,
            location: 'Basecamp Kaliurang',
            time: '2024-01-14T18:00:00',
            priority: 'low'
        },
        {
            id: 5,
            category: 'ojek',
            type: 'urgent',
            title: 'Ojek Darurat Diperlukan',
            description: 'Pendaki memerlukan evakuasi darurat dari Pos 2 ke basecamp.',
            customer: 'Emergency Call',
            phone: '081234567894',
            amount: 250000,
            location: 'Pos 2 - Basecamp',
            time: '2024-01-15T11:45:00',
            priority: 'urgent'
        },
        {
            id: 6,
            category: 'porter',
            type: 'new',
            title: 'Porter untuk Ekspedisi 3 Hari',
            description: 'Membutuhkan porter berpengalaman untuk ekspedisi 3 hari 2 malam.',
            customer: 'Expedition Team',
            phone: '081234567895',
            amount: 750000,
            location: 'Jalur Kinahrejo',
            time: '2024-01-15T08:20:00',
            priority: 'high'
        }
    ];

    // Clear existing notifications
    grid.innerHTML = '';

    // Create notification cards
    notifications.forEach(notification => {
        const card = createNotificationCard(notification);
        grid.appendChild(card);
    });
}

function createNotificationCard(notification) {
    const card = document.createElement('div');
    card.className = 'notification-card';
    card.dataset.category = notification.category;
    card.dataset.time = notification.time;
    card.dataset.priority = notification.priority;
    card.dataset.amount = notification.amount;

    const serviceIcons = {
        'ojek': 'fas fa-motorcycle',
        'porter': 'fas fa-hiking',
        'guide': 'fas fa-user-tie',
        'basecamp': 'fas fa-campground'
    };

    card.innerHTML = `
        <div class="notification-header">
            <span class="notification-type type-${notification.type}">${notification.type}</span>
            <h3 class="notification-title">${notification.title}</h3>
            <div class="notification-time">
                <i class="fas fa-clock"></i>
                ${formatTime(notification.time)}
            </div>
        </div>
        <div class="notification-body">
            <div class="service-icon ${notification.category}">
                <i class="${serviceIcons[notification.category]}"></i>
            </div>
            <p class="notification-description">${notification.description}</p>
            
            <div class="customer-info">
                <h4><i class="fas fa-user"></i> Informasi Pelanggan</h4>
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value">${notification.customer}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telepon:</span>
                    <a href="tel:${notification.phone}" class="info-value phone-number">${notification.phone}</a>
                </div>
                <div class="info-row">
                    <span class="info-label">Nilai Pesanan:</span>
                    <span class="info-value">${formatCurrency(notification.amount)}</span>
                </div>
            </div>
            
            <div class="notification-meta">
                <div class="notification-location">
                    <i class="fas fa-map-marker-alt"></i>
                    ${notification.location}
                </div>
                <button class="action-button" onclick="openNotificationModal(${notification.id})">
                    <i class="fas fa-eye"></i>
                    Lihat Detail
                </button>
            </div>
        </div>
    `;

    // Add click event to open modal
    card.addEventListener('click', () => openNotificationModal(notification.id));

    return card;
}

function formatTime(timeString) {
    const date = new Date(timeString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffMins < 1) return 'Baru saja';
    if (diffMins < 60) return `${diffMins} menit yang lalu`;
    if (diffHours < 24) return `${diffHours} jam yang lalu`;
    if (diffDays < 7) return `${diffDays} hari yang lalu`;
    
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function loadMoreNotifications() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
        
        // Simulate loading delay
        setTimeout(() => {
            loadMoreBtn.innerHTML = '<i class="fas fa-plus"></i> Muat Lebih Banyak';
            showNotification('Tidak ada notifikasi tambahan untuk dimuat', 'info');
        }, 1500);
    }
}

function initializeModal() {
    const modal = document.getElementById('notificationModal');
    const modalClose = document.getElementById('modalClose');
    const modalCancel = document.getElementById('modalCancel');

    if (modalClose) {
        modalClose.addEventListener('click', closeNotificationModal);
    }
    
    if (modalCancel) {
        modalCancel.addEventListener('click', closeNotificationModal);
    }

    // Close modal when clicking outside
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeNotificationModal();
            }
        });
    }
}

function openNotificationModal(notificationId) {
    const modal = document.getElementById('notificationModal');
    const modalBody = document.getElementById('modalBody');
    const modalAction = document.getElementById('modalAction');

    if (modal && modalBody) {
        // Sample detailed notification data
        modalBody.innerHTML = `
            <div class="customer-info">
                <h4><i class="fas fa-user"></i> Detail Lengkap Pesanan</h4>
                <div class="info-row">
                    <span class="info-label">ID Pesanan:</span>
                    <span class="info-value">#ORD-${notificationId.toString().padStart(6, '0')}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Pesanan:</span>
                    <span class="info-value">15 Januari 2024, 10:30 WIB</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">Menunggu Konfirmasi</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Catatan Khusus:</span>
                    <span class="info-value">Mohon datang tepat waktu, grup sudah menunggu di basecamp.</span>
                </div>
            </div>
        `;

        if (modalAction) {
            modalAction.onclick = () => {
                acceptOrder(notificationId);
                closeNotificationModal();
            };
        }

        modal.classList.add('active');
    }
}

function closeNotificationModal() {
    const modal = document.getElementById('notificationModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function acceptOrder(orderId) {
    showNotification(`Pesanan #ORD-${orderId.toString().padStart(6, '0')} berhasil diterima!`, 'success');
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getNotificationColor(type)};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function getNotificationIcon(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        default: return 'info-circle';
    }
}

function getNotificationColor(type) {
    switch(type) {
        case 'success': return 'linear-gradient(135deg, #16a34a, #15803d)';
        case 'error': return 'linear-gradient(135deg, #ef4444, #dc2626)';
        case 'warning': return 'linear-gradient(135deg, #f59e0b, #d97706)';
        default: return 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
    }
}

// Export functions for global access
window.dashboardLayanan = {
    showCategory,
    updateActiveMenuItem,
    showNotification,
    formatCurrency,
    formatTime,
    openNotificationModal,
    closeNotificationModal
};