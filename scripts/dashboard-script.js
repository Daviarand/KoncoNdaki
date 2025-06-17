// Dashboard Script
document.addEventListener("DOMContentLoaded", () => {
    // Load user data from localStorage (simulated)
    loadUserData()
  
    // Profile dropdown functionality
    initProfileDropdown()
  
    // Logout functionality
    initLogoutHandlers()
  
    // Mobile menu functionality (inherited from main script)
    initMobileMenu()
  })
  
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
  
  // Initialize mobile menu (enhanced version)
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
  
  // Smooth animations on page load
  window.addEventListener("load", () => {
    const welcomeBanner = document.querySelector(".welcome-banner")
    const heroSection = document.querySelector(".hero")
  
    if (welcomeBanner) {
      welcomeBanner.style.opacity = "0"
      welcomeBanner.style.transform = "translateY(-20px)"
      welcomeBanner.style.transition = "all 0.6s ease"
  
      setTimeout(() => {
        welcomeBanner.style.opacity = "1"
        welcomeBanner.style.transform = "translateY(0)"
      }, 100)
    }
  
    if (heroSection) {
      heroSection.style.opacity = "0"
      heroSection.style.transform = "translateY(20px)"
      heroSection.style.transition = "all 0.6s ease"
  
      setTimeout(() => {
        heroSection.style.opacity = "1"
        heroSection.style.transform = "translateY(0)"
      }, 200)
    }
  })
  