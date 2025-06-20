// Dashboard Layanan Script
document.addEventListener("DOMContentLoaded", () => {
    // Initialize dashboard
    initDashboard()
  
    // Load notifications
    loadNotifications()
  
    // Setup event listeners
    setupEventListeners()
  
    // Setup filters
    setupFilters()
  
    // Setup modal
    setupModal()
  })
  
  // Sample notification data
  const notificationsData = [
    {
      id: 1,
      category: "ojek",
      type: "new",
      title: "Pesanan Ojek Baru",
      description: "Permintaan antar jemput dari Basecamp Selo ke Pos 1 Gunung Merapi",
      customer: {
        name: "Ahmad Ridwan",
        phone: "0812-3456-7890",
        date: "21 Juni 2025",
        time: "05:30 WIB",
      },
      location: "Basecamp Selo → Pos 1 Merapi",
      amount: "Rp 25.000",
      timestamp: new Date(Date.now() - 5 * 60 * 1000),
      priority: "high",
      status: "new",
    },
    {
      id: 2,
      category: "porter",
      type: "confirmed",
      title: "Pesanan Porter Dikonfirmasi",
      description: "Bantuan membawa barang 15kg untuk pendakian 2 hari 1 malam",
      customer: {
        name: "Siti Marlina",
        phone: "0856-7890-1234",
        date: "22 Juni 2025",
        time: "04:00 WIB",
      },
      location: "Gunung Semeru - Jalur Ranu Pani",
      amount: "Rp 200.000",
      timestamp: new Date(Date.now() - 15 * 60 * 1000),
      priority: "medium",
      status: "confirmed",
    },
    {
      id: 3,
      category: "guide",
      type: "ongoing",
      title: "Perjalanan Guide Berlangsung",
      description: "Sedang memandu grup 6 orang pendakian Gunung Bromo sunrise",
      customer: {
        name: "Budi Santoso",
        phone: "0878-9012-3456",
        date: "21 Juni 2025",
        time: "02:00 WIB",
      },
      location: "Gunung Bromo - Jalur Cemoro Lawang",
      amount: "Rp 300.000",
      timestamp: new Date(Date.now() - 1 * 60 * 60 * 1000),
      priority: "high",
      status: "ongoing",
    },
    {
      id: 4,
      category: "basecamp",
      type: "completed",
      title: "Sewa Basecamp Selesai",
      description: "Penyewaan basecamp untuk 8 orang selama 1 malam telah selesai",
      customer: {
        name: "Dewi Kartika",
        phone: "0851-2345-6789",
        date: "20 Juni 2025",
        time: "18:00 WIB",
      },
      location: "Basecamp Gunung Gede Pangrango",
      amount: "Rp 150.000",
      timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000),
      priority: "low",
      status: "completed",
    },
    {
      id: 5,
      category: "ojek",
      type: "cancelled",
      title: "Pesanan Ojek Dibatalkan",
      description: "Pesanan antar jemput dibatalkan karena cuaca buruk",
      customer: {
        name: "Rina Kusuma",
        phone: "0812-9876-5432",
        date: "21 Juni 2025",
        time: "06:00 WIB",
      },
      location: "Basecamp Tawangmangu → Pos 2",
      amount: "Rp 30.000",
      timestamp: new Date(Date.now() - 3 * 60 * 60 * 1000),
      priority: "low",
      status: "cancelled",
    },
    {
      id: 6,
      category: "porter",
      type: "urgent",
      title: "Permintaan Porter Darurat",
      description: "Dibutuhkan porter untuk evakuasi pendaki yang mengalami cedera ringan",
      customer: {
        name: "Agus Prasetyo",
        phone: "0819-1234-5678",
        date: "21 Juni 2025",
        time: "14:30 WIB",
      },
      location: "Gunung Lawu - Pos 3",
      amount: "Rp 250.000",
      timestamp: new Date(Date.now() - 30 * 60 * 1000),
      priority: "urgent",
      status: "new",
    },
    {
      id: 7,
      category: "guide",
      type: "new",
      title: "Pesanan Guide Baru",
      description: "Permintaan guide untuk pendakian keluarga dengan 2 anak",
      customer: {
        name: "Maya Sari",
        phone: "0857-3456-7890",
        date: "23 Juni 2025",
        time: "05:00 WIB",
      },
      location: "Gunung Papandayan - Jalur Cisurupan",
      amount: "Rp 180.000",
      timestamp: new Date(Date.now() - 45 * 60 * 1000),
      priority: "medium",
      status: "new",
    },
    {
      id: 8,
      category: "basecamp",
      type: "confirmed",
      title: "Reservasi Basecamp Dikonfirmasi",
      description: "Penyewaan basecamp untuk grup 12 orang selama 2 malam",
      customer: {
        name: "Rudi Hermawan",
        phone: "0813-5678-9012",
        date: "24 Juni 2025",
        time: "16:00 WIB",
      },
      location: "Basecamp Gunung Sindoro",
      amount: "Rp 300.000",
      timestamp: new Date(Date.now() - 1.5 * 60 * 60 * 1000),
      priority: "medium",
      status: "confirmed",
    },
  ]
  
  // Global variables
  let currentFilter = "all"
  let currentSort = "newest"
  let filteredNotifications = [...notificationsData]
  
  // Initialize dashboard
  function initDashboard() {
    updateCategoryCounts()
    loadUserData()
  }
  
  // Load user data
  function loadUserData() {
    const userData = JSON.parse(localStorage.getItem("userData")) || {
      firstName: "Mitra",
      lastName: "Layanan",
      email: "mitra@konco-ndaki.com",
    }
  
    const fullName = `${userData.firstName} ${userData.lastName}`
  
    updateElementText("profileName", fullName)
    updateElementText("menuProfileName", fullName)
    updateElementText("menuProfileEmail", userData.email)
    updateElementText("mobileProfileName", fullName)
    updateElementText("mobileProfileEmail", userData.email)
  }
  
  // Update element text safely
  function updateElementText(elementId, text) {
    const element = document.getElementById(elementId)
    if (element) {
      element.textContent = text
    }
  }
  
  // Update category counts
  function updateCategoryCounts() {
    const counts = {
      all: notificationsData.length,
      ojek: notificationsData.filter((n) => n.category === "ojek").length,
      porter: notificationsData.filter((n) => n.category === "porter").length,
      guide: notificationsData.filter((n) => n.category === "guide").length,
      basecamp: notificationsData.filter((n) => n.category === "basecamp").length,
    }
  
    Object.keys(counts).forEach((category) => {
      const countElement = document.querySelector(`[data-category="${category}"] .count`)
      if (countElement) {
        countElement.textContent = counts[category]
      }
    })
  
    // Update total notifications
    const totalElement = document.getElementById("totalNotifications")
    if (totalElement) {
      totalElement.innerHTML = `<span>${counts.all}</span> notifikasi`
    }
  }
  
  // Load notifications
  function loadNotifications() {
    // Placeholder for loading notifications logic
  }
  
  // Setup event listeners
  function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById("searchInput")
    if (searchInput) {
      searchInput.addEventListener("input", handleSearch)
    }
  
    // Sort functionality
    const sortSelect = document.getElementById("sortSelect")
    if (sortSelect) {
      sortSelect.addEventListener("change", handleSort)
    }
  
    // Load more button
    const loadMoreBtn = document.getElementById("loadMoreBtn")
    if (loadMoreBtn) {
      loadMoreBtn.addEventListener("click", handleLoadMore)
    }
  
    // Profile dropdown (inherited from dashboard script)
    initProfileDropdown()
    initLogoutHandlers()
    initMobileMenu()
  }
  
  // Handle search
  function handleSearch(event) {
    const searchTerm = event.target.value.toLowerCase()
    filteredNotifications = notificationsData.filter((notification) => {
      const categoryMatch = currentFilter === "all" || notification.category === currentFilter
      const titleMatch = notification.title.toLowerCase().includes(searchTerm)
      const descriptionMatch = notification.description.toLowerCase().includes(searchTerm)
      const customerNameMatch = notification.customer.name.toLowerCase().includes(searchTerm)
      return categoryMatch && (titleMatch || descriptionMatch || customerNameMatch)
    })
  
    sortNotifications()
    renderNotifications()
  }
  
  // Handle sort
  function handleSort(event) {
    currentSort = event.target.value
    sortNotifications()
    renderNotifications()
  }
  
  // Sort notifications
  function sortNotifications() {
    filteredNotifications.sort((a, b) => {
      if (currentSort === "newest") {
        return b.timestamp - a.timestamp
      } else if (currentSort === "oldest") {
        return a.timestamp - b.timestamp
      } else if (currentSort === "priority") {
        const priorityOrder = { urgent: 3, high: 2, medium: 1, low: 0 }
        return priorityOrder[b.priority] - priorityOrder[a.priority]
      }
      return 0
    })
  }
  
  // Setup filters
  function setupFilters() {
    // Category filters
    const categoryItems = document.querySelectorAll(".category-item")
    categoryItems.forEach((item) => {
      item.addEventListener("click", () => {
        const category = item.dataset.category
        setActiveCategory(category)
        filterNotifications()
      })
    })
  }
  
  // Set active category
  function setActiveCategory(category) {
    currentFilter = category
  
    // Update active state
    document.querySelectorAll(".category-item").forEach((item) => {
      item.classList.remove("active")
    })
    document.querySelector(`[data-category="${category}"]`).classList.add("active")
  
    // Update header
    updateCategoryHeader(category)
  }
  
  // Update category header
  function updateCategoryHeader(category) {
    const headerElement = document.getElementById("categoryHeader")
    if (headerElement) {
      headerElement.textContent = getCategoryName(category)
    }
  }
  
  // Filter notifications
  function filterNotifications() {
    filteredNotifications = notificationsData.filter((notification) => {
      const categoryMatch = currentFilter === "all" || notification.category === currentFilter
      return categoryMatch
    })
  
    sortNotifications()
    renderNotifications()
  }
  
  // Render notifications
  function renderNotifications() {
    const grid = document.getElementById("notificationsGrid")
    if (!grid) return
  
    grid.innerHTML = ""
  
    if (filteredNotifications.length === 0) {
      grid.innerHTML = `
        <div class="no-notifications">
          <i class="fas fa-inbox"></i>
          <h3>Tidak ada notifikasi</h3>
          <p>Belum ada notifikasi untuk kategori yang dipilih</p>
        </div>
      `
      return
    }
  
    filteredNotifications.forEach((notification) => {
      const card = createNotificationCard(notification)
      grid.appendChild(card)
    })
  }
  
  // Create notification card
  function createNotificationCard(notification) {
    const card = document.createElement("div")
    card.className = "notification-card"
    card.dataset.id = notification.id
  
    const timeAgo = getTimeAgo(notification.timestamp)
    const serviceIcon = getServiceIcon(notification.category)
  
    card.innerHTML = `
      <div class="notification-header">
        <span class="notification-type type-${notification.type}">${getTypeLabel(notification.type)}</span>
        <h3 class="notification-title">${notification.title}</h3>
        <div class="notification-time">
          <i class="fas fa-clock"></i> ${timeAgo}
        </div>
      </div>
      <div class="notification-body">
        <div class="service-icon ${notification.category}">
          <i class="${serviceIcon}"></i>
        </div>
        <p class="notification-description">${notification.description}</p>
        <div class="customer-info">
          <h4><i class="fas fa-user"></i> Informasi Pemesan</h4>
          <div class="info-row">
            <span class="info-label">Nama:</span>
            <span class="info-value">${notification.customer.name}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Telepon:</span>
            <a href="tel:${notification.customer.phone}" class="info-value phone-number">${notification.customer.phone}</a>
          </div>
          <div class="info-row">
            <span class="info-label">Tanggal:</span>
            <span class="info-value">${notification.customer.date}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Jam:</span>
            <span class="info-value">${notification.customer.time}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Nilai:</span>
            <span class="info-value">${notification.amount}</span>
          </div>
        </div>
        <div class="notification-meta">
          <div class="notification-location">
            <i class="fas fa-map-marker-alt"></i> ${notification.location}
          </div>
        </div>
      </div>
    `
  
    // Add click event to open modal
    card.addEventListener("click", () => {
      openNotificationModal(notification)
    })
  
    return card
  }
  
  // Get service icon
  function getServiceIcon(category) {
    const icons = {
      ojek: "fas fa-motorcycle",
      porter: "fas fa-hiking",
      guide: "fas fa-user-tie",
      basecamp: "fas fa-campground",
    }
    return icons[category] || "fas fa-bell"
  }
  
  // Get type label
  function getTypeLabel(type) {
    const labels = {
      new: "PESANAN BARU",
      confirmed: "DIKONFIRMASI",
      ongoing: "BERLANGSUNG",
      completed: "SELESAI",
      cancelled: "DIBATALKAN",
      urgent: "DARURAT",
    }
    return labels[type] || type.toUpperCase()
  }
  
  // Get time ago
  function getTimeAgo(timestamp) {
    const now = new Date()
    const diff = now - timestamp
    const minutes = Math.floor(diff / (1000 * 60))
    const hours = Math.floor(diff / (1000 * 60 * 60))
    const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  
    if (minutes < 1) return "Baru saja"
    if (minutes < 60) return `${minutes} menit yang lalu`
    if (hours < 24) return `${hours} jam yang lalu`
    return `${days} hari yang lalu`
  }
  
  // Handle load more
  function handleLoadMore() {
    // Simulate loading more data
    const loadMoreBtn = document.getElementById("loadMoreBtn")
    if (loadMoreBtn) {
      loadMoreBtn.classList.add("loading")
      loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...'
  
      setTimeout(() => {
        loadMoreBtn.classList.remove("loading")
        loadMoreBtn.innerHTML = '<i class="fas fa-plus"></i> Muat Lebih Banyak'
        showNotification("Tidak ada notifikasi lagi untuk dimuat", "info")
      }, 1500)
    }
  }
  
  // Setup modal
  function setupModal() {
    const modal = document.getElementById("notificationModal")
    const modalClose = document.getElementById("modalClose")
    const modalCancel = document.getElementById("modalCancel")
  
    if (modalClose) {
      modalClose.addEventListener("click", closeNotificationModal)
    }
  
    if (modalCancel) {
      modalCancel.addEventListener("click", closeNotificationModal)
    }
  
    if (modal) {
      modal.addEventListener("click", (e) => {
        if (e.target === modal) {
          closeNotificationModal()
        }
      })
    }
  }
  
  // Open notification modal
  function openNotificationModal(notification) {
    const modal = document.getElementById("notificationModal")
    const modalTitle = document.getElementById("modalTitle")
    const modalBody = document.getElementById("modalBody")
    const modalAction = document.getElementById("modalAction")
  
    if (!modal || !modalTitle || !modalBody || !modalAction) return
  
    modalTitle.textContent = notification.title
  
    modalBody.innerHTML = `
      <div class="modal-notification-details">
        <div class="service-header">
          <div class="service-icon ${notification.category}">
            <i class="${getServiceIcon(notification.category)}"></i>
          </div>
          <div class="service-info">
            <h4>${getCategoryName(notification.category)}</h4>
            <span class="notification-type type-${notification.type}">${getTypeLabel(notification.type)}</span>
          </div>
        </div>
        
        <div class="detail-section">
          <h5><i class="fas fa-info-circle"></i> Deskripsi Layanan</h5>
          <p>${notification.description}</p>
        </div>
  
        <div class="detail-section">
          <h5><i class="fas fa-user"></i> Informasi Pemesan</h5>
          <div class="detail-grid">
            <div class="detail-item">
              <span class="detail-label">Nama Lengkap</span>
              <span class="detail-value">${notification.customer.name}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Nomor Telepon</span>
              <span class="detail-value">
                <a href="tel:${notification.customer.phone}" class="phone-number">${notification.customer.phone}</a>
              </span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Tanggal Layanan</span>
              <span class="detail-value">${notification.customer.date}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Waktu Layanan</span>
              <span class="detail-value">${notification.customer.time}</span>
            </div>
          </div>
        </div>
  
        <div class="detail-section">
          <h5><i class="fas fa-map-marker-alt"></i> Lokasi & Rute</h5>
          <p class="location-detail">${notification.location}</p>
        </div>
  
        <div class="detail-section">
          <h5><i class="fas fa-money-bill-wave"></i> Informasi Pembayaran</h5>
          <div class="payment-info">
            <div class="payment-item">
              <span class="payment-label">Nilai Layanan</span>
              <span class="payment-value">${notification.amount}</span>
            </div>
            <div class="payment-item">
              <span class="payment-label">Status Pembayaran</span>
              <span class="payment-value ${notification.status === "completed" ? "paid" : "pending"}">
                ${notification.status === "completed" ? "Lunas" : "Menunggu"}
              </span>
            </div>
          </div>
        </div>
      </div>
    `
  
    // Update modal action button
    updateModalActionButton(notification, modalAction)
  
    modal.classList.add("active")
    document.body.style.overflow = "hidden"
  }
  
  // Close notification modal
  function closeNotificationModal() {
    const modal = document.getElementById("notificationModal")
    if (modal) {
      modal.classList.remove("active")
      document.body.style.overflow = ""
    }
  }
  
  // Update modal action button
  function updateModalActionButton(notification, button) {
    switch (notification.status) {
      case "new":
        button.innerHTML = '<i class="fas fa-check"></i> Terima Pesanan'
        button.onclick = () => handleAcceptOrder(notification.id)
        break
      case "confirmed":
        button.innerHTML = '<i class="fas fa-play"></i> Mulai Layanan'
        button.onclick = () => handleStartService(notification.id)
        break
      case "ongoing":
        button.innerHTML = '<i class="fas fa-flag-checkered"></i> Selesaikan'
        button.onclick = () => handleCompleteService(notification.id)
        break
      case "completed":
        button.innerHTML = '<i class="fas fa-star"></i> Lihat Rating'
        button.onclick = () => handleViewRating(notification.id)
        break
      case "cancelled":
        button.style.display = "none"
        break
      default:
        button.innerHTML = '<i class="fas fa-info"></i> Detail'
    }
  }
  
  // Get category name
  function getCategoryName(category) {
    const names = {
      ojek: "Layanan Ojek",
      porter: "Layanan Porter",
      guide: "Layanan Guide",
      basecamp: "Sewa Basecamp",
    }
    return names[category] || category
  }
  
  // Action handlers
  function handleAcceptOrder(id) {
    const notification = notificationsData.find((n) => n.id === id)
    if (notification) {
      notification.status = "confirmed"
      notification.type = "confirmed"
  
      showNotification(`Pesanan ${notification.title} berhasil diterima!`, "success")
      closeNotificationModal()
      filterNotifications()
      updateCategoryCounts()
    }
  }
  
  function handleStartService(id) {
    const notification = notificationsData.find((n) => n.id === id)
    if (notification) {
      notification.status = "ongoing"
      notification.type = "ongoing"
  
      showNotification(`Layanan ${notification.title} telah dimulai!`, "success")
      closeNotificationModal()
      filterNotifications()
    }
  }
  
  function handleCompleteService(id) {
    const notification = notificationsData.find((n) => n.id === id)
    if (notification) {
      notification.status = "completed"
      notification.type = "completed"
  
      showNotification(`Layanan ${notification.title} telah selesai!`, "success")
      closeNotificationModal()
      filterNotifications()
    }
  }
  
  function handleViewRating(id) {
    const notification = notificationsData.find((n) => n.id === id)
    if (notification) {
      showNotification(`Menampilkan rating untuk ${notification.title}`, "info")
      closeNotificationModal()
    }
  }
  
  // Profile dropdown functionality (inherited from dashboard script)
  function initProfileDropdown() {
    const profileBtn = document.getElementById("profileBtn")
    const profileMenu = document.getElementById("profileMenu")
    const profileDropdown = document.querySelector(".profile-dropdown")
  
    if (profileBtn && profileMenu) {
      profileBtn.addEventListener("click", (e) => {
        e.stopPropagation()
        toggleProfileMenu()
      })
  
      document.addEventListener("click", (e) => {
        if (!profileDropdown.contains(e.target)) {
          closeProfileMenu()
        }
      })
  
      profileMenu.addEventListener("click", (e) => {
        e.stopPropagation()
      })
    }
  }
  
  function toggleProfileMenu() {
    const profileMenu = document.getElementById("profileMenu")
    const profileDropdown = document.querySelector(".profile-dropdown")
  
    if (profileMenu.classList.contains("active")) {
      closeProfileMenu()
    } else {
      openProfileMenu()
    }
  }
  
  function openProfileMenu() {
    const profileMenu = document.getElementById("profileMenu")
    const profileDropdown = document.querySelector(".profile-dropdown")
  
    profileMenu.classList.add("active")
    profileDropdown.classList.add("active")
  }
  
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
  
    if (confirm("Apakah Anda yakin ingin keluar?")) {
      localStorage.removeItem("userData")
      localStorage.removeItem("userLoggedIn")
  
      showNotification("Anda telah berhasil keluar", "success")
  
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
  
        if (mobileNav.classList.contains("active")) {
          menuIcon.classList.remove("fa-bars")
          menuIcon.classList.add("fa-times")
        } else {
          menuIcon.classList.remove("fa-times")
          menuIcon.classList.add("fa-bars")
        }
      })
  
      const mobileNavLinks = document.querySelectorAll(".mobile-nav-link")
      mobileNavLinks.forEach((link) => {
        link.addEventListener("click", function () {
          if (!this.classList.contains("logout")) {
            mobileNav.classList.remove("active")
            menuIcon.classList.remove("fa-times")
            menuIcon.classList.add("fa-bars")
          }
        })
      })
  
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
    const existingNotifications = document.querySelectorAll(".notification")
    existingNotifications.forEach((notification) => notification.remove())
  
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
  
    const closeButton = notification.querySelector(".notification-close")
    closeButton.addEventListener("click", () => {
      notification.remove()
      style.remove()
    })
  
    setTimeout(() => {
      if (notification.parentElement) {
        notification.remove()
        style.remove()
      }
    }, 5000)
  }
  