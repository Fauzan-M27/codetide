/* ═══════════════════════════════════════════════════
   LOADING SYSTEM — CodeTide
   Usage:
   - Loading.show('Processing...')
   - Loading.hide()
   - Loading.button(btnElement, true/false)
   - AutoSave.show('saving'/'saved'/'error')
═══════════════════════════════════════════════════ */

const Loading = (function() {
    let overlay = null;
    let particlesInterval = null;

    /**
     * Show full page loading overlay
     */
    function show(text = 'LOADING', hint = null) {
        // Remove existing overlay if any
        hide();

        // Create overlay
        overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-particles" id="loadingParticles"></div>
            <div class="loading-spinner"></div>
            <div class="loading-text">${escapeHtml(text)}</div>
            ${hint ? `<div class="loading-hint">${escapeHtml(hint)}</div>` : ''}
        `;

        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        // Start particle animation
        startParticles();

        return overlay;
    }

    /**
     * Hide loading overlay
     */
    function hide() {
        if (!overlay) return;

        overlay.classList.add('loading-out');
        document.body.style.overflow = '';

        // Stop particles
        stopParticles();

        setTimeout(() => {
            if (overlay && overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
            overlay = null;
        }, 300);
    }

    /**
     * Check if loading is active
     */
    function isActive() {
        return overlay !== null;
    }

    /**
     * Toggle button loading state
     * @param {HTMLElement} btnElement - Button element
     * @param {boolean} loading - True to show loading, false to hide
     * @param {string} loadingText - Optional custom loading text (default: null = keep original)
     * @param {string} styleClass - Optional style class (btn-blocks, btn-pulse, btn-bounce)
     */
    function button(btnElement, loading = true, loadingText = null, styleClass = '') {
        if (!btnElement) return;

        if (loading) {
            // Store original state
            if (!btnElement.hasAttribute('data-original-text')) {
                btnElement.setAttribute('data-original-text', btnElement.innerHTML);
            }
            if (!btnElement.hasAttribute('data-original-disabled')) {
                btnElement.setAttribute('data-original-disabled', btnElement.disabled);
            }

            // Apply loading state
            btnElement.classList.add('btn-loading');
            if (styleClass) {
                btnElement.classList.add(styleClass);
                btnElement.setAttribute('data-loading-style', styleClass);
            }
            btnElement.disabled = true;
            
            // Update text if custom loading text provided
            if (loadingText) {
                btnElement.innerHTML = `<span class="btn-original-text">${loadingText}</span>`;
            } else {
                // Wrap existing text
                const originalText = btnElement.getAttribute('data-original-text');
                btnElement.innerHTML = `<span class="btn-original-text">${originalText}</span>`;
            }
            
        } else {
            // Remove loading state
            btnElement.classList.remove('btn-loading');
            
            // Remove style class if exists
            const styleClass = btnElement.getAttribute('data-loading-style');
            if (styleClass) {
                btnElement.classList.remove(styleClass);
                btnElement.removeAttribute('data-loading-style');
            }
            
            // Restore original disabled state
            const originalDisabled = btnElement.getAttribute('data-original-disabled');
            btnElement.disabled = originalDisabled === 'true';
            
            // Restore original text
            const originalText = btnElement.getAttribute('data-original-text');
            if (originalText) {
                btnElement.innerHTML = originalText;
            }
            
            // Clean up attributes
            btnElement.removeAttribute('data-original-text');
            btnElement.removeAttribute('data-original-disabled');
        }
    }

    /**
     * Start floating particles
     */
    function startParticles() {
        const particlesContainer = document.getElementById('loadingParticles');
        if (!particlesContainer) return;

        particlesInterval = setInterval(() => {
            if (particlesContainer.children.length >= 12) return;

            const particle = document.createElement('div');
            particle.className = 'loading-particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = (2 + Math.random() * 2) + 's';
            
            particlesContainer.appendChild(particle);

            // Remove after animation
            setTimeout(() => {
                if (particle.parentNode) {
                    particle.parentNode.removeChild(particle);
                }
            }, 3000);
        }, 250);
    }

    /**
     * Stop particles
     */
    function stopParticles() {
        if (particlesInterval) {
            clearInterval(particlesInterval);
            particlesInterval = null;
        }
    }

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Public API
    return {
        show: show,
        hide: hide,
        isActive: isActive,
        button: button
    };
})();

/* ═══════════════════════════════════════════════════
   AUTO-SAVE INDICATOR
═══════════════════════════════════════════════════ */

const AutoSave = (function() {
    let indicator = null;
    let hideTimer = null;

    /**
     * Initialize indicator
     */
    function init() {
        if (indicator) return;

        indicator = document.createElement('div');
        indicator.className = 'autosave-indicator';
        indicator.innerHTML = `
            <div class="autosave-icon"></div>
            <span class="autosave-text">Saving...</span>
        `;
        document.body.appendChild(indicator);
    }

    /**
     * Show autosave indicator
     * @param {string} state - 'saving', 'saved', or 'error'
     */
    function show(state = 'saving') {
        init();

        // Clear previous timer
        if (hideTimer) {
            clearTimeout(hideTimer);
            hideTimer = null;
        }

        // Update state
        indicator.className = `autosave-indicator ${state} visible`;

        // Update text
        const textEl = indicator.querySelector('.autosave-text');
        switch(state) {
            case 'saving':
                textEl.textContent = 'SAVING...';
                break;
            case 'saved':
                textEl.textContent = 'SAVED';
                break;
            case 'error':
                textEl.textContent = 'SAVE ERROR';
                break;
        }

        // Auto hide after delay (except for 'saving')
        if (state !== 'saving') {
            hideTimer = setTimeout(hide, 2000);
        }
    }

    /**
     * Hide autosave indicator
     */
    function hide() {
        if (!indicator) return;

        indicator.classList.remove('visible');

        if (hideTimer) {
            clearTimeout(hideTimer);
            hideTimer = null;
        }
    }

    // Public API
    return {
        show: show,
        hide: hide,
        saving: () => show('saving'),
        saved: () => show('saved'),
        error: () => show('error')
    };
})();

/* ═══════════════════════════════════════════════════
   INLINE LOADING SPINNER
   Usage: <div class="loading-inline"><div class="loading-inline-spinner"></div>Loading...</div>
═══════════════════════════════════════════════════ */

// Make available globally
window.Loading = Loading;
window.AutoSave = AutoSave;
