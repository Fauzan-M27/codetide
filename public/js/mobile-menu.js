/* ═══════════════════════════════════════════════════
   MOBILE MENU HANDLER — CodeTide
   Handles hamburger menu toggle and mobile navigation
═══════════════════════════════════════════════════ */

(function() {
    'use strict';

    // Initialize mobile menu
    function initMobileMenu() {
        const hamburger = document.querySelector('.hamburger-menu');
        const mobileMenu = document.querySelector('.mobile-menu-overlay');
        
        if (!hamburger || !mobileMenu) return;

        // Toggle menu
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if (mobileMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        // Close menu when clicking outside
        mobileMenu.addEventListener('click', function(e) {
            if (e.target === this) {
                hamburger.classList.remove('active');
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Close menu when clicking a link
        const menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // Detect touch device
    function detectTouchDevice() {
        const isTouchDevice = ('ontouchstart' in window) || 
                             (navigator.maxTouchPoints > 0) || 
                             (navigator.msMaxTouchPoints > 0);
        
        if (isTouchDevice) {
            document.body.classList.add('touch-device');
        }
    }

    // Handle orientation change
    function handleOrientationChange() {
        window.addEventListener('orientationchange', function() {
            // Close mobile menu on orientation change
            const hamburger = document.querySelector('.hamburger-menu');
            const mobileMenu = document.querySelector('.mobile-menu-overlay');
            
            if (hamburger && mobileMenu) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            }

            // Refresh layout after orientation change
            setTimeout(function() {
                window.scrollTo(0, window.scrollY);
            }, 100);
        });
    }

    // Improve table scrolling on mobile
    function improveTableScroll() {
        const tables = document.querySelectorAll('.leaderboard-table, table');
        
        tables.forEach(table => {
            const wrapper = table.closest('.leaderboard-panel, .table-wrapper');
            if (wrapper && window.innerWidth <= 968) {
                // Add scroll hint
                if (!wrapper.querySelector('.scroll-hint')) {
                    const hint = document.createElement('div');
                    hint.className = 'scroll-hint';
                    hint.textContent = '← Scroll →';
                    hint.style.cssText = `
                        font-family: 'Press Start 2P', monospace;
                        font-size: 0.4rem;
                        color: rgba(232, 244, 255, 0.3);
                        text-align: center;
                        padding: 8px;
                        position: sticky;
                        bottom: 0;
                        background: rgba(9, 19, 38, 0.9);
                        border-top: 1px solid rgba(83, 234, 253, 0.15);
                    `;
                    wrapper.appendChild(hint);
                }

                // Remove hint after first scroll
                wrapper.addEventListener('scroll', function() {
                    const hint = this.querySelector('.scroll-hint');
                    if (hint) {
                        hint.style.opacity = '0';
                        setTimeout(() => hint.remove(), 300);
                    }
                }, { once: true });
            }
        });
    }

    // Viewport height fix for mobile browsers
    function fixViewportHeight() {
        // Set CSS variable for real viewport height
        const setVh = () => {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        };

        setVh();
        window.addEventListener('resize', setVh);
    }

    // Prevent double-tap zoom on buttons
    function preventDoubleTapZoom() {
        const buttons = document.querySelectorAll('button, .btn');
        
        buttons.forEach(button => {
            let lastTap = 0;
            
            button.addEventListener('touchend', function(e) {
                const currentTime = new Date().getTime();
                const tapLength = currentTime - lastTap;
                
                if (tapLength < 300 && tapLength > 0) {
                    e.preventDefault();
                }
                
                lastTap = currentTime;
            });
        });
    }

    // Initialize all mobile features
    function init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }

        detectTouchDevice();
        initMobileMenu();
        handleOrientationChange();
        improveTableScroll();
        fixViewportHeight();
        preventDoubleTapZoom();

        // Re-run table scroll improvements on dynamic content load
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    improveTableScroll();
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Auto-initialize
    init();

    // Make functions available globally if needed
    window.MobileMenu = {
        init: initMobileMenu,
        detectTouch: detectTouchDevice
    };

})();
