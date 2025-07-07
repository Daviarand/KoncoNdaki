// Enhanced Tentang Page JavaScript
document.addEventListener("DOMContentLoaded", () => {
  // Initialize all components
  initializeNavigation()
  initializeContactForm()
  initializeAnimations()
  initializeScrollEffects()
})

// Navigation functionality
function initializeNavigation() {
  // Dropdown profile
  const profileBtn = document.getElementById("profileBtn")
  const profileMenu = document.getElementById("profileMenu")

  if (profileBtn && profileMenu) {
    profileBtn.addEventListener("click", (e) => {
      e.stopPropagation()
      profileMenu.classList.toggle("active")
    })

    document.addEventListener("click", (e) => {
      if (!profileMenu.contains(e.target) && e.target !== profileBtn) {
        profileMenu.classList.remove("active")
      }
    })

    profileMenu.addEventListener("click", (e) => {
      e.stopPropagation()
    })
  }

  // Mobile menu
  const menuBtn = document.querySelector(".mobile-menu-btn")
  const mobileNav = document.getElementById("mobile-nav")
  const menuIcon = document.getElementById("menu-icon")

  if (menuBtn && mobileNav && menuIcon) {
    menuBtn.addEventListener("click", () => {
      mobileNav.classList.toggle("active")
      if (mobileNav.classList.contains("active")) {
        menuIcon.classList.remove("fa-bars")
        menuIcon.classList.add("fa-times")
        document.body.style.overflow = "hidden" // Prevent scroll when menu is open
      } else {
        menuIcon.classList.remove("fa-times")
        menuIcon.classList.add("fa-bars")
        document.body.style.overflow = "" // Restore scroll
      }
    })

    // Close mobile menu when clicking links
    const mobileNavLinks = document.querySelectorAll(".mobile-nav-link")
    mobileNavLinks.forEach((link) => {
      link.addEventListener("click", function () {
        if (!this.classList.contains("logout")) {
          mobileNav.classList.remove("active")
          menuIcon.classList.remove("fa-times")
          menuIcon.classList.add("fa-bars")
          document.body.style.overflow = ""
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
        document.body.style.overflow = ""
      }
    })
  }
}

// Enhanced Contact Form functionality
function initializeContactForm() {
  const contactForm = document.getElementById("contactForm")
  const submitBtn = contactForm?.querySelector(".btn-submit")

  if (contactForm && submitBtn) {
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault()

      // Get form data
      const formData = new FormData(contactForm)
      const data = Object.fromEntries(formData)

      // Validate form
      if (validateContactForm(data)) {
        submitContactForm(data, submitBtn)
      }
    })

    // Real-time validation
    const inputs = contactForm.querySelectorAll("input, select, textarea")
    inputs.forEach((input) => {
      input.addEventListener("blur", function () {
        validateField(this)
      })

      input.addEventListener("input", function () {
        clearFieldError(this)
      })
    })
  }
}

// Form validation
function validateContactForm(data) {
  let isValid = true
  const errors = {}

  // Name validation
  if (!data.name || data.name.trim().length < 2) {
    errors.name = "Nama harus diisi minimal 2 karakter"
    isValid = false
  }

  // Email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!data.email || !emailRegex.test(data.email)) {
    errors.email = "Email tidak valid"
    isValid = false
  }

  // Subject validation
  if (!data.subject) {
    errors.subject = "Subjek harus dipilih"
    isValid = false
  }

  // Message validation
  if (!data.message || data.message.trim().length < 10) {
    errors.message = "Pesan harus diisi minimal 10 karakter"
    isValid = false
  }

  // Display errors
  displayFormErrors(errors)

  return isValid
}

// Validate individual field
function validateField(field) {
  const value = field.value.trim()
  let error = ""

  switch (field.name) {
    case "name":
      if (!value || value.length < 2) {
        error = "Nama harus diisi minimal 2 karakter"
      }
      break
    case "email":
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!value || !emailRegex.test(value)) {
        error = "Email tidak valid"
      }
      break
    case "subject":
      if (!value) {
        error = "Subjek harus dipilih"
      }
      break
    case "message":
      if (!value || value.length < 10) {
        error = "Pesan harus diisi minimal 10 karakter"
      }
      break
  }

  if (error) {
    showFieldError(field, error)
    return false
  } else {
    clearFieldError(field)
    return true
  }
}

// Show field error
function showFieldError(field, message) {
  clearFieldError(field)

  field.style.borderColor = "#ef4444"
  field.style.boxShadow = "0 0 0 4px rgba(239, 68, 68, 0.1)"

  const errorDiv = document.createElement("div")
  errorDiv.className = "field-error"
  errorDiv.style.cssText = `
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    `
  errorDiv.textContent = message

  field.parentNode.appendChild(errorDiv)
}

// Clear field error
function clearFieldError(field) {
  field.style.borderColor = ""
  field.style.boxShadow = ""

  const existingError = field.parentNode.querySelector(".field-error")
  if (existingError) {
    existingError.remove()
  }
}

// Display form errors
function displayFormErrors(errors) {
  // Clear previous errors
  document.querySelectorAll(".field-error").forEach((error) => error.remove())

  // Show new errors
  Object.keys(errors).forEach((fieldName) => {
    const field = document.querySelector(`[name="${fieldName}"]`)
    if (field) {
      showFieldError(field, errors[fieldName])
    }
  })
}

// Submit contact form
async function submitContactForm(data, submitBtn) {
  // Show loading state
  const originalText = submitBtn.innerHTML
  submitBtn.innerHTML = '<i class="fas fa-spinner"></i> Mengirim...'
  submitBtn.classList.add("loading")
  submitBtn.disabled = true

  try {
    // Simulate API call (replace with actual endpoint)
    await new Promise((resolve) => setTimeout(resolve, 2000))

    // Success
    showNotification("Pesan berhasil dikirim! Kami akan segera menghubungi Anda.", "success")
    document.getElementById("contactForm").reset()
  } catch (error) {
    // Error
    showNotification("Terjadi kesalahan. Silakan coba lagi.", "error")
    console.error("Form submission error:", error)
  } finally {
    // Reset button
    submitBtn.innerHTML = originalText
    submitBtn.classList.remove("loading")
    submitBtn.disabled = false
  }
}

// Show notification
function showNotification(message, type = "info") {
  // Remove existing notifications
  const existingNotifications = document.querySelectorAll(".notification")
  existingNotifications.forEach((notification) => notification.remove())

  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        background: ${type === "success" ? "#16a34a" : type === "error" ? "#ef4444" : "#3b82f6"};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        font-weight: 600;
        max-width: 400px;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `

  notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-${type === "success" ? "check-circle" : type === "error" ? "exclamation-circle" : "info-circle"}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer; margin-left: auto;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `

  document.body.appendChild(notification)

  // Animate in
  setTimeout(() => {
    notification.style.transform = "translateX(0)"
  }, 100)

  // Auto remove after 5 seconds
  setTimeout(() => {
    notification.style.transform = "translateX(100%)"
    setTimeout(() => notification.remove(), 300)
  }, 5000)
}

// Initialize animations
function initializeAnimations() {
  // Animate elements on scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1"
        entry.target.style.transform = "translateY(0)"
      }
    })
  }, observerOptions)

  // Observe elements for animation
  const animatedElements = document.querySelectorAll(`
        .mv-card, .value-card, .team-card, .achievement-card,
        .stat-item, .contact-method, .story-text, .story-image
    `)

  animatedElements.forEach((element) => {
    element.style.opacity = "0"
    element.style.transform = "translateY(30px)"
    element.style.transition = "opacity 0.6s ease, transform 0.6s ease"
    observer.observe(element)
  })
}

// Initialize scroll effects
function initializeScrollEffects() {
  let ticking = false

  function updateScrollEffects() {
    const scrolled = window.pageYOffset
    const rate = scrolled * -0.5

    // Parallax effect for hero section
    const heroSection = document.querySelector(".about-hero")
    if (heroSection) {
      heroSection.style.transform = `translateY(${rate}px)`
    }

    ticking = false
  }

  function requestTick() {
    if (!ticking) {
      requestAnimationFrame(updateScrollEffects)
      ticking = true
    }
  }

  window.addEventListener("scroll", requestTick)

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        })
      }
    })
  })
}

// Logout functionality
document.addEventListener("click", (e) => {
  if (e.target.closest("#logoutBtn") || e.target.closest("#mobileLogoutBtn")) {
    e.preventDefault()

    if (confirm("Apakah Anda yakin ingin keluar?")) {
      // Show loading
      const btn = e.target.closest("a")
      const originalText = btn.innerHTML
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Keluar...'

      // Simulate logout process
      setTimeout(() => {
        window.location.href = "auth/logout.php"
      }, 1000)
    }
  }
})

// Keyboard navigation support
document.addEventListener("keydown", (e) => {
  // Close mobile menu with Escape key
  if (e.key === "Escape") {
    const mobileNav = document.getElementById("mobile-nav")
    const menuIcon = document.getElementById("menu-icon")

    if (mobileNav && mobileNav.classList.contains("active")) {
      mobileNav.classList.remove("active")
      menuIcon.classList.remove("fa-times")
      menuIcon.classList.add("fa-bars")
      document.body.style.overflow = ""
    }

    // Close profile dropdown
    const profileMenu = document.getElementById("profileMenu")
    if (profileMenu && profileMenu.classList.contains("active")) {
      profileMenu.classList.remove("active")
    }
  }

  // Handle form submission with Enter key
  if (e.key === "Enter" && e.target.tagName !== "TEXTAREA") {
    const form = e.target.closest("form")
    if (form && form.id === "contactForm") {
      e.preventDefault()
      form.dispatchEvent(new Event("submit"))
    }
  }
})

// Performance optimization: Debounce scroll events
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// Add loading states for better UX
window.addEventListener("beforeunload", () => {
  document.body.style.opacity = "0.7"
})

// Initialize tooltips for better accessibility
function initializeTooltips() {
  const elementsWithTooltips = document.querySelectorAll("[title]")
  elementsWithTooltips.forEach((element) => {
    element.addEventListener("mouseenter", () => {
      // Add custom tooltip implementation if needed
    })
  })
}

// Call initialize tooltips
initializeTooltips()
