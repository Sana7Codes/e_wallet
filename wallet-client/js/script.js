// Mobile Menu Toggle
const menuToggle = document.querySelector('.menu-toggle');
const navGroup = document.querySelector('.nav-group');

menuToggle.addEventListener('click', () => {
    navGroup.classList.toggle('active');
    menuToggle.classList.toggle('active');
});

// Dropdown Interactions
const dropdowns = document.querySelectorAll('.dropdown');

dropdowns.forEach(dropdown => {
    dropdown.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            dropdown.querySelector('.dropdown-menu').classList.toggle('active');
        }
    });
});

// Transaction Form Handling
const forms = document.querySelectorAll('.transaction-form');

forms.forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const transactionData = Object.fromEntries(formData.entries());
        
        try {
            setLoading(true);
            
            // Input sanitization
            const sanitizedData = sanitizeInputs(transactionData);
            
            // API Request
            const response = await axios.post('/api/transaction', sanitizedData, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCSRFToken()
                }
            });
            
            if (response.data.success) {
                showNotification('Transaction successful!', 'success');
                form.reset();
                updateBalance(response.data.newBalance);
            }
        } catch (error) {
            handleTransactionError(error);
        } finally {
            setLoading(false);
        }
    });
});

// Security Functions
function sanitizeInputs(data) {
    return Object.entries(data).reduce((acc, [key, value]) => {
        acc[key] = typeof value === 'string' ? value.replace(/<[^>]*>?/gm, '') : value;
        return acc;
    }, {});
}

function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

// Error Handling
function handleTransactionError(error) {
    console.error('Transaction Error:', error);
    
    const errorMessage = error.response?.data?.message || 
                        error.message || 
                        'Transaction failed. Please try again.';
    
    showNotification(errorMessage, 'error');
    
    if (error.response?.status === 401) {
        // Handle unauthorized access
        window.location.href = '/login';
    }
}

// Loading State
function setLoading(isLoading) {
    const overlay = document.querySelector('.loading-overlay');
    overlay.style.display = isLoading ? 'flex' : 'none';
}

// Balance Update
function updateBalance(newBalance) {
    const balanceElement = document.querySelector('.balance-amount');
    balanceElement.textContent = `$${parseFloat(newBalance).toFixed(2)}`;
}

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// API Communication
async function fetchInitialData() {
    try {
        const response = await axios.get('/api/user-data', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        updateBalance(response.data.balance);
        // Update other user data as needed
        
    } catch (error) {
        handleTransactionError(error);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchInitialData();
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('active');
            });
        }
    });
});