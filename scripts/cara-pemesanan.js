// Cara Pemesanan Script
class BookingForm {
  constructor() {
    this.currentStep = 1
    this.totalSteps = 4
    this.bookingData = {
      mountain: null,
      route: null,
      date: null,
      participants: null,
      services: [],
      paymentMethod: null,
    }
    this.mountainData = this.initializeMountainData()
    this.init()
  }

  initializeMountainData() {
    return {
      bromo: {
        name: "Gunung Bromo",
        location: "Jawa Timur",
        height: "2.329 mdpl",
        price: 35000,
        routes: [
          {
            id: "bromo-cemoro",
            name: "Jalur Cemoro Lawang",
            difficulty: "easy",
            duration: "2-3 jam",
            distance: "3 km",
            elevation: "400 m",
            description: "Jalur paling populer dan mudah untuk pemula. Akses kendaraan hingga dekat kawah.",
          },
          {
            id: "bromo-wonokitri",
            name: "Jalur Wonokitri",
            difficulty: "medium",
            duration: "4-5 jam",
            distance: "8 km",
            elevation: "600 m",
            description: "Jalur alternatif dengan pemandangan savana yang indah.",
          },
        ],
      },
      merapi: {
        name: "Gunung Merapi",
        location: "Jawa Tengah",
        height: "2.930 mdpl",
        price: 25000,
        routes: [
          {
            id: "merapi-selo",
            name: "Jalur Selo",
            difficulty: "hard",
            duration: "6-8 jam",
            distance: "12 km",
            elevation: "1200 m",
            description: "Jalur klasik dengan pemandangan sunrise terbaik dari puncak.",
          },
          {
            id: "merapi-kaliurang",
            name: "Jalur Kaliurang",
            difficulty: "medium",
            duration: "5-6 jam",
            distance: "10 km",
            elevation: "1000 m",
            description: "Jalur dengan fasilitas lengkap dan akses yang mudah.",
          },
        ],
      },
      semeru: {
        name: "Gunung Semeru",
        location: "Jawa Timur",
        height: "3.676 mdpl",
        price: 45000,
        routes: [
          {
            id: "semeru-ranu-pani",
            name: "Jalur Ranu Pani",
            difficulty: "hard",
            duration: "2-3 hari",
            distance: "20 km",
            elevation: "2200 m",
            description: "Jalur utama menuju puncak tertinggi Pulau Jawa. Memerlukan persiapan matang.",
          },
        ],
      },
      gede: {
        name: "Gunung Gede",
        location: "Jawa Barat",
        height: "2.958 mdpl",
        price: 30000,
        routes: [
          {
            id: "gede-gunung-putri",
            name: "Jalur Gunung Putri",
            difficulty: "medium",
            duration: "4-5 jam",
            distance: "8 km",
            elevation: "1000 m",
            description: "Jalur dengan hutan tropis yang rimbun dan air terjun.",
          },
          {
            id: "gede-cibodas",
            name: "Jalur Cibodas",
            difficulty: "easy",
            duration: "3-4 jam",
            distance: "6 km",
            elevation: "800 m",
            description: "Jalur termudah dengan fasilitas terlengkap.",
          },
        ],
      },
    }
  }

  init() {
    this.bindEvents()
    this.setMinDate()
    this.updateStepIndicator()
  }

  bindEvents() {
    // Mountain selection
    document.querySelectorAll(".mountain-option").forEach((option) => {
      option.addEventListener("click", (e) => this.selectMountain(e))
    })

    // Route selection (will be bound dynamically)
    document.addEventListener("click", (e) => {
      if (e.target.closest(".route-option")) {
        this.selectRoute(e)
      }
    })

    // Service selection
    document.querySelectorAll('input[name="services[]"]').forEach((checkbox) => {
      checkbox.addEventListener("change", (e) => this.toggleService(e))
    })

    // Form inputs
    const hikingDateInput = document.getElementById("hikingDate")
    if (hikingDateInput) {
      hikingDateInput.addEventListener("change", (e) => {
        this.bookingData.date = e.target.value
        this.updateNextButton(2)
        this.updateSummary()
      })
    }

    const participantsInput = document.getElementById("participants")
    if (participantsInput) {
      participantsInput.addEventListener("change", (e) => {
        this.bookingData.participants = e.target.value
        this.updateNextButton(2)
        this.updateSummary()
      })
    }

    // Payment method
    document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
      radio.addEventListener("change", (e) => {
        this.bookingData.paymentMethod = e.target.value
      })
    })

    // Navigation buttons
    const nextStep1 = document.getElementById("nextStep1")
    const nextStep2 = document.getElementById("nextStep2")
    const nextStep3 = document.getElementById("nextStep3")

    if (nextStep1) nextStep1.addEventListener("click", () => this.nextStep())
    if (nextStep2) nextStep2.addEventListener("click", () => this.nextStep())
    if (nextStep3) nextStep3.addEventListener("click", () => this.nextStep())

    const backStep2 = document.getElementById("backStep2")
    const backStep3 = document.getElementById("backStep3")
    const backStep4 = document.getElementById("backStep4")

    if (backStep2) backStep2.addEventListener("click", () => this.prevStep())
    if (backStep3) backStep3.addEventListener("click", () => this.prevStep())
    if (backStep4) backStep4.addEventListener("click", () => this.prevStep())

    // Form submission
    const bookingForm = document.getElementById("bookingForm")
    if (bookingForm) {
      bookingForm.addEventListener("submit", (e) => this.submitForm(e))
    }

    // FIXED: FAQ toggles - Proper event binding
    document.querySelectorAll(".faq-question").forEach((question) => {
      question.addEventListener("click", (e) => this.toggleFAQ(e))
    })
  }

  setMinDate() {
    const hikingDateInput = document.getElementById("hikingDate")
    if (hikingDateInput) {
      const today = new Date()
      const tomorrow = new Date(today)
      tomorrow.setDate(tomorrow.getDate() + 1)

      const minDate = tomorrow.toISOString().split("T")[0]
      hikingDateInput.setAttribute("min", minDate)
    }
  }

  selectMountain(e) {
    const mountainOption = e.currentTarget
    const mountainId = mountainOption.dataset.mountain

    // Remove previous selection
    document.querySelectorAll(".mountain-option").forEach((option) => {
      option.classList.remove("selected")
    })

    // Add selection to clicked option
    mountainOption.classList.add("selected")

    // Update booking data
    this.bookingData.mountain = mountainId

    // Enable next button
    this.updateNextButton(1)

    // Load routes for selected mountain
    this.loadRoutes(mountainId)

    // Update summary
    this.updateSummary()
  }

  loadRoutes(mountainId) {
    const routeContainer = document.getElementById("routeSelection")
    if (!routeContainer) return

    const mountain = this.mountainData[mountainId]

    if (!mountain || !mountain.routes) {
      routeContainer.innerHTML = "<p>Tidak ada jalur tersedia untuk gunung ini.</p>"
      return
    }

    let routesHTML = ""
    mountain.routes.forEach((route) => {
      routesHTML += `
                  <div class="route-option" data-route="${route.id}">
                      <div class="route-header">
                          <h4>${route.name}</h4>
                          <span class="route-difficulty ${route.difficulty}">${this.getDifficultyText(route.difficulty)}</span>
                      </div>
                      <div class="route-info">
                          <div class="route-stat">
                              <i class="fas fa-clock"></i>
                              <span class="stat-value">${route.duration}</span>
                              <span class="stat-label">Durasi</span>
                          </div>
                          <div class="route-stat">
                              <i class="fas fa-route"></i>
                              <span class="stat-value">${route.distance}</span>
                              <span class="stat-label">Jarak</span>
                          </div>
                          <div class="route-stat">
                              <i class="fas fa-mountain"></i>
                              <span class="stat-value">${route.elevation}</span>
                              <span class="stat-label">Elevasi</span>
                          </div>
                      </div>
                      <div class="route-description">
                          <p>${route.description}</p>
                      </div>
                  </div>
              `
    })

    routeContainer.innerHTML = routesHTML
  }

  getDifficultyText(difficulty) {
    const difficultyMap = {
      easy: "Mudah",
      medium: "Sedang",
      hard: "Sulit",
    }
    return difficultyMap[difficulty] || difficulty
  }

  selectRoute(e) {
    const routeOption = e.currentTarget
    const routeId = routeOption.dataset.route

    // Remove previous selection
    document.querySelectorAll(".route-option").forEach((option) => {
      option.classList.remove("selected")
    })

    // Add selection to clicked option
    routeOption.classList.add("selected")

    // Update booking data
    this.bookingData.route = routeId

    // Update next button
    this.updateNextButton(2)

    // Update summary
    this.updateSummary()
  }

  toggleService(e) {
    const checkbox = e.target
    const serviceCard = checkbox.closest(".service-card")
    const serviceId = checkbox.value

    if (checkbox.checked) {
      serviceCard.classList.add("selected")
      if (!this.bookingData.services.includes(serviceId)) {
        this.bookingData.services.push(serviceId)
      }
    } else {
      serviceCard.classList.remove("selected")
      this.bookingData.services = this.bookingData.services.filter((s) => s !== serviceId)
    }

    this.updateSummary()
  }

  updateNextButton(step) {
    const nextButton = document.getElementById(`nextStep${step}`)
    if (!nextButton) return

    let canProceed = false

    switch (step) {
      case 1:
        canProceed = this.bookingData.mountain !== null
        break
      case 2:
        canProceed =
          this.bookingData.route !== null && this.bookingData.date !== null && this.bookingData.participants !== null
        break
      case 3:
        canProceed = true // Services are optional
        break
    }

    nextButton.disabled = !canProceed
  }

  nextStep() {
    if (this.currentStep < this.totalSteps) {
      this.showStep(this.currentStep + 1)
    }
  }

  prevStep() {
    if (this.currentStep > 1) {
      this.showStep(this.currentStep - 1)
    }
  }

  showStep(stepNumber) {
    // Hide all steps
    document.querySelectorAll(".form-step").forEach((step) => {
      step.classList.remove("active")
    })

    // Show target step
    const targetStep = document.getElementById(`step-${stepNumber}`)
    if (targetStep) {
      targetStep.classList.add("active")
    }

    // Update current step
    this.currentStep = stepNumber

    // Update step indicator
    this.updateStepIndicator()

    // Update summary for step 4
    if (stepNumber === 4) {
      this.updateBookingSummary()
    }

    // Scroll to top of form
    const formContainer = document.querySelector(".booking-form-container")
    if (formContainer) {
      formContainer.scrollIntoView({
        behavior: "smooth",
      })
    }
  }

  updateStepIndicator() {
    document.querySelectorAll(".step-item").forEach((item, index) => {
      const stepNumber = index + 1

      // Remove all classes first
      item.classList.remove("completed", "active")

      if (stepNumber < this.currentStep) {
        item.classList.add("completed")
      } else if (stepNumber === this.currentStep) {
        item.classList.add("active")
      }
    })
  }

  updateSummary() {
    const sidebarSummary = document.getElementById("sidebarSummary")
    if (!sidebarSummary) return

    if (!this.bookingData.mountain) {
      sidebarSummary.innerHTML = `
                  <div class="summary-placeholder">
                      <i class="fas fa-clipboard-list"></i>
                      <p>Pilih gunung untuk melihat ringkasan pemesanan</p>
                  </div>
              `
      return
    }

    const mountain = this.mountainData[this.bookingData.mountain]
    const basePrice = mountain.price
    const participants = Number.parseInt(this.bookingData.participants) || 1
    const servicePrices = this.calculateServicePrices()
    const totalPrice = basePrice * participants + servicePrices.total

    let summaryHTML = `
              <div class="summary-item">
                  <span class="summary-label">Gunung</span>
                  <span class="summary-value">${mountain.name}</span>
              </div>
              <div class="summary-item">
                  <span class="summary-label">Tiket (${participants} orang)</span>
                  <span class="summary-value">Rp ${(basePrice * participants).toLocaleString("id-ID")}</span>
              </div>
          `

    if (this.bookingData.route) {
      const route = mountain.routes.find((r) => r.id === this.bookingData.route)
      if (route) {
        summaryHTML += `
                      <div class="summary-item">
                          <span class="summary-label">Jalur</span>
                          <span class="summary-value">${route.name}</span>
                      </div>
                  `
      }
    }

    if (this.bookingData.date) {
      const date = new Date(this.bookingData.date)
      summaryHTML += `
                  <div class="summary-item">
                      <span class="summary-label">Tanggal</span>
                      <span class="summary-value">${date.toLocaleDateString("id-ID", {
                        weekday: "long",
                        year: "numeric",
                        month: "long",
                        day: "numeric",
                      })}</span>
                  </div>
              `
    }

    if (servicePrices.services.length > 0) {
      servicePrices.services.forEach((service) => {
        summaryHTML += `
                      <div class="summary-item">
                          <span class="summary-label">${service.name}</span>
                          <span class="summary-value">Rp ${service.price.toLocaleString("id-ID")}</span>
                      </div>
                  `
      })
    }

    summaryHTML += `
              <div class="summary-item">
                  <span class="summary-label">Total</span>
                  <span class="summary-value">Rp ${totalPrice.toLocaleString("id-ID")}</span>
              </div>
          `

    sidebarSummary.innerHTML = summaryHTML
  }

  calculateServicePrices() {
    const servicePrices = {
      guide: 150000,
      porter: 100000,
      ojek: 50000,
      basecamp: 75000,
    }

    const serviceNames = {
      guide: "Jasa Guide",
      porter: "Jasa Porter",
      ojek: "Jasa Ojek",
      basecamp: "Sewa Basecamp",
    }

    let total = 0
    const services = []

    this.bookingData.services.forEach((serviceId) => {
      if (servicePrices[serviceId]) {
        const price = servicePrices[serviceId]
        total += price
        services.push({
          id: serviceId,
          name: serviceNames[serviceId],
          price: price,
        })
      }
    })

    return { total, services }
  }

  updateBookingSummary() {
    const bookingSummary = document.getElementById("bookingSummary")
    if (!bookingSummary) return

    if (!this.bookingData.mountain) {
      bookingSummary.innerHTML = "<p>Data pemesanan tidak lengkap.</p>"
      return
    }

    const mountain = this.mountainData[this.bookingData.mountain]
    const route = mountain.routes.find((r) => r.id === this.bookingData.route)
    const basePrice = mountain.price
    const participants = Number.parseInt(this.bookingData.participants) || 1
    const servicePrices = this.calculateServicePrices()
    const totalPrice = basePrice * participants + servicePrices.total
    const date = new Date(this.bookingData.date)

    let summaryHTML = `
              <h4>Detail Pemesanan</h4>
              <div class="summary-item">
                  <span class="summary-label">Gunung</span>
                  <span class="summary-value">${mountain.name}</span>
              </div>
              <div class="summary-item">
                  <span class="summary-label">Jalur</span>
                  <span class="summary-value">${route ? route.name : "Tidak dipilih"}</span>
              </div>
              <div class="summary-item">
                  <span class="summary-label">Tanggal</span>
                  <span class="summary-value">${date.toLocaleDateString("id-ID", {
                    weekday: "long",
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                  })}</span>
              </div>
              <div class="summary-item">
                  <span class="summary-label">Jumlah Pendaki</span>
                  <span class="summary-value">${participants} orang</span>
              </div>
          `

    if (servicePrices.services.length > 0) {
      summaryHTML += '<h4 style="margin-top: 1.5rem;">Layanan Tambahan</h4>'
      servicePrices.services.forEach((service) => {
        summaryHTML += `
                      <div class="summary-item">
                          <span class="summary-label">${service.name}</span>
                          <span class="summary-value">Rp ${service.price.toLocaleString("id-ID")}</span>
                      </div>
                  `
      })
    }

    summaryHTML += `
              <h4 style="margin-top: 1.5rem;">Rincian Biaya</h4>
              <div class="summary-item">
                  <span class="summary-label">Tiket Pendakian (${participants} orang)</span>
                  <span class="summary-value">Rp ${(basePrice * participants).toLocaleString("id-ID")}</span>
              </div>
          `

    if (servicePrices.total > 0) {
      summaryHTML += `
                  <div class="summary-item">
                      <span class="summary-label">Layanan Tambahan</span>
                      <span class="summary-value">Rp ${servicePrices.total.toLocaleString("id-ID")}</span>
                  </div>
              `
    }

    summaryHTML += `
              <div class="summary-item" style="border-top: 2px solid #16a34a; padding-top: 1rem; margin-top: 1rem;">
                  <span class="summary-label"><strong>Total Pembayaran</strong></span>
                  <span class="summary-value"><strong>Rp ${totalPrice.toLocaleString("id-ID")}</strong></span>
              </div>
          `

    bookingSummary.innerHTML = summaryHTML
  }

  submitForm(e) {
    e.preventDefault()

    // Validate required fields
    if (!this.validateBookingData()) {
      this.showNotification("Mohon lengkapi semua data yang diperlukan.", "error")
      return
    }

    // Show loading state
    const submitButton = document.getElementById("submitBooking")
    if (submitButton) {
      const originalText = submitButton.innerHTML
      submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'
      submitButton.disabled = true

      // Simulate API call
      setTimeout(() => {
        this.processPayment()
        submitButton.innerHTML = originalText
        submitButton.disabled = false
      }, 2000)
    }
  }

  validateBookingData() {
    const required = ["mountain", "route", "date", "participants", "paymentMethod"]
    return required.every((field) => this.bookingData[field] !== null && this.bookingData[field] !== "")
  }

  processPayment() {
    // Generate booking ID
    const bookingId = "KN" + Date.now().toString().slice(-8)

    // Show success message
    this.showSuccessModal(bookingId)

    // Reset form (optional)
    // this.resetForm();
  }

  showSuccessModal(bookingId) {
    const modal = document.createElement("div")
    modal.className = "booking-success-modal"
    modal.innerHTML = `
              <div class="modal-overlay">
                  <div class="modal-content">
                      <div class="success-icon">
                          <i class="fas fa-check-circle"></i>
                      </div>
                      <h3>Pemesanan Berhasil!</h3>
                      <p>Terima kasih! Pemesanan Anda telah berhasil diproses.</p>
                      <div class="booking-id">
                          <strong>ID Pemesanan: ${bookingId}</strong>
                      </div>
                      <p class="booking-note">
                          Silakan simpan ID pemesanan ini untuk referensi Anda. 
                          Detail pemesanan telah dikirim ke email Anda.
                      </p>
                      <div class="modal-actions">
                          <button class="btn-primary" onclick="this.closest('.booking-success-modal').remove()">
                              Tutup
                          </button>
                          <button class="btn-secondary" onclick="window.print()">
                              Cetak Tiket
                          </button>
                      </div>
                  </div>
              </div>
          `

    // Add modal styles
    const style = document.createElement("style")
    style.textContent = `
              .booking-success-modal {
                  position: fixed;
                  top: 0;
                  left: 0;
                  right: 0;
                  bottom: 0;
                  z-index: 10000;
              }
              
              .modal-overlay {
                  background: rgba(0, 0, 0, 0.5);
                  width: 100%;
                  height: 100%;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  padding: 1rem;
              }
              
              .modal-content {
                  background: white;
                  border-radius: 1rem;
                  padding: 2rem;
                  max-width: 500px;
                  width: 100%;
                  text-align: center;
                  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
              }
              
              .success-icon {
                  font-size: 4rem;
                  color: #16a34a;
                  margin-bottom: 1rem;
              }
              
              .modal-content h3 {
                  font-size: 1.5rem;
                  font-weight: 700;
                  color: #1f2937;
                  margin-bottom: 1rem;
              }
              
              .booking-id {
                  background: #f0fdf4;
                  border: 1px solid #bbf7d0;
                  border-radius: 0.5rem;
                  padding: 1rem;
                  margin: 1rem 0;
                  color: #166534;
              }
              
              .booking-note {
                  color: #6b7280;
                  font-size: 0.875rem;
                  line-height: 1.5;
                  margin-bottom: 2rem;
              }
              
              .modal-actions {
                  display: flex;
                  gap: 1rem;
                  justify-content: center;
              }
              
              .modal-actions .btn-primary,
              .modal-actions .btn-secondary {
                  padding: 0.75rem 1.5rem;
                  border: none;
                  border-radius: 0.5rem;
                  font-weight: 600;
                  cursor: pointer;
                  transition: all 0.3s ease;
              }
              
              .modal-actions .btn-primary {
                  background: #16a34a;
                  color: white;
              }
              
              .modal-actions .btn-primary:hover {
                  background: #15803d;
              }
              
              .modal-actions .btn-secondary {
                  background: #f3f4f6;
                  color: #374151;
              }
              
              .modal-actions .btn-secondary:hover {
                  background: #e5e7eb;
              }
          `

    document.head.appendChild(style)
    document.body.appendChild(modal)
  }

  showNotification(message, type = "info") {
    const notification = document.createElement("div")
    notification.className = `notification notification-${type}`
    notification.innerHTML = `
              <div class="notification-content">
                  <i class="fas fa-${type === "error" ? "exclamation-circle" : "info-circle"}"></i>
                  <span>${message}</span>
              </div>
          `

    // Add notification styles if not exists
    if (!document.querySelector("#notification-styles")) {
      const style = document.createElement("style")
      style.id = "notification-styles"
      style.textContent = `
                  .notification {
                      position: fixed;
                      top: 2rem;
                      right: 2rem;
                      z-index: 9999;
                      padding: 1rem 1.5rem;
                      border-radius: 0.5rem;
                      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                      animation: slideInRight 0.3s ease;
                  }
                  
                  .notification-info {
                      background: #dbeafe;
                      color: #1e40af;
                      border-left: 4px solid #3b82f6;
                  }
                  
                  .notification-error {
                      background: #fee2e2;
                      color: #dc2626;
                      border-left: 4px solid #ef4444;
                  }
                  
                  .notification-content {
                      display: flex;
                      align-items: center;
                      gap: 0.75rem;
                  }
                  
                  @keyframes slideInRight {
                      from {
                          transform: translateX(100%);
                          opacity: 0;
                      }
                      to {
                          transform: translateX(0);
                          opacity: 1;
                      }
                  }
              `
      document.head.appendChild(style)
    }

    document.body.appendChild(notification)

    // Auto remove after 5 seconds
    setTimeout(() => {
      notification.remove()
    }, 5000)
  }

  // FIXED: FAQ Toggle Function
  toggleFAQ(e) {
    e.preventDefault()
    e.stopPropagation()

    const faqQuestion = e.currentTarget
    const faqItem = faqQuestion.closest(".faq-item")

    if (!faqItem) return

    const isCurrentlyActive = faqItem.classList.contains("active")

    // Close all FAQ items first
    document.querySelectorAll(".faq-item").forEach((item) => {
      item.classList.remove("active")
    })

    // If the clicked item wasn't active, open it
    if (!isCurrentlyActive) {
      faqItem.classList.add("active")
    }
  }

  resetForm() {
    this.currentStep = 1
    this.bookingData = {
      mountain: null,
      route: null,
      date: null,
      participants: null,
      services: [],
      paymentMethod: null,
    }

    // Reset form elements
    const bookingForm = document.getElementById("bookingForm")
    if (bookingForm) {
      bookingForm.reset()
    }

    // Remove selections
    document.querySelectorAll(".selected").forEach((element) => {
      element.classList.remove("selected")
    })

    // Show first step
    this.showStep(1)

    // Update summary
    this.updateSummary()
  }
}

// Initialize booking form when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  new BookingForm()
})

// Mobile menu functionality (if not already included)
document.addEventListener("DOMContentLoaded", () => {
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn")
  const mobileNav = document.getElementById("mobile-nav")
  const menuIcon = document.getElementById("menu-icon")

  if (mobileMenuBtn && mobileNav) {
    mobileMenuBtn.addEventListener("click", () => {
      mobileNav.classList.toggle("active")

      if (mobileNav.classList.contains("active")) {
        menuIcon.classList.remove("fa-bars")
        menuIcon.classList.add("fa-times")
      } else {
        menuIcon.classList.remove("fa-times")
        menuIcon.classList.add("fa-bars")
      }
    })
  }

  // Profile dropdown functionality
  const profileBtn = document.getElementById("profileBtn")
  const profileMenu = document.getElementById("profileMenu")

  if (profileBtn && profileMenu) {
    profileBtn.addEventListener("click", (e) => {
      e.stopPropagation()
      profileMenu.classList.toggle("active")
    })

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.classList.remove("active")
      }
    })
  }
})