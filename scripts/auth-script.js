// Auth Script for Login and Register Pages
document.addEventListener('DOMContentLoaded', function() {
    
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const toggleRegisterPassword = document.getElementById('toggleRegisterPassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
    
    if (toggleRegisterPassword) {
        toggleRegisterPassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('registerPassword');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
    
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('confirmPassword');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
    
    // Password strength indicator (for register page)
    const registerPassword = document.getElementById('registerPassword');
    const passwordStrength = document.getElementById('passwordStrength');
    
    if (registerPassword && passwordStrength) {
        registerPassword.addEventListener('input', function() {
            const password = this.value;
            const strengthFill = passwordStrength.querySelector('.strength-fill');
            const strengthText = passwordStrength.querySelector('.strength-text');
            
            let strength = 0;
            let strengthLabel = 'Password lemah';
            let strengthColor = '#ef4444';
            
            if (password.length >= 6) strength += 25;
            if (password.match(/[a-z]/)) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            
            if (strength >= 100) {
                strengthLabel = 'Password sangat kuat';
                strengthColor = '#16a34a';
            } else if (strength >= 75) {
                strengthLabel = 'Password kuat';
                strengthColor = '#22c55e';
            } else if (strength >= 50) {
                strengthLabel = 'Password sedang';
                strengthColor = '#eab308';
            } else if (strength >= 25) {
                strengthLabel = 'Password lemah';
                strengthColor = '#f97316';
            } else {
                strengthLabel = 'Password sangat lemah';
                strengthColor = '#ef4444';
            }
            
            strengthFill.style.width = strength + '%';
            strengthFill.style.backgroundColor = strengthColor;
            strengthText.textContent = strengthLabel;
            strengthText.style.color = strengthColor;
        });
    }
    
    // Form validation for register page
    const registerForm = document.querySelector('.register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const terms = document.getElementById('terms');
            
            // Clear previous error states
            clearErrors();
            
            let hasErrors = false;
            
            // Check password match
            if (password !== confirmPassword) {
                showError('confirmPassword', 'Konfirmasi password tidak cocok');
                hasErrors = true;
            }
            
            // Check password length
            if (password.length < 6) {
                showError('registerPassword', 'Password minimal 6 karakter');
                hasErrors = true;
            }
            
            // Check terms agreement
            if (!terms.checked) {
                showError('terms', 'Anda harus menyetujui Syarat & Ketentuan');
                hasErrors = true;
            }
            
            if (hasErrors) {
                e.preventDefault();
            }
        });
    }
    
    // Form validation for login page
    const loginForm = document.querySelector('.login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Clear previous error states
            clearErrors();
            
            let hasErrors = false;
            
            // Check email format
            if (email && !isValidEmail(email)) {
                showError('email', 'Format email tidak valid');
                hasErrors = true;
            }
            
            // Check required fields
            if (!email.trim()) {
                showError('email', 'Email harus diisi');
                hasErrors = true;
            }
            
            if (!password.trim()) {
                showError('password', 'Password harus diisi');
                hasErrors = true;
            }
            
            if (hasErrors) {
                e.preventDefault();
            }
        });
    }
    
    // Helper functions
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (field) {
            const inputGroup = field.closest('.input-group');
            if (inputGroup) {
                inputGroup.classList.add('error');
                
                // Remove existing error message
                const existingError = inputGroup.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                // Add new error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                inputGroup.parentNode.appendChild(errorDiv);
            }
        }
    }
    
    function clearErrors() {
        // Remove error classes
        document.querySelectorAll('.input-group.error').forEach(group => {
            group.classList.remove('error');
        });
        
        // Remove error messages
        document.querySelectorAll('.error-message').forEach(error => {
            error.remove();
        });
    }
    
    // Social login buttons (placeholder functionality)
    const socialButtons = document.querySelectorAll('.btn-social');
    socialButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.classList.contains('google') ? 'Google' : 'Facebook';
            alert(`Fitur login dengan ${platform} akan segera tersedia!`);
        });
    });
    
    // Remember me functionality (placeholder)
    const rememberCheckbox = document.getElementById('remember');
    if (rememberCheckbox) {
        // Check if there's a saved preference
        const savedRemember = localStorage.getItem('rememberMe');
        if (savedRemember === 'true') {
            rememberCheckbox.checked = true;
        }
        
        rememberCheckbox.addEventListener('change', function() {
            localStorage.setItem('rememberMe', this.checked);
        });
    }
    
    // Newsletter checkbox functionality
    const newsletterCheckbox = document.getElementById('newsletter');
    if (newsletterCheckbox) {
        newsletterCheckbox.addEventListener('change', function() {
            if (this.checked) {
                console.log('User opted in for newsletter');
            } else {
                console.log('User opted out of newsletter');
            }
        });
    }
    
    // Auto-hide success/error messages after 5 seconds
    const messages = document.querySelectorAll('.success-message, .error-message');
    messages.forEach(message => {
        setTimeout(() => {
            if (message.parentNode) {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    if (message.parentNode) {
                        message.remove();
                    }
                }, 300);
            }
        }, 5000);
    });
    
    // Add smooth transitions to messages
    const style = document.createElement('style');
    style.textContent = `
        .success-message, .error-message {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .input-group.error input {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .error-message i {
            color: #ef4444;
        }
    `;
    document.head.appendChild(style);
}); 