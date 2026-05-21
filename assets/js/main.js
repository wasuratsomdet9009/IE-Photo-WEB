// assets/js/main.js

/**
 * Show a toast notification
 * @param {string} message - The message to display
 * @param {string} type - 'success', 'danger', or 'info'
 */
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    // Icon based on type
    let icon = 'ℹ️';
    if (type === 'success') icon = '✅';
    if (type === 'danger') icon = '❌';

    toast.innerHTML = `<span>${icon}</span> <span>${message}</span>`;
    container.appendChild(toast);

    // Trigger reflow to enable transition
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    // Remove toast after 5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300); // Wait for transition to finish
    }, 5000);
}

// Global functions initialization
document.addEventListener('DOMContentLoaded', () => {
    // Example: Highlight active nav link
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-links a');
    navLinks.forEach(link => {
        if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href').replace('../', ''))) {
            // Apply active subtle style, could add a class here
            link.style.borderBottom = '2px solid var(--primary-color)';
        }
    });
});
