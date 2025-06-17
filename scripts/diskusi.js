// Forum Diskusi Script
document.addEventListener('DOMContentLoaded', function() {
    // Initialize forum functionality
    initForumFeatures();
    
    // Load user data for profile
    loadUserData();
    
    // Initialize profile dropdown
    initProfileDropdown();
    
    // Initialize logout handlers
    initLogoutHandlers();
    
    // Initialize mobile menu
    initMobileMenu();
});

// Initialize forum features
function initForumFeatures() {
    // Initialize category filter
    initCategoryFilter();
    
    // Initialize post creation
    initPostCreation();
    
    // Initialize post interactions
    initPostInteractions();
    
    // Initialize load more functionality
    initLoadMore();
}

// Initialize category filter
function initCategoryFilter() {
    const topicTags = document.querySelectorAll('.topic-tag');
    
    topicTags.forEach(tag => {
        tag.addEventListener('click', function() {
            // Remove active class from all tags
            topicTags.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tag
            this.classList.add('active');
            
            const category = this.getAttribute('data-category');
            filterPosts(category);
        });
    });
}

// Filter posts by category
function filterPosts(category) {
    const posts = document.querySelectorAll('.post-card');
    
    posts.forEach(post => {
        const postCategory = post.getAttribute('data-category');
        
        if (category === 'all' || postCategory === category) {
            post.style.display = 'block';
            post.classList.remove('filtered-out');
            post.classList.add('filtered-in');
        } else {
            post.style.display = 'none';
            post.classList.add('filtered-out');
            post.classList.remove('filtered-in');
        }
    });
}

// Initialize post creation
function initPostCreation() {
    const createPostInput = document.getElementById('createPostInput');
    const submitPostBtn = document.getElementById('submitPostBtn');
    const categorySelect = document.getElementById('categorySelect');
    
    if (createPostInput && submitPostBtn) {
        // Enable/disable submit button based on input
        createPostInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                submitPostBtn.disabled = false;
                submitPostBtn.style.opacity = '1';
            } else {
                submitPostBtn.disabled = true;
                submitPostBtn.style.opacity = '0.5';
            }
        });
        
        // Handle post submission
        submitPostBtn.addEventListener('click', function() {
            const content = createPostInput.value.trim();
            const category = categorySelect.value;
            
            if (content.length > 0) {
                createNewPost(content, category);
                createPostInput.value = '';
                submitPostBtn.disabled = true;
                submitPostBtn.style.opacity = '0.5';
            }
        });
        
        // Handle Enter key
        createPostInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim().length > 0) {
                    submitPostBtn.click();
                }
            }
        });
    }
    
    // Initialize post options
    const addImageBtn = document.getElementById('addImageBtn');
    const addLocationBtn = document.getElementById('addLocationBtn');
    
    if (addImageBtn) {
        addImageBtn.addEventListener('click', function() {
            showNotification('Fitur upload gambar akan segera tersedia!', 'info');
        });
    }
    
    if (addLocationBtn) {
        addLocationBtn.addEventListener('click', function() {
            showNotification('Fitur lokasi akan segera tersedia!', 'info');
        });
    }
}

// Create new post
function createNewPost(content, category) {
    const userData = JSON.parse(localStorage.getItem('userData')) || {
        firstName: 'John',
        lastName: 'Doe'
    };
    
    const fullName = `${userData.firstName} ${userData.lastName}`;
    const postsFeed = document.getElementById('postsFeed');
    const postId = Date.now();
    
    // Category display mapping
    const categoryDisplay = {
        'pengalaman': { name: 'Pengalaman Pendakian', icon: 'fa-mountain', class: 'pengalaman' },
        'tips': { name: 'Tips & Trik', icon: 'fa-lightbulb', class: 'tips' },
        'peralatan': { name: 'Peralatan', icon: 'fa-backpack', class: 'peralatan' },
        'cuaca': { name: 'Info Cuaca', icon: 'fa-cloud-sun', class: 'cuaca' },
        'tanya-jawab': { name: 'Tanya Jawab', icon: 'fa-question-circle', class: 'tanya-jawab' }
    };
    
    const categoryInfo = categoryDisplay[category];
    
    const newPost = document.createElement('div');
    newPost.className = 'post-card new';
    newPost.setAttribute('data-category', category);
    
    newPost.innerHTML = `
        <div class="post-header">
            <div class="post-author">
                <div class="author-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="author-info">
                    <span class="author-name">${fullName}</span>
                    <span class="post-time">Baru saja</span>
                </div>
            </div>
            <div class="post-category ${categoryInfo.class}">
                <i class="fas ${categoryInfo.icon}"></i>
                ${categoryInfo.name}
            </div>
        </div>
        <div class="post-content">
            <p>${content}</p>
        </div>
        <div class="post-footer">
            <div class="post-stats">
                <button class="stat-btn like-btn" data-post="${postId}">
                    <i class="far fa-heart"></i>
                    <span>0</span>
                </button>
                <button class="stat-btn comment-btn" data-post="${postId}">
                    <i class="far fa-comment"></i>
                    <span>0</span>
                </button>
                <button class="stat-btn share-btn">
                    <i class="fas fa-share"></i>
                    <span>0</span>
                </button>
            </div>
            <button class="btn-read-more" onclick="expandPost(${postId})">
                Lihat Komentar
            </button>
        </div>
        <div class="comments-section" id="comments-${postId}" style="display: none;">
            <div class="comment-input">
                <div class="user-avatar small">
                    <i class="fas fa-user"></i>
                </div>
                <input type="text" placeholder="Tulis komentar...">
                <button class="btn-comment">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    `;
    
    // Insert at the beginning of posts feed
    postsFeed.insertBefore(newPost, postsFeed.firstChild);
    
    // Initialize interactions for the new post
    initPostInteractionsForElement(newPost);
    
    showNotification('Post berhasil dibuat!', 'success');
}

// Initialize post interactions
function initPostInteractions() {
    const posts = document.querySelectorAll('.post-card');
    posts.forEach(post => {
        initPostInteractionsForElement(post);
    });
}

// Initialize interactions for a specific post element
function initPostInteractionsForElement(post) {
    // Like button
    const likeBtn = post.querySelector('.like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            toggleLike(this);
        });
    }
    
    // Comment button
    const commentBtn = post.querySelector('.comment-btn');
    if (commentBtn) {
        commentBtn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post');
            expandPost(postId);
        });
    }
    
    // Share button
    const shareBtn = post.querySelector('.share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            sharePost();
        });
    }
    
    // Comment input
    const commentInput = post.querySelector('.comment-input input');
    const commentSubmitBtn = post.querySelector('.btn-comment');
    
    if (commentInput && commentSubmitBtn) {
        commentSubmitBtn.addEventListener('click', function() {
            const comment = commentInput.value.trim();
            if (comment.length > 0) {
                addComment(post, comment);
                commentInput.value = '';
            }
        });
        
        commentInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                commentSubmitBtn.click();
            }
        });
    }
}

// Toggle like status
function toggleLike(button) {
    const icon = button.querySelector('i');
    const countSpan = button.querySelector('span');
    let count = parseInt(countSpan.textContent);
    
    if (button.classList.contains('liked')) {
        // Unlike
        button.classList.remove('liked');
        icon.classList.remove('fas');
        icon.classList.add('far');
        count--;
        showNotification('Like dihapus', 'info');
    } else {
        // Like
        button.classList.add('liked');
        icon.classList.remove('far');
        icon.classList.add('fas');
        count++;
        showNotification('Post disukai!', 'success');
    }
    
    countSpan.textContent = count;
}

// Expand post to show comments
function expandPost(postId) {
    const commentsSection = document.getElementById(`comments-${postId}`);
    const readMoreBtn = document.querySelector(`[onclick="expandPost(${postId})"]`);
    
    if (commentsSection) {
        if (commentsSection.style.display === 'none') {
            commentsSection.style.display = 'block';
            if (readMoreBtn) {
                readMoreBtn.textContent = 'Sembunyikan Komentar';
            }
        } else {
            commentsSection.style.display = 'none';
            if (readMoreBtn) {
                readMoreBtn.textContent = 'Lihat Komentar';
            }
        }
    }
}

// Add comment to post
function addComment(post, commentText) {
    const userData = JSON.parse(localStorage.getItem('userData')) || {
        firstName: 'John',
        lastName: 'Doe'
    };
    
    const fullName = `${userData.firstName} ${userData.lastName}`;
    const commentsSection = post.querySelector('.comments-section');
    const commentInput = commentsSection.querySelector('.comment-input');
    
    const newComment = document.createElement('div');
    newComment.className = 'comment';
    
    newComment.innerHTML = `
        <div class="comment-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="comment-content">
            <span class="comment-author">${fullName}</span>
            <p>${commentText}</p>
            <span class="comment-time">Baru saja</span>
        </div>
    `;
    
    // Insert before comment input
    commentsSection.insertBefore(newComment, commentInput);
    
    // Update comment count
    const commentBtn = post.querySelector('.comment-btn span');
    if (commentBtn) {
        const currentCount = parseInt(commentBtn.textContent);
        commentBtn.textContent = currentCount + 1;
    }
    
    showNotification('Komentar berhasil ditambahkan!', 'success');
}

// Share post
function sharePost() {
    if (navigator.share) {
        navigator.share({
            title: 'KoncoNdaki Forum',
            text: 'Lihat diskusi menarik di forum KoncoNdaki!',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Link berhasil disalin!', 'success');
        }).catch(() => {
            showNotification('Gagal menyalin link', 'error');
        });
    }
}

// Initialize load more functionality
function initLoadMore() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            loadMorePosts();
        });
    }
}

// Load more posts
function loadMorePosts() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const postsFeed = document.getElementById('postsFeed');
    
    // Show loading state
    loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
    loadMoreBtn.disabled = true;
    
    // Simulate loading delay
    setTimeout(() => {
        // Add more sample posts
        const morePosts = [
            {
                author: 'Rini Sari',
                time: '3 hari yang lalu',
                category: 'peralatan',
                title: 'Review Sepatu Hiking Terbaru',
                content: 'Baru saja mencoba sepatu hiking merek XYZ untuk pendakian Gunung Gede. Performanya cukup bagus untuk harga segitu...'
            },
            {
                author: 'Agus Wijaya',
                time: '4 hari yang lalu',
                category: 'cuaca',
                title: 'Update Cuaca Gunung Semeru',
                content: 'Info terbaru dari BMKG, cuaca di Gunung Semeru minggu ini cukup cerah. Cocok untuk pendakian...'
            }
        ];
        
        morePosts.forEach(postData => {
            const postId = Date.now() + Math.random();
            const categoryInfo = getCategoryInfo(postData.category);
            
            const newPost = document.createElement('div');
            newPost.className = 'post-card';
            newPost.setAttribute('data-category', postData.category);
            
            newPost.innerHTML = `
                <div class="post-header">
                    <div class="post-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <span class="author-name">${postData.author}</span>
                            <span class="post-time">${postData.time}</span>
                        </div>
                    </div>
                    <div class="post-category ${categoryInfo.class}">
                        <i class="fas ${categoryInfo.icon}"></i>
                        ${categoryInfo.name}
                    </div>
                </div>
                <div class="post-content">
                    <h3>${postData.title}</h3>
                    <p>${postData.content}</p>
                </div>
                <div class="post-footer">
                    <div class="post-stats">
                        <button class="stat-btn like-btn" data-post="${postId}">
                            <i class="far fa-heart"></i>
                            <span>${Math.floor(Math.random() * 20)}</span>
                        </button>
                        <button class="stat-btn comment-btn" data-post="${postId}">
                            <i class="far fa-comment"></i>
                            <span>${Math.floor(Math.random() * 10)}</span>
                        </button>
                        <button class="stat-btn share-btn">
                            <i class="fas fa-share"></i>
                            <span>${Math.floor(Math.random() * 5)}</span>
                        </button>
                    </div>
                    <button class="btn-read-more" onclick="expandPost(${postId})">
                        Lihat Komentar
                    </button>
                </div>
                <div class="comments-section" id="comments-${postId}" style="display: none;">
                    <div class="comment-input">
                        <div class="user-avatar small">
                            <i class="fas fa-user"></i>
                        </div>
                        <input type="text" placeholder="Tulis komentar...">
                        <button class="btn-comment">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            `;
            
            postsFeed.appendChild(newPost);
            initPostInteractionsForElement(newPost);
        });
        
        // Reset button
        loadMoreBtn.innerHTML = '<i class="fas fa-chevron-down"></i> Muat Lebih Banyak';
        loadMoreBtn.disabled = false;
        
        showNotification('Post baru berhasil dimuat!', 'success');
    }, 1500);
}

// Get category info
function getCategoryInfo(category) {
    const categoryDisplay = {
        'pengalaman': { name: 'Pengalaman Pendakian', icon: 'fa-mountain', class: 'pengalaman' },
        'tips': { name: 'Tips & Trik', icon: 'fa-lightbulb', class: 'tips' },
        'peralatan': { name: 'Peralatan', icon: 'fa-backpack', class: 'peralatan' },
        'cuaca': { name: 'Info Cuaca', icon: 'fa-cloud-sun', class: 'cuaca' },
        'tanya-jawab': { name: 'Tanya Jawab', icon: 'fa-question-circle', class: 'tanya-jawab' }
    };
    
    return categoryDisplay[category] || categoryDisplay['pengalaman'];
}

// Load user data from localStorage
function loadUserData() {
    // Get user data from localStorage (set during registration/login)
    const userData = JSON.parse(localStorage.getItem("userData")) || {
        firstName: "John",
        lastName: "Doe",
        email: "john.doe@email.com",
        phone: "+62 812-3456-7890",
    }

    // Update profile name displays
    const fullName = `${userData.firstName} ${userData.lastName}`
    const firstName = userData.firstName

    // Update all profile name elements
    updateElementText("profileName", fullName)
    updateElementText("menuProfileName", fullName)
    updateElementText("menuProfileEmail", userData.email)
    updateElementText("mobileProfileName", fullName)
    updateElementText("mobileProfileEmail", userData.email)
    updateElementText("welcomeName", firstName)
}

// Update element text safely
function updateElementText(elementId, text) {
    const element = document.getElementById(elementId)
    if (element) {
        element.textContent = text
    }
}

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

        // Redirect to home page after delay
        setTimeout(() => {
            window.location.href = "index.html"
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