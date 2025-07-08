// Dashboard Layanan JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Hanya inisialisasi event listener, tidak lagi memuat notifikasi dummy
    initializeDashboard();
    initializeEventListeners();
});

function initializeDashboard() {
    const hash = window.location.hash.substring(1) || 'all';
    showCategory(hash);
    
    const activeMenuItem = document.querySelector(`[data-category="${hash}"]`);
    if (activeMenuItem) {
        updateActiveMenuItem(activeMenuItem);
    }
}

function initializeEventListeners() {
    // Klik pada kategori
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            showCategory(category);
            updateActiveMenuItem(this);
            window.location.hash = category;
        });
    });

    // Pencarian
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            handleSearch(this.value);
        });
    }

    // Pengurutan (jika ada)
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            handleSort(this.value);
        });
    }

    // Tombol muat lebih banyak (jika ada)
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // Logika untuk muat lebih banyak bisa ditambahkan di sini jika diperlukan
            showNotification('Tidak ada notifikasi tambahan untuk dimuat.', 'info');
        });
    }

    // Modal
    initializeModal();
}

function showCategory(category) {
    const categoryTitles = {
        'all': 'Semua Notifikasi',
        'ojek': 'Tugas Ojek',
        'porter': 'Tugas Porter',
        'guide': 'Tugas Guide',
        'basecamp': 'Notifikasi Basecamp'
    };
    const categoryDescriptions = {
        'all': 'Menampilkan semua notifikasi pesanan dari pengelola',
        'ojek': 'Menampilkan notifikasi untuk layanan ojek',
        'porter': 'Menampilkan notifikasi untuk layanan porter',
        'guide': 'Menampilkan notifikasi untuk layanan guide',
        'basecamp': 'Menampilkan notifikasi untuk layanan basecamp'
    };

    const currentCategoryEl = document.getElementById('currentCategory');
    const categoryDescriptionEl = document.getElementById('categoryDescription');

    if (currentCategoryEl) {
        currentCategoryEl.textContent = categoryTitles[category] || 'Semua Notifikasi';
    }
    if (categoryDescriptionEl) {
        categoryDescriptionEl.textContent = categoryDescriptions[category] || 'Menampilkan semua notifikasi';
    }

    filterNotifications(category);
}

function updateActiveMenuItem(clickedItem) {
    const menuItems = document.querySelectorAll('.category-item');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });
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
    const notifications = Array.from(grid.querySelectorAll('.notification-card'));

    notifications.sort((a, b) => {
        const timeA = new Date(a.dataset.time).getTime();
        const timeB = new Date(b.dataset.time).getTime();
        
        if (sortType === 'newest') {
            return timeB - timeA;
        } else {
            return timeA - timeB;
        }
    });

    notifications.forEach(notification => grid.appendChild(notification));
}

function filterNotifications(category) {
    const notifications = document.querySelectorAll('.notification-card');
    notifications.forEach(notification => {
        const notificationCategory = notification.dataset.category;
        const isVisible = category === 'all' || notificationCategory === category;
        notification.style.display = isVisible ? 'block' : 'none';
    });
}

function initializeModal() {
    // Fungsi untuk modal jika diperlukan di masa depan
}

function showNotification(message, type = 'info') {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    const iconClass = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'info': 'fas fa-info-circle'
    };
    notification.innerHTML = `<i class="${iconClass[type]}"></i><span>${message}</span>`;
    
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}