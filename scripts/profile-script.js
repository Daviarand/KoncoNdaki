// Enhanced Profile Script
document.addEventListener("DOMContentLoaded", () => {
  // Load user data
  loadUserData()

  // Initialize profile functionality
  initProfileTabs()
  initProfileForm()
  initPasswordForm()
  initToggleButtons()
  initPasswordRequirements()

  // Initialize animations
  initAnimations()

  // Initialize profile dropdown
  initProfileDropdown()

  // Initialize logout handlers
  initLogoutHandlers()

  // Initialize mobile menu
  initMobileMenu()
})

// Load user data from localStorage
function loadUserData() {
  const userData = JSON.parse(localStorage.getItem("userData")) || {
    firstName: "John",
    lastName: "Doe",
    email: "john.doe@email.com",
    phone: "+62 812-3456-7890",
    birthDate: "1990-01-15",
    gender: "male",
    address: "Jl. Contoh No. 123, Jakarta Selatan, DKI Jakarta",
  }

  // Update profile header
  const fullName = `${userData.firstName} ${userData.lastName}`
  updateElementText("profilePageName", fullName)
  updateElementText("profilePageEmail", userData.email)

  // Update form fields
  updateElementValue("firstName", userData.firstName)
  updateElementValue("lastName", userData.lastName)
  updateElementValue("email", userData.email)
  updateElementValue("phone", userData.phone)
  updateElementValue("birthDate", userData.birthDate)
  updateElementValue("gender", userData.gender)
  updateElementValue("address", userData.address)

  // Update all profile name elements
  updateElementText("profileName", fullName)
  updateElementText("menuProfileName", fullName)
  updateElementText("menuProfileEmail", userData.email)
  updateElementText("mobileProfileName", fullName)
  updateElementText("mobileProfileEmail", userData.email)
  updateElementText("welcomeName", userData.firstName)
}

// Update element text safely
function updateElementText(elementId, text) {
  const element = document.getElementById(elementId)
  if (element) {
    element.textContent = text
  }
}

// Update element value safely
function updateElementValue(elementId, value) {
  const element = document.getElementById(elementId)
  if (element) {
    element.value = value
  }
}

// Initialize profile tabs with enhanced animations
function initProfileTabs() {
  const tabButtons = document.querySelectorAll(".tab-btn")
  const tabContents = document.querySelectorAll(".tab-content")

  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const targetTab = this.getAttribute("data-tab")

      // Pastikan hanya menangani tab yang valid
      if (!["personal", "security", "preferences"].includes(targetTab)) {
        return
      }

      // Remove active class from all tabs and contents
      tabButtons.forEach((btn) => btn.classList.remove("active"))
      tabContents.forEach((content) => {
        content.classList.remove("active")
        content.style.opacity = "0"
        content.style.transform = "translateY(20px)"
      })

      // Add active class to clicked tab
      this.classList.add("active")

      // Show target content with animation
      const targetContent = document.getElementById(`${targetTab}-tab`)
      if (targetContent) {
        setTimeout(() => {
          targetContent.classList.add("active")
          targetContent.style.opacity = "1"
          targetContent.style.transform = "translateY(0)"
        }, 150)
      }
    })
  })
}

// Initialize profile form with enhanced validation
function initProfileForm() {
  const editBtn = document.getElementById("editPersonalBtn")
  const cancelBtn = document.getElementById("cancelPersonalBtn")
  const form = document.getElementById("personalForm")
  const formActions = document.getElementById("personalActions")

  if (editBtn) {
    editBtn.addEventListener("click", function () {
      toggleEditMode(true)
      this.innerHTML = '<i class="fas fa-times"></i> Batal Edit'
      this.classList.add("btn-cancel")
    })
  }

  if (cancelBtn) {
    cancelBtn.addEventListener("click", () => {
      toggleEditMode(false)
      loadUserData() // Reload original data
    })
  }

  if (form) {
    form.addEventListener("submit", handlePersonalFormSubmit)
  }
}

// Toggle edit mode for personal information
function toggleEditMode(isEdit) {
  const inputs = document.querySelectorAll("#personalForm input, #personalForm textarea, #personalForm select")
  const editBtn = document.getElementById("editPersonalBtn")
  const formActions = document.getElementById("personalActions")

  inputs.forEach((input) => {
    if (input.name !== "email") {
      // Email should remain readonly
      if (isEdit) {
        input.removeAttribute("readonly")
        input.removeAttribute("disabled")
        input.style.background = "white"
      } else {
        input.setAttribute("readonly", "readonly")
        if (input.tagName === "SELECT") {
          input.setAttribute("disabled", "disabled")
        }
        input.style.background = "linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%)"
      }
    }
  })

  if (editBtn) {
    if (isEdit) {
      editBtn.style.display = "none"
    } else {
      editBtn.style.display = "flex"
      editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit'
      editBtn.classList.remove("btn-cancel")
    }
  }

  if (formActions) {
    formActions.style.display = isEdit ? "flex" : "none"
  }
}

// Handle personal form submission with validation
function handlePersonalFormSubmit(e) {
  e.preventDefault()

  // Show loading state
  const submitBtn = e.target.querySelector('button[type="submit"]')
  const originalText = submitBtn.innerHTML
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'
  submitBtn.disabled = true

  const formData = new FormData(e.target)
  const userData = {}

  for (const [key, value] of formData.entries()) {
    userData[key] = value.trim()
  }

  // Validate required fields
  if (!userData.firstName || !userData.lastName || !userData.phone) {
    showNotification("Mohon lengkapi semua field yang wajib diisi", "error")
    submitBtn.innerHTML = originalText
    submitBtn.disabled = false
    return
  }

  // Simulate API call
  setTimeout(() => {
    // Save to localStorage
    localStorage.setItem("userData", JSON.stringify(userData))

    // Update display
    loadUserData()

    // Exit edit mode
    toggleEditMode(false)

    // Reset button
    submitBtn.innerHTML = originalText
    submitBtn.disabled = false

    // Show success message
    showNotification("Informasi personal berhasil diperbarui", "success")
  }, 1500)
}

// Initialize password form with enhanced validation
function initPasswordForm() {
  const passwordForm = document.getElementById("passwordForm")
  const newPasswordInput = document.getElementById("newPassword")
  const confirmPasswordInput = document.getElementById("confirmNewPassword")

  if (passwordForm) {
    passwordForm.addEventListener("submit", handlePasswordFormSubmit)
  }

  if (newPasswordInput) {
    newPasswordInput.addEventListener("input", function () {
      const strength = calculatePasswordStrength(this.value)
      updatePasswordStrength(strength)
      updatePasswordRequirements(this.value)
    })
  }

  if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener("input", () => {
      validatePasswordMatch()
    })
  }
}

// Initialize password requirements checker
function initPasswordRequirements() {
  const requirements = document.querySelector(".password-requirements")
  if (requirements) {
    requirements.style.display = "none"

    const newPasswordInput = document.getElementById("newPassword")
    if (newPasswordInput) {
      newPasswordInput.addEventListener("focus", () => {
        requirements.style.display = "block"
      })

      newPasswordInput.addEventListener("blur", () => {
        if (!newPasswordInput.value) {
          requirements.style.display = "none"
        }
      })
    }
  }
}

// Update password requirements display
function updatePasswordRequirements(password) {
  const requirements = document.querySelectorAll(".password-requirement")
  if (!requirements.length) return

  const checks = {
    length: password.length >= 8,
    uppercase: /[A-Z]/.test(password),
    lowercase: /[a-z]/.test(password),
    number: /[0-9]/.test(password),
    special: /[!@#$%^&*]/.test(password),
  }

  requirements.forEach((req) => {
    const type = req.getAttribute("data-requirement")
    const icon = req.querySelector("i")
    const text = req.querySelector("span")

    if (checks[type]) {
      icon.className = "fas fa-check-circle"
      icon.style.color = "#16a34a"
      text.style.color = "#16a34a"
    } else {
      icon.className = "far fa-circle"
      icon.style.color = "#9ca3af"
      text.style.color = "#9ca3af"
    }
  })
}

// Handle password form submission
function handlePasswordFormSubmit(e) {
  e.preventDefault()

  const currentPassword = document.getElementById("currentPassword").value
  const newPassword = document.getElementById("newPassword").value
  const confirmPassword = document.getElementById("confirmNewPassword").value

  // Validate current password
  if (!currentPassword) {
    showNotification("Mohon masukkan password saat ini", "error")
    return
  }

  // Validate new password
  if (!newPassword) {
    showNotification("Mohon masukkan password baru", "error")
    return
  }

  // Validate password strength
  const strength = calculatePasswordStrength(newPassword)
  if (strength < 3) {
    showNotification("Password baru terlalu lemah", "error")
    return
  }

  // Validate password match
  if (newPassword !== confirmPassword) {
    showNotification("Password baru tidak cocok", "error")
    return
  }

  // Show loading state
  const submitBtn = e.target.querySelector('button[type="submit"]')
  const originalText = submitBtn.innerHTML
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'
  submitBtn.disabled = true

  // Simulate API call
  setTimeout(() => {
    // Reset form
    e.target.reset()
    updatePasswordStrength(0)
    updatePasswordRequirements("")

    // Reset button
    submitBtn.innerHTML = originalText
    submitBtn.disabled = false

    // Show success message
    showNotification("Password berhasil diperbarui", "success")
  }, 1500)
}

// Calculate password strength
function calculatePasswordStrength(password) {
  let strength = 0

  // Length check
  if (password.length >= 8) strength++

  // Character type checks
  if (/[A-Z]/.test(password)) strength++
  if (/[a-z]/.test(password)) strength++
  if (/[0-9]/.test(password)) strength++
  if (/[!@#$%^&*]/.test(password)) strength++

  return Math.min(strength, 5)
}

// Update password strength indicator
function updatePasswordStrength(strength) {
  const strengthBar = document.querySelector(".password-strength-bar")
  const strengthText = document.querySelector(".password-strength-text")

  if (!strengthBar || !strengthText) return

  // Update bar width and color
  const width = (strength / 5) * 100
  let color = "#ef4444" // red

  if (strength >= 4) {
    color = "#16a34a" // green
  } else if (strength >= 3) {
    color = "#f59e0b" // yellow
  } else if (strength >= 2) {
    color = "#f97316" // orange
  }

  strengthBar.style.width = `${width}%`
  strengthBar.style.backgroundColor = color

  // Update text
  let text = "Sangat Lemah"
  if (strength >= 4) {
    text = "Sangat Kuat"
  } else if (strength >= 3) {
    text = "Kuat"
  } else if (strength >= 2) {
    text = "Sedang"
  } else if (strength >= 1) {
    text = "Lemah"
  }

  strengthText.textContent = text
  strengthText.style.color = color
}

// Validate password match
function validatePasswordMatch() {
  const newPassword = document.getElementById("newPassword")
  const confirmPassword = document.getElementById("confirmNewPassword")
  const matchText = document.querySelector(".password-match-text")

  if (!newPassword || !confirmPassword || !matchText) return

  if (!confirmPassword.value) {
    matchText.textContent = ""
    matchText.style.color = "#9ca3af"
  } else if (newPassword.value === confirmPassword.value) {
    matchText.textContent = "Password cocok"
    matchText.style.color = "#16a34a"
  } else {
    matchText.textContent = "Password tidak cocok"
    matchText.style.color = "#ef4444"
  }
}

// Initialize toggle buttons
function initToggleButtons() {
  const toggleButtons = document.querySelectorAll(".toggle-btn")
  toggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const isActive = this.classList.contains("active")
      this.classList.toggle("active")
      this.querySelector(".toggle-slider").style.transform = isActive
        ? "translateX(0)"
        : "translateX(20px)"
      this.querySelector(".toggle-text").textContent = isActive ? "Nonaktif" : "Aktif"
    })
  })
}

// Initialize animations
function initAnimations() {
  // Add animation classes to elements
  const animatedElements = document.querySelectorAll(".animate-on-scroll")
  animatedElements.forEach((element) => {
    element.classList.add("fade-in")
  })

  // Add scroll event listener
  window.addEventListener("scroll", () => {
    animatedElements.forEach((element) => {
      const elementTop = element.getBoundingClientRect().top
      const elementBottom = element.getBoundingClientRect().bottom
      const isVisible = elementTop < window.innerHeight && elementBottom > 0

      if (isVisible) {
        element.classList.add("visible")
      }
    })
  })
}

// Show notification
function showNotification(message, type = "info", duration = 5000) {
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

  // Add to document
  document.body.appendChild(notification)

  // Add close button functionality
  const closeBtn = notification.querySelector(".notification-close")
  closeBtn.addEventListener("click", () => {
    notification.remove()
  })

  // Auto remove after duration
  setTimeout(() => {
    notification.remove()
  }, duration)
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

    // Redirect to login page after delay
    setTimeout(() => {
      window.location.href = "login.html"
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
