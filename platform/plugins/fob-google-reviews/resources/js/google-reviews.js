/**
 * Google Reviews Widget
 * Professional carousel with auto-play and touch support
 */

class GoogleReviewsWidget {
    constructor(element) {
        this.widget = element;
        this.displayType = element.dataset.displayType || 'carousel';
        this.track = element.querySelector('.fob-gr-reviews-track');
        this.cards = element.querySelectorAll('.fob-gr-review-card');
        this.prevBtn = element.querySelector('.fob-gr-prev');
        this.nextBtn = element.querySelector('.fob-gr-next');
        this.dotsContainer = element.querySelector('.fob-gr-dots');

        this.currentIndex = 0;
        this.autoPlayInterval = null;
        this.isTransitioning = false;
        this.touchStartX = 0;
        this.touchEndX = 0;

        this.init();
    }

    init() {
        // Only initialize carousel functionality for carousel display type
        if (this.displayType !== 'carousel') {
            return;
        }

        if (!this.track || this.cards.length === 0) return;

        this.updateCardsPerView();
        this.createDots();
        this.attachEventListeners();
        this.updateNavigation();

        // Start autoplay if enabled
        const autoPlay = this.widget.dataset.autoplay === 'true';
        if (autoPlay) {
            this.startAutoPlay();
        }

        // Handle window resize
        window.addEventListener('resize', this.debounce(() => {
            this.updateCardsPerView();
            this.goToSlide(this.currentIndex, false);
        }, 250));
    }

    updateCardsPerView() {
        const width = window.innerWidth;

        if (width >= 1280) {
            this.cardsPerView = 4;
        } else if (width >= 1024) {
            this.cardsPerView = 3;
        } else if (width >= 768) {
            this.cardsPerView = 2;
        } else {
            this.cardsPerView = 1;
        }

        this.totalSlides = Math.ceil(this.cards.length / this.cardsPerView);
    }

    createDots() {
        if (!this.dotsContainer) return;

        this.dotsContainer.innerHTML = '';

        for (let i = 0; i < this.totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = 'fob-gr-dot';
            dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
            dot.addEventListener('click', () => this.goToSlide(i));
            this.dotsContainer.appendChild(dot);
        }

        this.dots = this.dotsContainer.querySelectorAll('.fob-gr-dot');
        this.updateDots();
    }

    attachEventListeners() {
        // Navigation buttons
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }

        // Touch events for mobile swipe
        this.track.addEventListener('touchstart', (e) => {
            this.touchStartX = e.changedTouches[0].screenX;
            this.stopAutoPlay();
        }, { passive: true });

        this.track.addEventListener('touchend', (e) => {
            this.touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        }, { passive: true });

        // Mouse events for desktop drag
        let isDragging = false;
        let startX = 0;

        this.track.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX;
            this.track.style.cursor = 'grabbing';
            this.stopAutoPlay();
        });

        this.track.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
        });

        this.track.addEventListener('mouseup', (e) => {
            if (!isDragging) return;
            isDragging = false;
            this.track.style.cursor = 'grab';

            const endX = e.pageX;
            const diff = startX - endX;

            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    this.next();
                } else {
                    this.prev();
                }
            }
        });

        this.track.addEventListener('mouseleave', () => {
            isDragging = false;
            this.track.style.cursor = 'grab';
        });

        // Pause on hover
        this.widget.addEventListener('mouseenter', () => this.stopAutoPlay());
        this.widget.addEventListener('mouseleave', () => {
            if (this.widget.dataset.autoplay === 'true') {
                this.startAutoPlay();
            }
        });

        // Read more functionality
        this.widget.querySelectorAll('.fob-gr-read-more').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const reviewText = btn.previousElementSibling;

                if (reviewText.classList.contains('has-more')) {
                    reviewText.classList.remove('has-more');
                    btn.textContent = btn.dataset.lessText || 'Show less';
                } else {
                    reviewText.classList.add('has-more');
                    btn.textContent = btn.dataset.moreText || 'Read more';
                }
            });
        });
    }

    handleSwipe() {
        const diff = this.touchStartX - this.touchEndX;

        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                this.next();
            } else {
                this.prev();
            }
        }
    }

    goToSlide(index, animate = true) {
        if (this.isTransitioning) return;

        this.currentIndex = Math.max(0, Math.min(index, this.totalSlides - 1));

        const cardWidth = this.cards[0].offsetWidth;
        const gap = 16; // Gap between cards
        const offset = -(this.currentIndex * this.cardsPerView * (cardWidth + gap));

        if (!animate) {
            this.track.style.transition = 'none';
        }

        this.track.style.transform = `translateX(${offset}px)`;

        if (!animate) {
            // Force reflow
            this.track.offsetHeight;
            this.track.style.transition = '';
        }

        this.updateNavigation();
        this.updateDots();

        this.isTransitioning = true;
        setTimeout(() => {
            this.isTransitioning = false;
        }, 400);
    }

    next() {
        if (this.currentIndex < this.totalSlides - 1) {
            this.goToSlide(this.currentIndex + 1);
        } else if (this.widget.dataset.loop === 'true') {
            this.goToSlide(0);
        }
    }

    prev() {
        if (this.currentIndex > 0) {
            this.goToSlide(this.currentIndex - 1);
        } else if (this.widget.dataset.loop === 'true') {
            this.goToSlide(this.totalSlides - 1);
        }
    }

    updateNavigation() {
        if (this.prevBtn) {
            this.prevBtn.disabled = this.currentIndex === 0 && this.widget.dataset.loop !== 'true';
        }

        if (this.nextBtn) {
            this.nextBtn.disabled = this.currentIndex === this.totalSlides - 1 && this.widget.dataset.loop !== 'true';
        }
    }

    updateDots() {
        if (!this.dots) return;

        this.dots.forEach((dot, index) => {
            if (index === this.currentIndex) {
                dot.classList.add('active');
                dot.setAttribute('aria-current', 'true');
            } else {
                dot.classList.remove('active');
                dot.removeAttribute('aria-current');
            }
        });
    }

    startAutoPlay() {
        this.stopAutoPlay();

        const interval = parseInt(this.widget.dataset.autoplaySpeed) || 5000;

        this.autoPlayInterval = setInterval(() => {
            if (this.currentIndex < this.totalSlides - 1) {
                this.next();
            } else {
                this.goToSlide(0);
            }
        }, interval);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }

    debounce(func, wait) {
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
}

// Initialize all widgets on page load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.fob-google-reviews-widget').forEach(widget => {
        new GoogleReviewsWidget(widget);
    });
});

// Expose for dynamic initialization
window.GoogleReviewsWidget = GoogleReviewsWidget;
