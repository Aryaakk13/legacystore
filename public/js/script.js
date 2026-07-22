/* ==========================================
   LegacySMP Store - Main JavaScript
   ========================================== */

(function() {
    'use strict';

    // === Configuration ===
    const CONFIG = {
        apiEndpoint: '/api',
        animationDelay: 100,
        toastDuration: 5000,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
    };

    // === DOM Ready ===
    document.addEventListener('DOMContentLoaded', function() {
        initParticles();
        initCategoryTabs();
        initCartButtons();
        initCopyIP();
        initSmoothScroll();
        initServerStatus();
        initAnimations();
    });

    // === Particles Background ===
    function initParticles() {
        const container = document.getElementById('particles');
        if (!container) return;

        for (let i = 0; i < 15; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 10 + 's';
            particle.style.animationDuration = (15 + Math.random() * 15) + 's';
            particle.style.width = (2 + Math.random() * 5) + 'px';
            particle.style.height = particle.style.width;
            container.appendChild(particle);
        }
    }

    // === Category Tabs ===
    function initCategoryTabs() {
        const tabs = document.querySelectorAll('.category-tab');
        const productCards = document.querySelectorAll('.product-card');

        if (!tabs.length) return;

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const category = this.getAttribute('data-category');

                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                productCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');

                    if (category === 'all' || cardCategory === category) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeInUp 0.5s ease both';
                        card.style.animationDelay = Math.random() * 0.3 + 's';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // === Cart System ===
    function initCartButtons() {
        const addButtons = document.querySelectorAll('.btn-add-cart');

        addButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');

                if (!productId) return;

                this.disabled = true;
                this.innerHTML = '<span class="loading-spinner" style="width:20px;height:20px;border-width:2px;margin:0 auto;"></span>';

                addToCart(productId)
                    .then(response => {
                        showToast(`${productName} added to cart!`, 'success');
                        updateCartCount(response.cartCount);
                    })
                    .catch(error => {
                        showToast(error.message || 'Failed to add to cart', 'error');
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
                    });
            });
        });
    }

    async function addToCart(productId) {
        const response = await fetch(`/shop/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CONFIG.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.error || 'Failed to add to cart');
        }

        return await response.json();
    }

    function updateCartCount(count) {
        const badge = document.querySelector('.cart-count');
        if (badge) {
            badge.textContent = count;
            badge.style.animation = 'none';
            setTimeout(() => badge.style.animation = 'pulse 0.5s ease', 10);
        }
    }

    // === Copy Server IP ===
    function initCopyIP() {
        const ipElement = document.querySelector('.server-ip');
        if (!ipElement) return;

        ipElement.addEventListener('click', function() {
            const ip = this.getAttribute('data-ip') || this.textContent.trim();

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(ip).then(() => {
                    showToast('Server IP copied to clipboard!', 'success');
                }).catch(() => {
                    fallbackCopy(ip);
                });
            } else {
                fallbackCopy(ip);
            }
        });
    }

    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('Server IP copied to clipboard!', 'success');
    }

    // === Smooth Scroll ===
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;

                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                }
            });
        });
    }

    // === Server Status Check ===
    function initServerStatus() {
        const statusElement = document.querySelector('.server-status');
        if (!statusElement) return;

        checkServerStatus();
        setInterval(checkServerStatus, 60000);
    }

    async function checkServerStatus() {
        const dot = document.querySelector('.server-status .dot');
        const text = document.querySelector('.server-status span');

        if (!dot || !text) return;

        try {
            dot.style.background = 'var(--success)';
            text.textContent = '● Server Online';
        } catch (error) {
            dot.style.background = 'var(--danger)';
            text.textContent = '● Server Offline';
        }
    }

    // === Scroll Animations ===
    function initAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px',
        });

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    }

    // === Toast Notification System ===
    function showToast(message, type = 'info') {
        const container = document.querySelector('.toast-container');
        if (!container) {
            const newContainer = document.createElement('div');
            newContainer.className = 'toast-container';
            document.body.appendChild(newContainer);
        }

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️',
        };

        toast.innerHTML = `
            <div style="display:flex;align-items:center;gap:10px;">
                <span>${icons[type] || icons.info}</span>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;color:var(--text);cursor:pointer;margin-left:auto;font-size:1.2rem;">&times;</button>
            </div>
        `;

        const container2 = document.querySelector('.toast-container');
        container2.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, CONFIG.toastDuration);
    }

    // === Utility Functions ===
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    window.LegacySMP = {
        showToast,
        formatCurrency,
        addToCart,
        updateCartCount,
    };

})();
