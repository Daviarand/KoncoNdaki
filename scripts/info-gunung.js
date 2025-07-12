// Info Gunung Script
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search and filter functionality
    initSearchFilter();
    
    // Initialize profile dropdown
    initProfileDropdown();
    
    // Initialize logout handlers
    initLogoutHandlers();
    
    // Initialize mobile menu
    initMobileMenu();
});

// Search and Filter functionality
function initSearchFilter() {
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const mountainCards = document.querySelectorAll('.mountain-detail-card');
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterMountains(searchTerm, getActiveFilter());
        });
    }
    
    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            
            filterMountains(searchTerm, filterValue);
        });
    });
}

// Get active filter
function getActiveFilter() {
    const activeButton = document.querySelector('.filter-btn.active');
    return activeButton ? activeButton.getAttribute('data-filter') : 'all';
}

// Filter mountains based on search and category
function filterMountains(searchTerm, filterValue) {
    const mountainCards = document.querySelectorAll('.mountain-detail-card');
    
    mountainCards.forEach(card => {
        const mountainName = card.getAttribute('data-name').toLowerCase();
        const mountainRegion = card.getAttribute('data-region');
        
        const matchesSearch = mountainName.includes(searchTerm);
        const matchesFilter = filterValue === 'all' || mountainRegion === filterValue;
        
        if (matchesSearch && matchesFilter) {
            card.style.display = 'block';
            card.classList.remove('hidden');
            card.classList.add('visible');
        } else {
            card.style.display = 'none';
            card.classList.add('hidden');
            card.classList.remove('visible');
        }
    });
}

// Mountain data for modal
const mountainData = {
    bromo: {
        name: 'Gunung Bromo',
        location: 'Jawa Timur',
        elevation: '2.329 mdpl',
        difficulty: 'Pemula',
        duration: '2-3 jam',
        quota: '500 kuota',
        price: 'Rp 35.000',
        image: 'https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=600&h=300&fit=crop',
        description: 'Gunung Bromo adalah gunung berapi aktif yang terletak di Jawa Timur, Indonesia. Gunung ini terkenal dengan pemandangan sunrise yang spektakuler dan lautan pasir yang menakjubkan.',
        highlights: [
            'Pemandangan sunrise terbaik di Jawa Timur',
            'Lautan pasir yang unik dan menakjubkan',
            'Kawah aktif yang masih mengeluarkan asap',
            'Akses yang relatif mudah untuk pemula'
        ],
        tips: [
            'Berangkat dini hari untuk melihat sunrise',
            'Bawa jaket tebal karena suhu sangat dingin',
            'Gunakan masker untuk melindungi dari debu vulkanik',
            'Sewa jeep untuk akses ke viewpoint'
        ],
        facilities: [
            'Area parkir luas',
            'Warung makan dan minuman',
            'Toilet umum',
            'Penyewaan kuda',
            'Guide lokal tersedia'
        ]
    },
    merapi: {
        name: 'Gunung Merapi',
        location: 'Jawa Tengah',
        elevation: '2.930 mdpl',
        difficulty: 'Menengah',
        duration: '4-6 jam',
        quota: '250 kuota',
        price: 'Rp 25.000',
        image: 'https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=600&h=300&fit=crop',
        description: 'Gunung Merapi adalah gunung berapi paling aktif di Indonesia yang terletak di perbatasan Jawa Tengah dan Yogyakarta. Dari puncaknya, pendaki dapat melihat pemandangan kota Yogyakarta yang memukau.',
        highlights: [
            'Gunung berapi paling aktif di Indonesia',
            'Pemandangan kota Yogyakarta dari puncak',
            'Jalur pendakian yang menantang',
            'Kawah aktif dengan aktivitas vulkanik'
        ],
        tips: [
            'Cek status aktivitas vulkanik sebelum mendaki',
            'Gunakan sepatu hiking yang baik',
            'Bawa air yang cukup',
            'Ikuti instruksi guide dengan ketat'
        ],
        facilities: [
            'Pos pendakian resmi',
            'Shelter di beberapa titik',
            'Guide wajib untuk keamanan',
            'Monitoring aktivitas vulkanik'
        ]
    },
    semeru: {
        name: 'Gunung Semeru',
        location: 'Jawa Timur',
        elevation: '3.676 mdpl',
        difficulty: 'Lanjutan',
        duration: '2-3 hari',
        quota: '600 kuota',
        price: 'Rp 45.000',
        image: 'https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=600&h=300&fit=crop',
        description: 'Gunung Semeru adalah puncak tertinggi di Pulau Jawa dengan ketinggian 3.676 mdpl. Gunung ini menawarkan pemandangan yang sangat menakjubkan dan tantangan pendakian yang menantang.',
        highlights: [
            'Puncak tertinggi di Pulau Jawa',
            'Pemandangan spektakuler dari Mahameru',
            'Danau Ranu Kumbolo yang indah',
            'Savana Oro-oro Ombo yang luas'
        ],
        tips: [
            'Persiapan fisik yang matang sangat penting',
            'Bawa sleeping bag tahan suhu dingin',
            'Siapkan logistik untuk 2-3 hari',
            'Waspadai cuaca buruk dan kabut tebal'
        ],
        facilities: [
            'Basecamp Ranu Pani',
            'Shelter di Ranu Kumbolo',
            'Pos pemeriksaan kesehatan',
            'Batas kuota pendaki per hari'
        ]
    },
    gede: {
        name: 'Gunung Gede',
        location: 'Jawa Barat',
        elevation: '2.958 mdpl',
        difficulty: 'Menengah',
        duration: '5-7 jam',
        quota: '800 kuota',
        price: 'Rp 30.000',
        image: 'https://images.pexels.com/photos/1366919/pexels-photo-1366919.jpeg?auto=compress&cs=tinysrgb&w=600&h=300&fit=crop',
        description: 'Gunung Gede terletak di Jawa Barat dengan keanekaragaman flora dan fauna yang tinggi. Gunung ini terkenal dengan air terjun dan danau kawahnya yang indah.',
        highlights: [
            'Keanekaragaman flora dan fauna',
            'Air terjun Cibeureum yang indah',
            'Danau kawah di puncak',
            'Jalur pendakian yang bervariasi'
        ],
        tips: [
            'Bawa jas hujan karena sering hujan',
            'Gunakan sepatu anti slip',
            'Jaga kebersihan untuk melindungi ekosistem',
            'Ikuti jalur yang telah ditentukan'
        ],
        facilities: [
            'Taman Nasional Gede Pangrango',
            'Pusat informasi pengunjung',
            'Jalur interpretasi alam',
            'Camping ground tersedia'
        ]
    },
    papandayan: {
        name: 'Gunung Papandayan',
        location: 'Jawa Barat',
        elevation: '2.665 mdpl',
        difficulty: 'Pemula',
        duration: '3-4 jam',
        quota: '400 kuota',
        price: 'Rp 20.000',
        image: 'https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=600&h=300&fit=crop',
        description: 'Gunung Papandayan memiliki kawah aktif yang mengeluarkan gas belerang dan pemandangan savana yang indah. Cocok untuk pendaki pemula.',
        highlights: [
            'Kawah aktif dengan gas belerang',
            'Savana yang luas dan indah',
            'Akses yang mudah untuk pemula',
            'Pemandangan alam yang beragam'
        ],
        tips: [
            'Hindari area kawah saat cuaca buruk',
            'Bawa masker untuk melindungi dari gas belerang',
            'Waktu terbaik pagi hingga sore',
            'Jaga jarak aman dari kawah aktif'
        ],
        facilities: [
            'Area parkir yang luas',
            'Warung di area basecamp',
            'Toilet dan mushola',
            'Jalur yang sudah tertata baik'
        ]
    },
    merbabu: {
        name: 'Gunung Merbabu',
        location: 'Jawa Tengah',
        elevation: '3.145 mdpl',
        difficulty: 'Menengah',
        duration: '6-8 jam',
        quota: '300 kuota',
        price: 'Rp 28.000',
        image: 'https://images.pexels.com/photos/1671325/pexels-photo-1671325.jpeg?auto=compress&cs=tinysrgb&w=600&h=300&fit=crop',
        description: 'Gunung Merbabu memiliki padang savana yang luas dan pemandangan Gunung Merapi yang spektakuler. Populer di kalangan pendaki karena keindahan alamnya.',
        highlights: [
            'Padang savana yang luas',
            'Pemandangan Gunung Merapi',
            'Sunrise dan sunset yang indah',
            'Jalur pendakian yang menantang'
        ],
        tips: [
            'Bawa air yang cukup karena sumber air terbatas',
            'Siapkan pakaian hangat untuk malam',
            'Camping di savana sangat direkomendasikan',
            'Waspadai perubahan cuaca yang cepat'
        ],
        facilities: [
            'Beberapa jalur pendakian',
            'Area camping di savana',
            'Pos pendakian di basecamp',
            'Guide lokal tersedia'
        ]
    }
};

// Open mountain detail modal
function openMountainModal(mountainId) {
    const modal = document.getElementById('mountainModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    
    const mountain = mountainData[mountainId];
    
    if (mountain) {
        modalTitle.textContent = mountain.name;
        
        modalBody.innerHTML = `
            <div class="modal-mountain-detail">
                <div class="modal-image">
                    <img src="${mountain.image}" alt="${mountain.name}">
                    <div class="modal-stats">
                        <div class="modal-stat">
                            <i class="fas fa-mountain"></i>
                            <span>${mountain.elevation}</span>
                        </div>
                        <div class="modal-stat">
                            <i class="fas fa-clock"></i>
                            <span>${mountain.duration}</span>
                        </div>
                        <div class="modal-stat">
                            <i class="fas fa-users"></i>
                            <span>${mountain.quota}</span>
                        </div>
                        <div class="modal-stat">
                            <i class="fas fa-signal"></i>
                            <span>${mountain.difficulty}</span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-info">
                    <div class="modal-section">
                        <h3><i class="fas fa-info-circle"></i> Deskripsi</h3>
                        <p>${mountain.description}</p>
                    </div>
                    
                    <div class="modal-section">
                        <h3><i class="fas fa-star"></i> Highlights</h3>
                        <ul>
                            ${mountain.highlights.map(highlight => `<li>${highlight}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="modal-section">
                        <h3><i class="fas fa-lightbulb"></i> Tips Pendakian</h3>
                        <ul>
                            ${mountain.tips.map(tip => `<li>${tip}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="modal-section">
                        <h3><i class="fas fa-home"></i> Fasilitas</h3>
                        <ul>
                            ${mountain.facilities.map(facility => `<li>${facility}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="modal-actions">
                        <div class="modal-price">
                            <span class="price-label">Harga tiket mulai dari</span>
                            <span class="price-amount">${mountain.price}</span>
                        </div>
                        <button class="btn-book-modal" onclick="bookMountain('${mountainId}')">
                            <i class="fas fa-ticket-alt"></i>
                            Pesan Tiket
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

// Close mountain detail modal
function closeMountainModal() {
    const modal = document.getElementById('mountainModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Book mountain ticket
function bookMountain(mountainId) {
    // Redirect ke halaman form pemesanan dengan parameter gunung
    if (mountainId) {
        window.location.href = `form-pemesanan.php?gunung=${mountainId}`;
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('mountainModal');
    if (e.target === modal) {
        closeMountainModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMountainModal();
    }
});


// Initialize profile dropdown
function initProfileDropdown() {
    const profileBtn = document.getElementById("profileBtn")
    const profileMenu = document.getElementById("profileMenu")
    const profileDropdown = document.querySelector(".profile-dropdown")

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener("click", (e) => {
            e.stopPropagation()
            toggleProfileMenu()
        })

        // Close dropdown when clicking outside
        document.addEventListener("click", (e) => {
            if (!profileDropdown.contains(e.target)) {
                closeProfileMenu()
            }
        })

        // Prevent dropdown from closing when clicking inside menu
        profileMenu.addEventListener("click", (e) => {
            e.stopPropagation()
        })
    }
}

// Toggle profile menu
function toggleProfileMenu() {
    const profileMenu = document.getElementById("profileMenu")
    const profileDropdown = document.querySelector(".profile-dropdown")

    if (profileMenu.classList.contains("active")) {
        closeProfileMenu()
    } else {
        openProfileMenu()
    }
}

// Open profile menu
function openProfileMenu() {
    const profileMenu = document.getElementById("profileMenu")
    const profileDropdown = document.querySelector(".profile-dropdown")

    profileMenu.classList.add("active")
    profileDropdown.classList.add("active")
}

// Close profile menu
function closeProfileMenu() {
    const profileMenu = document.getElementById("profileMenu")
    const profileDropdown = document.querySelector(".profile-dropdown")

    profileMenu.classList.remove("active")
    profileDropdown.classList.remove("active")
}

// Initialize logout handlers
function initLogoutHandlers() {
    const logoutBtn = document.getElementById("logoutBtn")
    const mobileLogoutBtn = document.getElementById("mobileLogoutBtn")

    if (logoutBtn) {
        logoutBtn.addEventListener("click", handleLogout)
    }

    if (mobileLogoutBtn) {
        mobileLogoutBtn.addEventListener("click", handleLogout)
    }
}

// Handle logout
function handleLogout(e) {
    e.preventDefault()

    // Show confirmation dialog
    if (confirm("Apakah Anda yakin ingin keluar?")) {
        // Clear user data
        localStorage.removeItem("userData")
        localStorage.removeItem("userLoggedIn")

        // Show logout message
        showNotification("Anda telah berhasil keluar", "success")

        // Redirect to login page after delay
        setTimeout(() => {
            window.location.href = "login.php"
        }, 1500)
    }
}

// Initialize mobile menu
function initMobileMenu() {
    const menuBtn = document.querySelector(".mobile-menu-btn")
    const mobileNav = document.getElementById("mobile-nav")
    const menuIcon = document.getElementById("menu-icon")

    if (menuBtn && mobileNav && menuIcon) {
        menuBtn.addEventListener("click", () => {
            mobileNav.classList.toggle("active")

            // Toggle icon between hamburger and X
            if (mobileNav.classList.contains("active")) {
                menuIcon.classList.remove("fa-bars")
                menuIcon.classList.add("fa-times")
            } else {
                menuIcon.classList.remove("fa-times")
                menuIcon.classList.add("fa-bars")
            }
        })

        // Close mobile menu when clicking on a link
        const mobileNavLinks = document.querySelectorAll(".mobile-nav-link")
        mobileNavLinks.forEach((link) => {
            link.addEventListener("click", function () {
                // Don't close menu for logout link (handled separately)
                if (!this.classList.contains("logout")) {
                    mobileNav.classList.remove("active")
                    menuIcon.classList.remove("fa-times")
                    menuIcon.classList.add("fa-bars")
                }
            })
        })

        // Close mobile menu when clicking outside
        document.addEventListener("click", (event) => {
            const isClickInsideNav = mobileNav.contains(event.target)
            const isClickOnMenuBtn = menuBtn.contains(event.target)

            if (!isClickInsideNav && !isClickOnMenuBtn && mobileNav.classList.contains("active")) {
                mobileNav.classList.remove("active")
                menuIcon.classList.remove("fa-times")
                menuIcon.classList.add("fa-bars")
            }
        })
    }
}

// Show notification function
function showNotification(message, type = "info") {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll(".notification")
    existingNotifications.forEach((notification) => notification.remove())

    // Create notification element
    const notification = document.createElement("div")
    notification.className = `notification notification-${type}`

    const icon = type === "success" ? "fa-check-circle" : type === "error" ? "fa-exclamation-circle" : "fa-info-circle"

    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        padding: 16px;
        z-index: 10000;
        max-width: 400px;
        border-left: 4px solid ${type === "success" ? "#16a34a" : type === "error" ? "#ef4444" : "#3b82f6"};
        animation: slideIn 0.3s ease;
    `

    // Add animation styles
    const style = document.createElement("style")
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1f2937;
        }
        
        .notification-content i {
            color: ${type === "success" ? "#16a34a" : type === "error" ? "#ef4444" : "#3b82f6"};
        }
        
        .notification-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
        }
        
        .notification-close:hover {
            background: #f3f4f6;
            color: #6b7280;
        }
    `

    document.head.appendChild(style)
    document.body.appendChild(notification)

    // Close button functionality
    const closeButton = notification.querySelector(".notification-close")
    closeButton.addEventListener("click", () => {
        notification.remove()
        style.remove()
    })

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove()
            style.remove()
        }
    }, 5000)
}

// Add modal styles dynamically
const modalStyles = document.createElement('style');
modalStyles.textContent = `
    .modal-mountain-detail {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .modal-image {
        position: relative;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .modal-image img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
    
    .modal-stats {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        right: 1rem;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }
    
    .modal-stat {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 0.5rem;
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        backdrop-filter: blur(10px);
    }
    
    .modal-stat i {
        color: #16a34a;
        margin-bottom: 0.25rem;
    }
    
    .modal-stat span {
        font-size: 0.75rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .modal-section {
        margin-bottom: 1.5rem;
    }
    
    .modal-section h3 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }
    
    .modal-section h3 i {
        color: #16a34a;
    }
    
    .modal-section p {
        color: #4b5563;
        line-height: 1.6;
    }
    
    .modal-section ul {
        color: #4b5563;
        line-height: 1.6;
        margin-left: 1.5rem;
    }
    
    .modal-section li {
        margin-bottom: 0.5rem;
    }
    
    .modal-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .modal-price {
        display: flex;
        flex-direction: column;
    }
    
    .price-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    
    .price-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #16a34a;
    }
    
    .btn-book-modal {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #16a34a;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
        font-size: 1rem;
    }
    
    .btn-book-modal:hover {
        background-color: #15803d;
    }
    
    @media (max-width: 768px) {
        .modal-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .modal-actions {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .btn-book-modal {
            justify-content: center;
        }
    }
`;

document.head.appendChild(modalStyles);