// Dashboard Pengelola JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard
    initializeDashboard();
    initializeCharts();
    initializeEventListeners();
});

function initializeDashboard() {
    // Set active section based on hash or default to dashboard
    const hash = window.location.hash.substring(1) || 'dashboard';
    showSection(hash);
    
    // Update active menu item
    const activeMenuItem = document.querySelector(`[data-category="${hash}"]`);
    if (activeMenuItem) {
        updateActiveMenuItem(activeMenuItem);
    }
}

function initializeEventListeners() {
    // Menu item clicks
    const menuItems = document.querySelectorAll('.category-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            showSection(category);
            updateActiveMenuItem(this);
            
            // Update URL hash
            window.location.hash = category;
        });
    });

    // Search functionality
    const searchInputs = document.querySelectorAll('input[type="text"][placeholder*="Cari"]');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            handleSearch(this.value, this.closest('section').id);
        });
    });

    // Form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            handleFormSubmission(e, this);
        });
    });

    // Action buttons
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            handleActionButton(this);
        });
    });
}

function showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    // Show target section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }
}

function updateActiveMenuItem(clickedItem) {
    // Remove active class from all menu items
    const menuItems = document.querySelectorAll('.category-item');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });

    // Add active class to clicked item
    clickedItem.classList.add('active');
}

function handleSearch(query, sectionId) {
    console.log(`Searching for "${query}" in section ${sectionId}`);
    
    // Implement search logic based on section
    switch(sectionId) {
        case 'kelolaBooking':
            searchBookings(query);
            break;
        case 'dataPendaki':
            searchPendaki(query);
            break;
        default:
            console.log('Search not implemented for this section');
    }
}

function searchBookings(query) {
    const rows = document.querySelectorAll('#kelolaBooking .modern-table tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const isVisible = text.includes(query.toLowerCase());
        row.style.display = isVisible ? '' : 'none';
    });
}

function searchPendaki(query) {
    // Similar implementation for pendaki search
    console.log(`Searching pendaki for: ${query}`);
}

function handleFormSubmission(e, form) {
    // Handle different form types
    const formClass = form.className;
    
    if (formClass.includes('modern-form')) {
        // Handle modern form submission
        console.log('Submitting modern form');
        
        // Add loading state
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.classList.add('loading');
            submitButton.disabled = true;
        }
        
        // Simulate form processing
        setTimeout(() => {
            if (submitButton) {
                submitButton.classList.remove('loading');
                submitButton.disabled = false;
            }
            
            // Show success message
            showNotification('Form berhasil disubmit!', 'success');
        }, 2000);
    }
}

function handleActionButton(button) {
    const action = button.className;
    
    if (action.includes('btn-verify')) {
        handleVerifyAction(button);
    } else if (action.includes('btn-edit')) {
        handleEditAction(button);
    } else if (action.includes('btn-delete')) {
        handleDeleteAction(button);
    } else if (action.includes('btn-save')) {
        handleSaveAction(button);
    }
}

function handleVerifyAction(button) {
    if (confirm('Apakah Anda yakin ingin memverifikasi booking ini?')) {
        // Add loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        // Simulate verification process
        setTimeout(() => {
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.disabled = false;
            button.classList.remove('btn-verify');
            button.classList.add('btn-verified');
            
            // Update status badge in the same row
            const row = button.closest('tr');
            const statusBadge = row.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.textContent = 'Confirmed';
                statusBadge.className = 'status-badge confirmed';
            }
            
            showNotification('Booking berhasil diverifikasi!', 'success');
        }, 1500);
    }
}

function handleEditAction(button) {
    const row = button.closest('tr');
    const cells = row.querySelectorAll('td');
    
    // Toggle edit mode
    if (button.classList.contains('editing')) {
        // Save changes
        button.innerHTML = '<i class="fas fa-edit"></i>';
        button.classList.remove('editing');
        showNotification('Perubahan berhasil disimpan!', 'success');
    } else {
        // Enter edit mode
        button.innerHTML = '<i class="fas fa-save"></i>';
        button.classList.add('editing');
        showNotification('Mode edit diaktifkan', 'info');
    }
}

function handleDeleteAction(button) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        const row = button.closest('tr');
        
        // Add fade out animation
        row.style.transition = 'opacity 0.3s ease';
        row.style.opacity = '0';
        
        setTimeout(() => {
            row.remove();
            showNotification('Item berhasil dihapus!', 'success');
        }, 300);
    }
}

function handleSaveAction(button) {
    // Add loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Simulate save process
    setTimeout(() => {
        button.innerHTML = '<i class="fas fa-save"></i>';
        button.disabled = false;
        showNotification('Data berhasil disimpan!', 'success');
    }, 1000);
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getNotificationColor(type)};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function getNotificationIcon(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        default: return 'info-circle';
    }
}

function getNotificationColor(type) {
    switch(type) {
        case 'success': return 'linear-gradient(135deg, #16a34a, #15803d)';
        case 'error': return 'linear-gradient(135deg, #ef4444, #dc2626)';
        case 'warning': return 'linear-gradient(135deg, #f59e0b, #d97706)';
        default: return 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
    }
}

function initializeCharts() {
    // Initialize trend chart
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Jumlah Pendaki',
                    data: [850, 920, 1100, 1050, 1200, 1247],
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // This is key!
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            color: '#f3f4f6'
                        }
                    }
                }
            }
        });
    }
    
    // Initialize path distribution chart
    const pathCtx = document.getElementById('pathChart');
    if (pathCtx) {
        new Chart(pathCtx, {
            type: 'doughnut',
            data: {
                labels: ['Jalur Selo', 'Jalur Babadan', 'Jalur Kaliurang', 'Jalur Kinahrejo', 'Jalur Tlogo'],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
                    backgroundColor: [
                        '#16a34a',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // This is key!
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
}

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

// Export functions for global access
window.dashboardPengelola = {
    showSection,
    updateActiveMenuItem,
    showNotification,
    formatCurrency,
    formatDate
};