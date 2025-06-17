// Auth Script for Login and Register Pages
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Password strength checker
    const passwordInput = document.getElementById('registerPassword');
    const strengthBar = document.querySelector('.strength-fill');
    const strengthText = document.querySelector('.strength-text');
    
    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            updatePasswordStrength(strength, strengthBar, strengthText);
        });
    }

    // Form validation
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLoginSubmit);
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegisterSubmit);
    }

    // Social login handlers
    const socialButtons = document.querySelectorAll('.btn-social');
    socialButtons.forEach(button => {
        button.addEventListener('click', function() {
            const provider = this.classList.contains('google') ? 'Google' : 'Facebook';
            showNotification(`Login dengan ${provider} akan segera tersedia!`, 'info');
        });
    });

    // Real-time validation
    const inputs = document.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateInput(this);
        });
        
        input.addEventListener('input', function() {
            clearInputError(this);
        });
    });

    // Confirm password validation
    const confirmPasswordInput = document.getElementById('confirmPassword');
    if (confirmPasswordInput && passwordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            validatePasswordMatch(passwordInput, this);
        });
    }
});

// Password strength calculation
function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength += 25;
    if (password.match(/[a-z]/)) strength += 25;
    if (password.match(/[A-Z]/)) strength += 25;
    if (password.match(/[0-9]/)) strength += 12.5;
    if (password.match(/[^a-zA-Z0-9]/)) strength += 12.5;
    
    return Math.min(strength, 100);
}

// Update password strength display
function updatePasswordStrength(strength, strengthBar, strengthText) {
    strengthBar.style.width = strength + '%';
    
    if (strength < 30) {
        strengthBar.style.backgroundColor = '#ef4444';
        strengthText.textContent = 'Password lemah';
        strengthText.style.color = '#ef4444';
    } else if (strength < 60) {
        strengthBar.style.backgroundColor = '#f59e0b';
        strengthText.textContent = 'Password sedang';
        strengthText.style.color = '#f59e0b';
    } else if (strength < 80) {
        strengthBar.style.backgroundColor = '#10b981';
        strengthText.textContent = 'Password kuat';
        strengthText.style.color = '#10b981';
    } else {
        strengthBar.style.backgroundColor = '#16a34a';
        strengthText.textContent = 'Password sangat kuat';
        strengthText.style.color = '#16a34a';
    }
}

// Input validation
function validateInput(input) {
    const inputGroup = input.parentElement;
    const value = input.value.trim();
    
    // Remove existing error
    clearInputError(input);
    
    // Required field validation
    if (input.hasAttribute('required') && !value) {
        showInputError(input, 'Field ini wajib diisi');
        return false;
    }
    
    // Email validation
    if (input.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showInputError(input, 'Format email tidak valid');
            return false;
        }
    }
    
    // Phone validation
    if (input.type === 'tel' && value) {
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (!phoneRegex.test(value) || value.length < 10) {
            showInputError(input, 'Nomor telepon tidak valid');
            return false;
        }
    }
    
    // Password validation
    if (input.type === 'password' && input.id === 'registerPassword' && value) {
        if (value.length < 8) {
            showInputError(input, 'Password minimal 8 karakter');
            return false;
        }
    }
    
    // Show success state
    inputGroup.classList.add('success');
    return true;
}

// Password match validation
function validatePasswordMatch(passwordInput, confirmInput) {
    const inputGroup = confirmInput.parentElement;
    
    clearInputError(confirmInput);
    
    if (confirmInput.value && passwordInput.value !== confirmInput.value) {
        showInputError(confirmInput, 'Password tidak cocok');
        return false;
    }
    
    if (confirmInput.value && passwordInput.value === confirmInput.value) {
        inputGroup.classList.add('success');
    }
    
    return true;
}

// Show input error
function showInputError(input, message) {
    const inputGroup = input.parentElement;
    const formGroup = inputGroup.parentElement;
    
    inputGroup.classList.add('error');
    inputGroup.classList.remove('success');
    
    // Remove existing error message
    const existingError = formGroup.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    formGroup.appendChild(errorDiv);
}

// Clear input error
function clearInputError(input) {
    const inputGroup = input.parentElement;
    const formGroup = inputGroup.parentElement;
    
    inputGroup.classList.remove('error');
    
    const errorMessage = formGroup.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

// Handle login form submission
function handleLoginSubmit(e) {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('.btn-submit');
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // Validate inputs
    let isValid = true;
    const inputs = e.target.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
        if (!validateInput(input)) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        showNotification('Mohon perbaiki kesalahan pada form', 'error');
        return;
    }
    
    // Show loading state
    setButtonLoading(submitButton, true);
    
    // Simulate API call
    setTimeout(() => {
        setButtonLoading(submitButton, false);
        
        // For demo purposes, create user data and mark as logged in
        const userData = {
            firstName: 'John',
            lastName: 'Doe',
            email: email,
            phone: '+62 812-3456-7890',
            birthDate: '1990-01-15',
            address: 'Jl. Contoh No. 123, Jakarta Selatan, DKI Jakarta'
        };
        
        // Save user data to localStorage
        localStorage.setItem('userData', JSON.stringify(userData));
        localStorage.setItem('userLoggedIn', 'true');
        
        showNotification('Login berhasil! Mengalihkan...', 'success');
        
        // Redirect to dashboard after delay
        setTimeout(() => {
            window.location.href = 'dashboard.html';
        }, 1500);
    }, 2000);
}

// Handle register form submission
function handleRegisterSubmit(e) {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('.btn-submit');
    const termsCheckbox = document.getElementById('terms');
    
    // Validate inputs
    let isValid = true;
    const inputs = e.target.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
        if (!validateInput(input)) {
            isValid = false;
        }
    });
    
    // Validate password match
    const passwordInput = document.getElementById('registerPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    
    if (!validatePasswordMatch(passwordInput, confirmPasswordInput)) {
        isValid = false;
    }
    
    // Check terms agreement
    if (!termsCheckbox.checked) {
        showNotification('Anda harus menyetujui syarat dan ketentuan', 'error');
        isValid = false;
    }
    
    if (!isValid) {
        showNotification('Mohon perbaiki kesalahan pada form', 'error');
        return;
    }
    
    // Show loading state
    setButtonLoading(submitButton, true);
    
    // Get form data
    const formData = new FormData(e.target);
    const userData = {};
    
    for (let [key, value] of formData.entries()) {
        if (key !== 'password' && key !== 'confirmPassword') {
            userData[key] = value;
        }
    }
    
    // Add default values
    userData.birthDate = '1990-01-15';
    userData.address = 'Alamat belum diisi';
    
    // Simulate API call
    setTimeout(() => {
        setButtonLoading(submitButton, false);
        
        // Save user data to localStorage
        localStorage.setItem('userData', JSON.stringify(userData));
        
        showNotification('Registrasi berhasil! Silakan login dengan akun Anda.', 'success');
        
        // Redirect to login page after delay
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 2000);
    }, 2000);
}

// Set button loading state
function setButtonLoading(button, isLoading) {
    if (isLoading) {
        button.classList.add('loading');
        button.disabled = true;
    } else {
        button.classList.remove('loading');
        button.disabled = false;
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icon = type === 'success' ? 'fa-check-circle' : 
                 type === 'error' ? 'fa-exclamation-circle' : 
                 'fa-info-circle';
    
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
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
        border-left: 4px solid ${type === 'success' ? '#16a34a' : type === 'error' ? '#ef4444' : '#3b82f6'};
        animation: slideIn 0.3s ease;
    `;
    
    // Add animation styles
    const style = document.createElement('style');
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
            color: ${type === 'success' ? '#16a34a' : type === 'error' ? '#ef4444' : '#3b82f6'};
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
    `;
    
    document.head.appendChild(style);
    document.body.appendChild(notification);
    
    // Close button functionality
    const closeButton = notification.querySelector('.notification-close');
    closeButton.addEventListener('click', () => {
        notification.remove();
        style.remove();
    });
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
            style.remove();
        }
    }, 5000);
}

// Smooth animations on page load
window.addEventListener('load', function() {
    const authForm = document.querySelector('.auth-form');
    const authImage = document.querySelector('.auth-image');
    
    if (authForm) {
        authForm.style.opacity = '0';
        authForm.style.transform = 'translateY(20px)';
        authForm.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            authForm.style.opacity = '1';
            authForm.style.transform = 'translateY(0)';
        }, 100);
    }
    
    if (authImage) {
        authImage.style.opacity = '0';
        authImage.style.transform = 'translateX(-20px)';
        authImage.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            authImage.style.opacity = '1';
            authImage.style.transform = 'translateX(0)';
        }, 200);
    }
});

// Handle back navigation
window.addEventListener('popstate', function(e) {
    // Handle browser back button if needed
});

// Form auto-save (optional feature)
function autoSaveForm() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input:not([type="password"])');
        
        inputs.forEach(input => {
            // Load saved data
            const savedValue = localStorage.getItem(`form_${input.name}`);
            if (savedValue && input.type !== 'password') {
                input.value = savedValue;
            }
            
            // Save data on input
            input.addEventListener('input', function() {
                if (this.type !== 'password') {
                    localStorage.setItem(`form_${this.name}`, this.value);
                }
            });
        });
        
        // Clear saved data on successful submission
        form.addEventListener('submit', function() {
            inputs.forEach(input => {
                localStorage.removeItem(`form_${input.name}`);
            });
        });
    });
}

// Initialize auto-save (uncomment if needed)
// autoSaveForm();