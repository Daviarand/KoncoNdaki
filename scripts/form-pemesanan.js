// Enhanced Booking Form JavaScript
class BookingForm {
  constructor() {
    this.currentStep = 1
    this.totalSteps = 4
    this.bookingData = {
      mountain: null,
      route: null,
      date: null,
      participants: 1,
      services: [],
      paymentMethod: "bank_transfer",
      tiket_id: null,
    }

    this.mountainData = this.initializeMountainData()
    this.servicePrices = this.initializeServicePrices()
    this.taxRate = 0.1 // Admin Fee 10%
    this.hasUnsavedChanges = false
    this.init()
  }

  initializeMountainData() {
    // Gunakan data dari database yang dikirim dari PHP
    if (window.gunungData && window.gunungData.length > 0) {
      const mountainData = {}

      window.gunungData.forEach((gunung) => {
        const mountainId = gunung.nama_gunung
          .toLowerCase()
          .replace(/gunung\s+/i, "")
          .replace(/\s+/g, "")

        mountainData[mountainId] = {
          name: gunung.nama_gunung,
          price: Number.parseFloat(gunung.harga_tiket) || 0,
          tiket_id: gunung.id,
          routes: this.getRoutesForMountain(mountainId),
        }
      })

      console.log("Mountain Data Processed:", mountainData)
      return mountainData
    }

    // Fallback data jika database kosong
    console.warn("Using fallback mountain data - database might be empty")
    return {
      bromo: {
        name: "Gunung Bromo",
        price: 35000,
        tiket_id: 1,
        routes: [
          { id: "bromo-cemoro", name: "Jalur Cemoro Lawang", difficulty: "Mudah" },
          { id: "bromo-wonokitri", name: "Jalur Wonokitri (Pasuruan)", difficulty: "Menengah" },
        ],
      },
      merapi: {
        name: "Gunung Merapi",
        price: 25000,
        tiket_id: 2,
        routes: [{ id: "merapi-selo", name: "Jalur Selo (Boyolali)", difficulty: "Menantang" }],
      },
      semeru: {
        name: "Gunung Semeru",
        price: 45000,
        tiket_id: 3,
        routes: [{ id: "semeru-ranupani", name: "Jalur Ranu Pani", difficulty: "Sangat Menantang" }],
      },
      gede: {
        name: "Gunung Gede",
        price: 30000,
        tiket_id: 4,
        routes: [
          { id: "gede-cibodas", name: "Jalur Cibodas", difficulty: "Menengah" },
          { id: "gede-putri", name: "Jalur Gunung Putri", difficulty: "Menengah" },
        ],
      },
    }
  }

  initializeServicePrices() {
    // Gunakan data dari database yang dikirim dari PHP
    if (window.layananData && window.layananData.length > 0) {
      const servicePrices = {}

      window.layananData.forEach((layanan) => {
        servicePrices[layanan.nama_layanan] = {
          price: Number.parseFloat(layanan.harga_layanan) || 0,
          satuan: layanan.satuan || "/hari",
          deskripsi: layanan.deskripsi || "",
        }
      })

      console.log("Service Prices from Database:", servicePrices)
      return servicePrices
    }

    // Fallback data jika database kosong
    console.warn("Using fallback service data - database might be empty")
    return {
      guide: { price: 150000, satuan: "/hari", deskripsi: "Pemandu berpengalaman" },
      porter: { price: 100000, satuan: "/hari", deskripsi: "Bantuan membawa barang" },
      ojek: { price: 50000, satuan: "/orang", deskripsi: "Transportasi ke pos" },
      basecamp: { price: 75000, satuan: "/malam", deskripsi: "Tempat istirahat" },
    }
  }

  getRoutesForMountain(mountainId) {
    // Data jalur pendakian (bisa dipindah ke database juga jika diperlukan)
    const routes = {
      bromo: [
        { id: "bromo-cemoro", name: "Jalur Cemoro Lawang", difficulty: "Mudah" },
        { id: "bromo-wonokitri", name: "Jalur Wonokitri (Pasuruan)", difficulty: "Menengah" },
      ],
      merapi: [{ id: "merapi-selo", name: "Jalur Selo (Boyolali)", difficulty: "Menantang" }],
      semeru: [{ id: "semeru-ranupani", name: "Jalur Ranu Pani", difficulty: "Sangat Menantang" }],
      gede: [
        { id: "gede-cibodas", name: "Jalur Cibodas", difficulty: "Menengah" },
        { id: "gede-putri", name: "Jalur Gunung Putri", difficulty: "Menengah" },
      ],
    }

    return routes[mountainId] || []
  }

  init() {
    this.bindEvents()
    this.setMinDate()
    this.updateStepIndicator()
    this.updateSummary()
    this.setupNavigationWarning()
  }

  // Setup navigation warning for unsaved changes
  setupNavigationWarning() {
    // Add warning to all navigation links
    const navLinks = document.querySelectorAll(".nav-link, .mobile-nav-link")
    navLinks.forEach((link) => {
      if (!link.classList.contains("logout")) {
        link.addEventListener("click", (e) => {
          if (this.hasUnsavedChanges) {
            if (!confirm("Data yang sudah diisi akan hilang. Yakin ingin keluar?")) {
              e.preventDefault()
            }
          }
        })
      }
    })

    // Browser back/forward/refresh warning
    window.addEventListener("beforeunload", (e) => {
      if (this.hasUnsavedChanges) {
        e.preventDefault()
        e.returnValue = "Data yang sudah diisi akan hilang. Yakin ingin keluar?"
        return "Data yang sudah diisi akan hilang. Yakin ingin keluar?"
      }
    })
  }

  // Mark form as having unsaved changes
  markAsChanged() {
    this.hasUnsavedChanges = true
  }

  // Mark form as saved (remove unsaved changes warning)
  markAsSaved() {
    this.hasUnsavedChanges = false
  }

  bindEvents() {
    document.querySelectorAll(".mountain-option").forEach((option) =>
      option.addEventListener("click", (e) => {
        this.selectMountain(e)
        this.markAsChanged()
      }),
    )

    document.getElementById("routeSelection").addEventListener("click", (e) => {
      if (e.target.closest(".route-option")) {
        this.selectRoute(e)
        this.markAsChanged()
      }
    })

    document.querySelectorAll('input[name="services[]"]').forEach((checkbox) =>
      checkbox.addEventListener("change", (e) => {
        this.toggleService(e)
        this.markAsChanged()
      }),
    )

    document.getElementById("hikingDate").addEventListener("change", (e) => {
      this.handleDateOrParticipantChange(e)
      this.markAsChanged()
    })

    document.getElementById("participants").addEventListener("input", (e) => {
      this.handleDateOrParticipantChange(e)
      this.markAsChanged()
    })

    document.getElementById("agreeTerms").addEventListener("change", () => this.updateSubmitButtonState())

    // Navigation buttons
    document.getElementById("nextStep1").addEventListener("click", () => this.nextStep())
    document.getElementById("nextStep2").addEventListener("click", () => this.nextStep())
    document.getElementById("nextStep3").addEventListener("click", () => this.nextStep())
    document.getElementById("backStep1").addEventListener("click", () => {
      if (this.hasUnsavedChanges) {
        if (confirm("Data yang sudah diisi akan hilang. Yakin ingin keluar?")) {
          window.location.href = "dashboard.php"
        }
      } else {
        window.location.href = "dashboard.php"
      }
    })
    document.getElementById("backStep2").addEventListener("click", () => this.prevStep())
    document.getElementById("backStep3").addEventListener("click", () => this.prevStep())
    document.getElementById("backStep4").addEventListener("click", () => this.prevStep())
    document.getElementById("bookingForm").addEventListener("submit", (e) => this.submitForm(e))
  }

  setMinDate() {
    const today = new Date()
    const tomorrow = new Date(today)
    tomorrow.setDate(tomorrow.getDate() + 1)
    const minDate = tomorrow.toISOString().split("T")[0]
    document.getElementById("hikingDate").setAttribute("min", minDate)
  }

  // Step Logic
  goToStep(stepNumber) {
    this.currentStep = stepNumber
    document.querySelectorAll(".form-step").forEach((step) => step.classList.remove("active"))
    document.getElementById(`step-${this.currentStep}`).classList.add("active")
    this.updateStepIndicator()
  }

  nextStep() {
    if (this.currentStep < this.totalSteps) {
      this.goToStep(this.currentStep + 1)
    }
  }

  prevStep() {
    if (this.currentStep > 1) {
      this.goToStep(this.currentStep - 1)
    }
  }

  updateStepIndicator() {
    if (this.currentStep === 4) {
      this.updateSummary(true)
      setTimeout(() => {
        this.updateSubmitButtonState()
      }, 50)
    }
  }

  // Form Actions
  selectMountain(e) {
    const mountainOption = e.currentTarget
    const mountainId = mountainOption.dataset.mountain

    document.querySelectorAll(".mountain-option").forEach((opt) => opt.classList.remove("selected"))
    mountainOption.classList.add("selected")

    this.bookingData.mountain = mountainId
    this.bookingData.tiket_id = this.mountainData[mountainId].tiket_id
    this.bookingData.route = null // Reset route choice

    this.loadRoutes(mountainId)
    this.updateSummary()
    this.updateNextButtonState(1)
    this.updateNextButtonState(2)
  }

  loadRoutes(mountainId) {
    const routes = this.mountainData[mountainId]?.routes || []
    const container = document.getElementById("routeSelection")
    container.innerHTML = "" // Clear previous routes
    if (routes.length === 0) {
      container.innerHTML = "<p>Tidak ada jalur pendakian yang tersedia untuk gunung ini.</p>"
      return
    }

    routes.forEach((route) => {
      const difficultyClass = route.difficulty.toLowerCase().replace(" ", "-")
      const routeEl = document.createElement("div")
      routeEl.className = "route-option"
      routeEl.dataset.routeId = route.id
      routeEl.innerHTML = `
        <i class="fas fa-route route-icon"></i>
        <div class="route-details">
          <h4>${route.name}</h4>
          <span class="difficulty ${difficultyClass}">${route.difficulty}</span>
        </div>
        <div class="route-check"><i class="fas fa-check-circle"></i></div>
      `
      container.appendChild(routeEl)
    })
  }

  selectRoute(e) {
    const routeOption = e.target.closest(".route-option")
    if (!routeOption) return

    const routeId = routeOption.dataset.routeId
    this.bookingData.route = routeId

    document.querySelectorAll(".route-option").forEach((opt) => opt.classList.remove("selected"))
    routeOption.classList.add("selected")

    this.updateSummary()
    this.updateNextButtonState(2)
  }

  handleDateOrParticipantChange(e) {
    if (e.target.id === "hikingDate") {
      this.bookingData.date = e.target.value
    }
    if (e.target.id === "participants") {
      this.bookingData.participants = Number.parseInt(e.target.value, 10) || 1
    }
    this.updateSummary()
    this.updateNextButtonState(2)
  }

  toggleService(e) {
    const serviceId = e.target.value
    if (e.target.checked) {
      this.bookingData.services.push(serviceId)
      e.target.closest(".service-card").classList.add("selected")
    } else {
      this.bookingData.services = this.bookingData.services.filter((s) => s !== serviceId)
      e.target.closest(".service-card").classList.remove("selected")
    }
    this.updateSummary()
  }

  // Update UI
  updateNextButtonState(step) {
    const btn = document.getElementById(`nextStep${step}`)
    let enabled = false
    if (step === 1) {
      enabled = !!this.bookingData.mountain
    } else if (step === 2) {
      enabled = !!this.bookingData.route && !!this.bookingData.date && this.bookingData.participants > 0
    }
    btn.disabled = !enabled
  }

  updateSubmitButtonState() {
    const isAgreed = document.getElementById("agreeTerms").checked
    document.getElementById("submitBooking").disabled = !isAgreed
  }

  updateSummary(isFinal = false) {
    const sidebarContainer = document.getElementById("sidebarSummary")
    const finalContainer = document.getElementById("bookingSummary")
    const { mountain, route, date, participants, services } = this.bookingData
    const mountainInfo = mountain ? this.mountainData[mountain] : null

    if (!mountainInfo) {
      const placeholder = '<p class="placeholder">Pilih item untuk melihat ringkasan.</p>'
      sidebarContainer.innerHTML = placeholder
      if (finalContainer) finalContainer.innerHTML = placeholder
      return
    }

    // Calculations - MENGGUNAKAN DATA DARI DATABASE
    const ticketPrice = mountainInfo.price * participants
    let servicesPrice = 0
    const serviceDetails = []

    services.forEach((serviceId) => {
      const serviceInfo = this.servicePrices[serviceId]
      if (!serviceInfo) {
        console.warn(`Service ${serviceId} not found in database`)
        return
      }

      let price = serviceInfo.price
      let note = ""
      let jumlah_item_layanan = 1

      // Logika Kalkulasi berdasarkan satuan layanan - SAMA SEPERTI DI PHP
      if (serviceInfo.satuan === "/orang") {
        jumlah_item_layanan = participants
        price *= participants
        note = `(${participants} orang)`
      }
      // Untuk layanan per hari/malam, gunakan 1 hari/malam saja - TIDAK DIKALI
      if (serviceInfo.satuan === "/hari" || serviceInfo.satuan === "/malam") {
        jumlah_item_layanan = 1 // 1 hari/malam
        // price tetap sama (tidak dikali)
        note = `(1 hari/malam)`
      }

      servicesPrice += price
      serviceDetails.push({
        name: serviceId.charAt(0).toUpperCase() + serviceId.slice(1),
        price,
        note,
      })

      console.log(`Service ${serviceId}: base price ${serviceInfo.price}, calculated price ${price}, note: ${note}`)
    })

    const subtotal = ticketPrice + servicesPrice
    const tax = subtotal * this.taxRate
    const total = subtotal + tax

    // Find route name
    const routeInfo = mountainInfo.routes.find((r) => r.id === route)
    const routeName = routeInfo ? routeInfo.name : "<i>Belum dipilih</i>"

    // Build HTML
    let html = `
      <div class="summary-item"><span>Gunung:</span><strong>${mountainInfo.name}</strong></div>
      <div class="summary-item"><span>Jalur:</span><strong>${routeName}</strong></div>
      <div class="summary-item"><span>Tanggal:</span><strong>${date || "<i>Belum dipilih</i>"}</strong></div>
      <div class="summary-item"><span>Pendaki:</span><strong>${participants} orang</strong></div>
      <hr>
      <div class="summary-item"><span>Tiket (${participants} x ${this.formatCurrency(mountainInfo.price)}):</span><strong>${this.formatCurrency(ticketPrice)}</strong></div>
    `

    if (serviceDetails.length > 0) {
      html += `<h5>Layanan Tambahan:</h5>`
      serviceDetails.forEach((s) => {
        html += `<div class="summary-item"><span>- ${s.name} ${s.note}:</span><strong>${this.formatCurrency(s.price)}</strong></div>`
      })
    }

    html += `<hr>`

    const finalHtml =
      html +
      `
      <div class="summary-item total"><span>Subtotal:</span><strong>${this.formatCurrency(subtotal)}</strong></div>
      <div class="summary-item total"><span>Admin Fee (10%):</span><strong>${this.formatCurrency(tax)}</strong></div>
      <div class="summary-item grand-total"><span>Total Pembayaran:</span><strong>${this.formatCurrency(total)}</strong></div>
    `

    sidebarContainer.innerHTML = html
    if (isFinal && finalContainer) {
      finalContainer.innerHTML = finalHtml
    }

    console.log("Summary updated:", { ticketPrice, servicesPrice, subtotal, tax, total })
  }

  formatCurrency(value) {
    return `Rp ${new Intl.NumberFormat("id-ID").format(value)}`
  }

  submitForm(e) {
    e.preventDefault()

    // Mark as saved to prevent navigation warning
    this.markAsSaved()

    const form = document.getElementById("bookingForm")

    // Membuat input tersembunyi untuk data yang mungkin tidak ada sebagai field input standar
    const createHiddenInput = (name, value) => {
      let input = form.querySelector(`input[name="${name}"]`)
      if (!input) {
        input = document.createElement("input")
        input.type = "hidden"
        input.name = name
        form.appendChild(input)
      }
      input.value = value
    }

    // Data krusial yang harus dikirim ke backend
    createHiddenInput("tiket_id", this.bookingData.tiket_id)

    // Submit form secara nyata ke action yang sudah ditentukan di HTML
    form.submit()
  }
}

// Inisialisasi class saat halaman selesai dimuat
window.addEventListener("DOMContentLoaded", () => {
  new BookingForm()

  // Tambahkan handler konfirmasi logout
  const logoutBtn = document.getElementById("logoutBtn")
  const mobileLogoutBtn = document.getElementById("mobileLogoutBtn")

  function handleLogout(e) {
    e.preventDefault()
    if (confirm("Apakah Anda yakin ingin keluar?")) {
      window.location.href = "auth/logout.php"
    }
  }

  if (logoutBtn) logoutBtn.addEventListener("click", handleLogout)
  if (mobileLogoutBtn) mobileLogoutBtn.addEventListener("click", handleLogout)
})
