class ChatboxApp {
  constructor() {
    this.selectedCategory = null
    this.isTyping = false
    this.initializeElements()
    this.attachEventListeners()
    this.initializeSuggestions()
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
    this.suggestionChips = document.querySelectorAll(".suggestion-chip")
  }

  attachEventListeners() {
    // Category selection with enhanced animations
    this.categoryButtons.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const category = e.currentTarget.dataset.category
        this.selectCategoryWithAnimation(category)
      })

      // Add hover sound effect (optional)
      btn.addEventListener("mouseenter", () => {
        btn.style.transform = "translateY(-8px) scale(1.02)"
      })

      btn.addEventListener("mouseleave", () => {
        btn.style.transform = "translateY(0) scale(1)"
      })
    })

    // Change category button
    this.changeCategoryBtn.addEventListener("click", () => {
      this.resetToCategoriesWithAnimation()
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

    // Auto-resize input and toggle send button
    this.userInput.addEventListener("input", () => {
      this.toggleSendButton()
      this.updateSuggestions()
    })

    // Input focus effects
    this.userInput.addEventListener("focus", () => {
      this.inputArea.style.transform = "translateY(-2px)"
    })

    this.userInput.addEventListener("blur", () => {
      this.inputArea.style.transform = "translateY(0)"
    })
  }

  initializeSuggestions() {
    this.suggestionChips.forEach((chip) => {
      chip.addEventListener("click", () => {
        const text = chip.dataset.text
        this.userInput.value = text
        this.toggleSendButton()
        this.userInput.focus()

        // Add click animation
        chip.style.transform = "scale(0.95)"
        setTimeout(() => {
          chip.style.transform = "scale(1)"
        }, 150)
      })
    })
  }

  selectCategoryWithAnimation(category) {
    this.selectedCategory = category

    // Add loading state
    this.categorySelection.style.opacity = "0.5"
    this.categorySelection.style.pointerEvents = "none"

    setTimeout(() => {
      // Hide category selection with fade out
      this.categorySelection.style.display = "none"

      // Show chat interface with fade in
      this.chatBox.style.display = "flex"
      this.inputArea.style.display = "block"

      // Update category badge with animation
      const categoryNames = {
        rekomendasi: "💡 Rekomendasi",
        prediksi: "🔮 Prediksi",
      }
      this.categoryBadge.textContent = categoryNames[category]

      // Enable input with animation
      this.userInput.disabled = false
      this.sendButton.disabled = false
      this.userInput.placeholder = `Tanyakan tentang ${category}...`

      // Add welcome message for selected category
      this.addWelcomeMessage(category)

      // Focus on input with delay
      setTimeout(() => {
        this.userInput.focus()
        this.updateSuggestionsByCategory(category)
      }, 300)

      // Reset opacity
      this.categorySelection.style.opacity = "1"
      this.categorySelection.style.pointerEvents = "auto"
    }, 300)
  }

  updateSuggestionsByCategory(category) {
    const suggestions = {
      rekomendasi: [
        { text: "Rekomendasi gunung untuk pemula", display: "Gunung pemula" },
        { text: "Tips persiapan mendaki gunung", display: "Tips persiapan" },
        { text: "Peralatan wajib pendakian", display: "Peralatan wajib" },
      ],
      prediksi: [
        { text: "Prediksi cuaca untuk mendaki", display: "Prediksi cuaca" },
        { text: "Estimasi waktu pendakian", display: "Estimasi waktu" },
        { text: "Analisis kondisi jalur", display: "Kondisi jalur" },
      ],
    }

    const categoryChips = suggestions[category] || []
    this.suggestionChips.forEach((chip, index) => {
      if (categoryChips[index]) {
        chip.dataset.text = categoryChips[index].text
        chip.textContent = categoryChips[index].display
        chip.style.display = "inline-block"
      } else {
        chip.style.display = "none"
      }
    })
  }

  addWelcomeMessage(category) {
    const welcomeMessages = {
      rekomendasi:
        "🏔️ Halo! Saya siap membantu memberikan rekomendasi terbaik untuk petualangan outdoor Anda. Apa yang ingin Anda tanyakan?",
      prediksi:
        "🔮 Halo! Saya siap membantu menganalisis dan memberikan prediksi untuk perencanaan perjalanan Anda. Silakan sampaikan apa yang ingin Anda prediksi!",
    }

    setTimeout(() => {
      this.addMessage(welcomeMessages[category], "bot")
    }, 500)
  }

  resetToCategoriesWithAnimation() {
    // Add fade out animation
    this.chatBox.style.opacity = "0"
    this.inputArea.style.opacity = "0"

    setTimeout(() => {
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

      // Reset opacity
      this.chatBox.style.opacity = "1"
      this.inputArea.style.opacity = "1"
    }, 300)
  }

  async sendMessage() {
    const message = this.userInput.value.trim()
    if (message === "" || this.isTyping) return

    // Add send button animation
    this.sendButton.style.transform = "scale(0.9)"
    setTimeout(() => {
      this.sendButton.style.transform = "scale(1)"
    }, 150)

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
      this.addMessage("🚫 Maaf, terjadi kesalahan. Silakan coba lagi.", "bot")
    }
  }

  prepareContextMessage(message) {
    // Add category context to the message
    const contextPrefixes = {
      rekomendasi: "Sebagai asisten rekomendasi untuk aktivitas outdoor dan pendakian gunung, ",
      prediksi: "Sebagai asisten prediksi untuk perencanaan perjalanan dan aktivitas outdoor, ",
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
  
  // TAMBAHAN: Format message untuk bot dengan line breaks
  if (type === "bot") {
    // Replace \n\n dengan <br><br> untuk double line break
    // Replace \n dengan <br> untuk single line break
    const formattedMessage = message
      .replace(/\n\n/g, '<br><br>')
      .replace(/\n/g, '<br>')
    messageDiv.innerHTML = formattedMessage
  } else {
    messageDiv.textContent = message
  }

  // Add message with animation
  messageDiv.style.opacity = "0"
  messageDiv.style.transform = "translateY(20px)"

  this.messagesContainer.appendChild(messageDiv)

  // Trigger animation
  setTimeout(() => {
    messageDiv.style.opacity = "1"
    messageDiv.style.transform = "translateY(0)"
  }, 50)

  this.scrollToBottom()
}

  showTypingIndicator() {
    this.isTyping = true
    const typingDiv = document.createElement("div")
    typingDiv.classList.add("message", "bot", "typing-indicator")
    typingDiv.id = "typingIndicator"

    typingDiv.innerHTML = `
            <span>🤖 AI sedang mengetik</span>
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
      typingIndicator.style.opacity = "0"
      setTimeout(() => {
        typingIndicator.remove()
      }, 300)
    }
  }

  toggleSendButton() {
    const hasText = this.userInput.value.trim().length > 0
    this.sendButton.disabled = !hasText || this.isTyping

    if (hasText && !this.isTyping) {
      this.sendButton.style.background = "var(--gradient-primary)"
      this.sendButton.style.transform = "scale(1)"
    } else {
      this.sendButton.style.background = "var(--border-color)"
      this.sendButton.style.transform = "scale(0.95)"
    }
  }

  updateSuggestions() {
    const inputValue = this.userInput.value.toLowerCase()

    // Hide/show suggestions based on input
    this.suggestionChips.forEach((chip) => {
      const chipText = chip.textContent.toLowerCase()
      if (inputValue && chipText.includes(inputValue)) {
        chip.style.opacity = "1"
        chip.style.transform = "scale(1.05)"
      } else {
        chip.style.opacity = "0.7"
        chip.style.transform = "scale(1)"
      }
    })
  }

  scrollToBottom() {
    setTimeout(() => {
      this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight
    }, 100)
  }
}

// Initialize the chatbox when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  new ChatboxApp()

  // Add page load animation
  document.body.style.opacity = "0"
  setTimeout(() => {
    document.body.style.transition = "opacity 0.5s ease"
    document.body.style.opacity = "1"
  }, 100)
})
