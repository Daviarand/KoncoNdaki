class ChatboxApp {
  constructor() {
    this.selectedCategory = null
    this.isTyping = false
    this.initializeElements()
    this.attachEventListeners()
  }

  initializeElements() {
    // Category selection elements
    this.categorySelection = document.getElementById("categorySelection")
    this.categoryButtons = document.querySelectorAll(".category-btn")

    // Chat elements
    this.chatBox = document.getElementById("chatBox")
    this.messagesContainer = document.getElementById("messagesContainer")
    this.categoryHeader = document.getElementById("categoryHeader")
    this.categoryBadge = document.getElementById("categoryBadge")
    this.changeCategoryBtn = document.getElementById("changeCategoryBtn")

    // Input elements
    this.inputArea = document.getElementById("inputArea")
    this.userInput = document.getElementById("userInput")
    this.sendButton = document.getElementById("sendButton")
  }

  attachEventListeners() {
    // Category selection
    this.categoryButtons.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const category = e.currentTarget.dataset.category
        this.selectCategory(category)
      })
    })

    // Change category button
    this.changeCategoryBtn.addEventListener("click", () => {
      this.resetToCategories()
    })

    // Send message
    this.sendButton.addEventListener("click", () => {
      this.sendMessage()
    })

    // Enter key to send message
    this.userInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter" && !this.userInput.disabled) {
        this.sendMessage()
      }
    })

    // Auto-resize input
    this.userInput.addEventListener("input", () => {
      this.toggleSendButton()
    })
  }

  selectCategory(category) {
    this.selectedCategory = category

    // Hide category selection
    this.categorySelection.style.display = "none"

    // Show chat interface
    this.chatBox.style.display = "flex"
    this.inputArea.style.display = "flex"

    // Update category badge
    const categoryNames = {
      rekomendasi: "Rekomendasi 💡",
      prediksi: "Prediksi 🔮",
    }
    this.categoryBadge.textContent = categoryNames[category]

    // Enable input
    this.userInput.disabled = false
    this.sendButton.disabled = false
    this.userInput.placeholder = `Tanyakan tentang ${category}...`

    // Add welcome message for selected category
    this.addWelcomeMessage(category)

    // Focus on input
    this.userInput.focus()
  }

  addWelcomeMessage(category) {
    const welcomeMessages = {
      rekomendasi: "Halo! Saya siap membantu memberikan rekomendasi terbaik untuk Anda. Apa yang ingin Anda tanyakan?",
      prediksi:
        "Halo! Saya siap membantu menganalisis dan memberikan prediksi. Silakan sampaikan apa yang ingin Anda prediksi!",
    }

    this.addMessage(welcomeMessages[category], "bot")
  }

  resetToCategories() {
    // Clear chat
    this.messagesContainer.innerHTML = ""

    // Reset state
    this.selectedCategory = null

    // Show category selection
    this.categorySelection.style.display = "block"

    // Hide chat interface
    this.chatBox.style.display = "none"
    this.inputArea.style.display = "none"

    // Disable input
    this.userInput.disabled = true
    this.sendButton.disabled = true
    this.userInput.value = ""
  }

  async sendMessage() {
    const message = this.userInput.value.trim()
    if (message === "" || this.isTyping) return

    // Add user message
    this.addMessage(message, "user")
    this.userInput.value = ""
    this.toggleSendButton()

    // Show typing indicator
    this.showTypingIndicator()

    try {
      // Prepare context-aware message
      const contextMessage = this.prepareContextMessage(message)

      // Send to backend
      const response = await this.sendToBackend(contextMessage)

      // Remove typing indicator and add response
      this.hideTypingIndicator()
      this.addMessage(response, "bot")
    } catch (error) {
      console.error("Error:", error)
      this.hideTypingIndicator()
      this.addMessage("Maaf, terjadi kesalahan. Silakan coba lagi.", "bot")
    }
  }

  prepareContextMessage(message) {
    // Add category context to the message
    const contextPrefixes = {
      rekomendasi: "Sebagai asisten rekomendasi, ",
      prediksi: "Sebagai asisten prediksi, ",
    }

    return contextPrefixes[this.selectedCategory] + message
  }

  async sendToBackend(message) {
    // Send to the same PHP file
    const response = await fetch("chatbox.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `message=${encodeURIComponent(message)}&category=${encodeURIComponent(this.selectedCategory)}`,
    })

    if (!response.ok) {
      throw new Error("Network response was not ok")
    }

    const data = await response.json()
    return data.response || "Maaf, tidak ada respons dari server."
  }

  addMessage(message, type) {
    const messageDiv = document.createElement("div")
    messageDiv.classList.add("message", type)
    messageDiv.textContent = message

    this.messagesContainer.appendChild(messageDiv)
    this.scrollToBottom()
  }

  showTypingIndicator() {
    this.isTyping = true
    const typingDiv = document.createElement("div")
    typingDiv.classList.add("message", "bot", "typing-indicator")
    typingDiv.id = "typingIndicator"

    typingDiv.innerHTML = `
            <span>AI sedang mengetik</span>
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `

    this.messagesContainer.appendChild(typingDiv)
    this.scrollToBottom()
  }

  hideTypingIndicator() {
    this.isTyping = false
    const typingIndicator = document.getElementById("typingIndicator")
    if (typingIndicator) {
      typingIndicator.remove()
    }
  }

  toggleSendButton() {
    const hasText = this.userInput.value.trim().length > 0
    this.sendButton.disabled = !hasText || this.isTyping
  }

  scrollToBottom() {
    this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight
  }
}

// Initialize the chatbox when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  new ChatboxApp()
})
