// Booking Form Logic for form-pemesanan.html
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
    this.taxRate = 0.10
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
    document.getElementById("hikingDate").addEventListener("change", (e) => {
      this.bookingData.date = e.target.value
      this.updateNextButton(2)
      this.updateSummary()
    })

    document.getElementById("participants").addEventListener("change", (e) => {
      this.bookingData.participants = e.target.value
      this.updateNextButton(2)
      this.updateSummary()
    })

    // Payment method
    document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
      radio.addEventListener("change", (e) => {
        this.bookingData.paymentMethod = e.target.value
      })
    })

    // Navigation buttons
    document.getElementById("nextStep1").addEventListener("click", () => this.nextStep())
    document.getElementById("nextStep2").addEventListener("click", () => this.nextStep())
    document.getElementById("nextStep3").addEventListener("click", () => this.nextStep())

    document.getElementById("backStep1").addEventListener("click", () => {
      window.location.href = "dashboard.php" // Mengarahkan ke dashboard.html
    })

    document.getElementById("backStep2").addEventListener("click", () => this.prevStep())
    document.getElementById("backStep3").addEventListener("click", () => this.prevStep())
    document.getElementById("backStep4").addEventListener("click", () => this.prevStep())

    // Form submission
    document.getElementById("bookingForm").addEventListener("submit", (e) => this.submitForm(e))
  }

  setMinDate() {
    const today = new Date()
    const tomorrow = new Date(today)
    tomorrow.setDate(tomorrow.getDate() + 1)

    const minDate = tomorrow.toISOString().split("T")[0]
    document.getElementById("hikingDate").setAttribute("min", minDate)
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
    const routeOption = e.target.closest(".route-option")
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
    document.getElementById(`step-${stepNumber}`).classList.add("active")

    // Update current step
    this.currentStep = stepNumber

    // Update step indicator
    this.updateStepIndicator()

    // Update summary for step 4
    if (stepNumber === 4) {
      this.updateBookingSummary()
    }

    // Scroll to top of form
    document.querySelector(".booking-form-container").scrollIntoView({
      behavior: "smooth",
    })
  }

  updateStepIndicator() {
    document.querySelectorAll(".step-item").forEach((item, index) => {
      const stepNumber = index + 1
      if (stepNumber <= this.currentStep) {
        item.classList.add("completed")
      } else {
        item.classList.remove("completed")
      }

      if (stepNumber === this.currentStep) {
        item.classList.add("active")
      } else {
        item.classList.remove("active")
      }
    })
  }

  updateSummary() {
    const sidebarSummary = document.getElementById("sidebarSummary")

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
const subtotalPrice = basePrice * participants + servicePrices.total
const taxAmount = subtotalPrice * this.taxRate // Hitung pajak
const totalPriceWithTax = subtotalPrice + taxAmount // Tambahkan pajak ke total

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
              <div class="summary-item" style="border-top: 1px dashed #ccc; padding-top: 0.5rem; margin-top: 0.5rem;">
                  <span class="summary-label">Subtotal</span>
                  <span class="summary-value">Rp ${subtotalPrice.toLocaleString("id-ID")}</span>
              </div>
              <div class="summary-item">
                  <span class="summary-label">Pajak (10%)</span>
                  <span class="summary-value">Rp ${taxAmount.toLocaleString("id-ID")}</span>
              </div>
              <div class="summary-item" style="border-top: 2px solid #16a34a; padding-top: 1rem; margin-top: 1rem;">
                  <span class="summary-label">Total</span>
                  <span class="summary-value">Rp ${totalPriceWithTax.toLocaleString("id-ID")}</span>
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

  if (!this.bookingData.mountain) {
    bookingSummary.innerHTML = "<p>Data pemesanan tidak lengkap.</p>"
    return
  }

const mountain = this.mountainData[this.bookingData.mountain]
const route = mountain.routes.find((r) => r.id === this.bookingData.route)
const basePrice = mountain.price
const participants = Number.parseInt(this.bookingData.participants) || 1
const servicePrices = this.calculateServicePrices()
const subtotalPrice = basePrice * participants + servicePrices.total
const taxAmount = subtotalPrice * this.taxRate // Hitung pajak
const totalPriceWithTax = subtotalPrice + taxAmount // Tambahkan pajak ke total
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
              <div class="summary-item" style="border-top: 1px dashed #ccc; padding-top: 1rem; margin-top: 1rem;">
                  <span class="summary-label">Subtotal</span>
                  <span class="summary-value">Rp ${subtotalPrice.toLocaleString("id-ID")}</span>
              </div>
              <div class="summary-item">
                  <span class="summary-label">Pajak (10%)</span>
                  <span class="summary-value">Rp ${taxAmount.toLocaleString("id-ID")}</span>
              </div>
              <div class="summary-item" style="border-top: 2px solid #16a34a; padding-top: 1rem; margin-top: 1rem;">
                  <span class="summary-label"><strong>Total Pembayaran</strong></span>
                  <span class="summary-value"><strong>Rp ${totalPriceWithTax.toLocaleString("id-ID")}</strong></span>
              </div>
          `

    bookingSummary.innerHTML = summaryHTML
  }

  submitForm(e) {
    e.preventDefault()

    // Validate required fields
    if (!this.validateBookingData()) {
      alert("Mohon lengkapi semua data yang diperlukan.")
      return
    }

    // Show loading state
    const submitButton = document.getElementById("submitBooking")
    const originalText = submitButton.innerHTML
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'
    submitButton.disabled = true

    // Simulate API call
    setTimeout(() => {
      alert("Pemesanan berhasil! Terima kasih telah memesan tiket di KoncoNdaki.")
      submitButton.innerHTML = originalText
      submitButton.disabled = false
      document.getElementById("bookingForm").reset()
      window.location.href = "dashboard.html"
    }, 2000)
  }

  validateBookingData() {
    const required = ["mountain", "route", "date", "participants", "paymentMethod"]
    return required.every((field) => this.bookingData[field] !== null && this.bookingData[field] !== "")
  }
}

// Inisialisasi booking form setelah DOM siap
window.addEventListener("DOMContentLoaded", () => {
new BookingForm()
})
