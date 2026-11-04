/* ═══════════════════════════════════════════════════
   TOAST NOTIFICATION SYSTEM — CodeTide
   Usage:
   - Toast.success('Title', 'Message')
   - Toast.error('Title', 'Message')
   - Toast.warning('Title', 'Message')
   - Toast.info('Title', 'Message')
═══════════════════════════════════════════════════ */

const Toast = (function() {
    let container = null;
    let toastIdCounter = 0;

    // PxlKit icon mapping to actual constants
    const ICON_MAP = {
        success: () => typeof PXL_CHECK !== 'undefined' ? PXL_CHECK : null,
        error: () => typeof PXL_BUG !== 'undefined' ? PXL_BUG : null,
        warning: () => typeof PXL_LIGHTNING !== 'undefined' ? PXL_LIGHTNING : null,
        info: () => typeof PXL_SPARKLE_STAR !== 'undefined' ? PXL_SPARKLE_STAR : null
    };

    /**
     * Initialize toast container
     */
    function init() {
        if (container) return;
        
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    /**
     * Create toast element
     */
    function createToast(type, title, message, duration = 4000) {
        init();

        const toastId = `toast-${++toastIdCounter}`;
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.id = toastId;
        toast.setAttribute('role', 'alert');

        // Build toast HTML
        toast.innerHTML = `
            <div class="toast-icon" id="toast-icon-${toastIdCounter}"></div>
            <div class="toast-content">
                <div class="toast-title">${escapeHtml(title)}</div>
                <div class="toast-message">${escapeHtml(message)}</div>
            </div>
            <button class="toast-close" aria-label="Close">×</button>
            ${duration > 0 ? '<div class="toast-progress"></div>' : ''}
        `;

        // Add to container
        container.appendChild(toast);

        // Render PxlKit icon
        setTimeout(() => {
            if (typeof PXLKIT !== 'undefined') {
                const iconContainer = document.getElementById(`toast-icon-${toastIdCounter}`);
                const getIcon = ICON_MAP[type];
                
                if (iconContainer && getIcon) {
                    const iconData = getIcon();
                    if (iconData) {
                        const canvas = PXLKIT.render(iconData, 3);
                        iconContainer.appendChild(canvas);
                    }
                }
            }
        }, 10);

        // Close button handler
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => dismissToast(toastId));

        // Click anywhere to dismiss
        toast.addEventListener('click', (e) => {
            if (!e.target.classList.contains('toast-close')) {
                dismissToast(toastId);
            }
        });

        // Auto dismiss with adjusted progress animation
        if (duration > 0) {
            const progressBar = toast.querySelector('.toast-progress');
            if (progressBar) {
                progressBar.style.animationDuration = `${duration}ms`;
            }
            setTimeout(() => dismissToast(toastId), duration);
        }

        return toastId;
    }

    /**
     * Dismiss toast
     */
    function dismissToast(toastId) {
        const toast = document.getElementById(toastId);
        if (!toast) return;

        toast.classList.add('toast-dismissing');
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            
            // Remove container if empty
            if (container && container.children.length === 0) {
                container.remove();
                container = null;
            }
        }, 300);
    }

    /**
     * Dismiss all toasts
     */
    function dismissAll() {
        if (!container) return;
        
        const toasts = container.querySelectorAll('.toast');
        toasts.forEach(toast => {
            dismissToast(toast.id);
        });
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Public API
    return {
        success: (title, message, duration) => createToast('success', title, message, duration),
        error: (title, message, duration) => createToast('error', title, message, duration),
        warning: (title, message, duration) => createToast('warning', title, message, duration),
        info: (title, message, duration) => createToast('info', title, message, duration),
        dismiss: dismissToast,
        dismissAll: dismissAll
    };
})();

// Make it available globally
window.Toast = Toast;
