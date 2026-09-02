const jqueryInstance = typeof window.jQuery === 'function' ? window.jQuery : typeof window.$ === 'function' ? window.$ : null;
const notifyRuntimeReady = () => {
    window.dispatchEvent(new CustomEvent('socialoud:runtime-ready', { detail: { jquery: jqueryInstance } }));
};
if (jqueryInstance) {
    window.socialoudJquery = jqueryInstance;
    window.jQuery = window.$ = jqueryInstance;
}
notifyRuntimeReady();
window.setTimeout(notifyRuntimeReady, 0);
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', notifyRuntimeReady, { once: true });
}

const SocialoudRuntime = {
    render: () => null,

    data() {
        return {
            categoryLoading: false,
            navigationLoading: false,
            modalTimer: null,
            modalCountdownTimer: null,
            adSliderTimers: [],
        };
    },

    mounted() {
        this.onDocumentClick = this.handleDocumentClick.bind(this);
        this.onDocumentChange = this.handleDocumentChange.bind(this);
        this.onDocumentSubmit = this.handleDocumentSubmit.bind(this);
        this.onPopState = () => this.navigate(window.location.href, false);
        this.onKeydown = (event) => {
            if (event.key === 'Escape') {
                this.closeGalleryPreview();
                this.closeCoverAd();
            }
        };
        this.colorSchemeMedia = window.matchMedia('(prefers-color-scheme: light)');
        this.onColorSchemeChange = () => {
            if (this.getThemePreference() === 'auto') this.setTheme('auto', false);
        };
        if (this.colorSchemeMedia.addEventListener) {
            this.colorSchemeMedia.addEventListener('change', this.onColorSchemeChange);
        } else {
            this.colorSchemeMedia.addListener(this.onColorSchemeChange);
        }

        document.addEventListener('click', this.onDocumentClick);
        document.addEventListener('change', this.onDocumentChange);
        document.addEventListener('submit', this.onDocumentSubmit);
        window.addEventListener('popstate', this.onPopState);
        this.applyStoredTheme();
        this.bindPage();
        this.bindCoverAd();
    },

    beforeUnmount() {
        document.removeEventListener('click', this.onDocumentClick);
        document.removeEventListener('change', this.onDocumentChange);
        document.removeEventListener('submit', this.onDocumentSubmit);
        window.removeEventListener('popstate', this.onPopState);
        if (this.colorSchemeMedia?.removeEventListener) {
            this.colorSchemeMedia.removeEventListener('change', this.onColorSchemeChange);
        } else {
            this.colorSchemeMedia?.removeListener(this.onColorSchemeChange);
        }
        window.clearTimeout(this.modalTimer);
        window.clearInterval(this.modalCountdownTimer);
        this.adSliderTimers.forEach(({ timer }) => window.clearInterval(timer));
        if (this.coverAdScrollHandler) {
            window.removeEventListener('scroll', this.coverAdScrollHandler);
        }
    },

    methods: {
        bindPage() {
            this.updateSubcategoryArrows();
            this.bindAdSliders();
            this.bindGalleryModal();
            this.bindThreadsShare();
        },
        bindThreadsShare() {
            const sharing = document.querySelector('.socialoud-article-share');
            const list = sharing?.querySelector('.bb-social-sharing:not(.socialoud-threads-sharing)');
            const threadsItem = sharing?.querySelector('.socialoud-threads-sharing li');
            const copyItem = list?.lastElementChild;
            const threadsWrapper = threadsItem?.parentElement;
            if (!list || !threadsItem || !copyItem || !threadsWrapper) return;

            list.insertBefore(threadsItem, copyItem);
            threadsWrapper.remove();
        },
        bindGalleryModal() {
            const modals = [...document.querySelectorAll('[data-socialoud-gallery-modal]')];
            const modal = modals.pop();
            modals.forEach((item) => item.remove());
            if (modal && modal.parentNode !== document.body) document.body.appendChild(modal);
        },

        getThemePreference() {
            try {
                const preference = localStorage.getItem('socialoud-theme');
                return ['dark', 'light', 'auto'].includes(preference) ? preference : 'dark';
            } catch (_) {
                return 'dark';
            }
        },

        applyStoredTheme() {
            this.setTheme(this.getThemePreference(), false);
        },

        setTheme(preference, persist = true) {

            const theme = ['dark', 'light', 'auto'].includes(preference) ? preference : 'dark';
            const isLight = theme === 'light' || (theme === 'auto' && this.colorSchemeMedia.matches);
            document.documentElement.classList.toggle('socialoud-light', isLight);
            if (persist) {
                try {
                    localStorage.setItem('socialoud-theme', theme);
                } catch (_) {}
            }

            const select = document.querySelector('[data-theme-toggle]');
            if (!select) return;
            select.value = theme;
            const labels = { auto: 'Auto', dark: 'Dark', light: 'Light' };
            select.setAttribute('aria-label', `Choose display theme (current: ${labels[theme]})`);
        },

        handleDocumentClick(event) {
            const menuButton = event.target.closest('.socialoud-menu-toggle');
            if (menuButton) {
                const nav = document.querySelector('.socialoud-nav');
                const open = nav?.classList.toggle('is-open') ?? false;
                menuButton.setAttribute('aria-expanded', String(open));
                return;
            }

            const searchButton = event.target.closest('.socialoud-search-toggle');
            if (searchButton) {
                const panel = document.querySelector('.socialoud-search-panel');
                if (!panel) return;
                const open = panel.hasAttribute('hidden');
                panel.toggleAttribute('hidden', !open);
                searchButton.setAttribute('aria-expanded', String(open));
                if (open) panel.querySelector('input')?.focus();
                return;
            }
            const galleryModalClose = event.target.closest('[data-socialoud-gallery-modal-close]');
            if (galleryModalClose) {
                this.closeGalleryPreview();
                return;
            }

            const galleryPreview = event.target.closest('[data-socialoud-gallery-preview]');
            if (galleryPreview) {
                event.preventDefault();
                this.openGalleryPreview(galleryPreview);
                return;
            }

            if (event.target.closest('[data-socialoud-ad-close]')) {
                this.closeCoverAd();
                return;
            }

            const previous = event.target.closest('[data-subcategory-prev]');
            if (previous) {
                this.scrollSubcategories(-1);
                return;
            }

            const next = event.target.closest('[data-subcategory-next]');
            if (next) {
                this.scrollSubcategories(1);
                return;
            }

            const filter = event.target.closest('[data-category-filter]');
            if (filter) {
                this.loadCategory(filter);
                return;
            }

            const homeLoadMore = event.target.closest('[data-socialoud-home-load-more]');
            if (homeLoadMore) {
                this.loadMoreHome(homeLoadMore);
                return;
            }

            const galleryLoadMore = event.target.closest('[data-socialoud-gallery-load-more]');
            if (galleryLoadMore) {
                this.loadMoreGallery(galleryLoadMore);
                return;
            }

            const loadMore = event.target.closest('[data-socialoud-load-more]');
            if (loadMore) {
                this.loadMoreCategory(loadMore);
                return;
            }

            const anchor = event.target.closest('a');
            if (!anchor || !this.isNavigableAnchor(anchor, event)) return;

            const url = new URL(anchor.href, window.location.href);
            if (url.hash && url.pathname === window.location.pathname && url.search === window.location.search) {
                return;
            }

            event.preventDefault();
            this.navigate(url.href);
        },
        handleDocumentChange(event) {
            const select = event.target.closest('[data-theme-toggle]');
            if (select) this.setTheme(select.value);
        },

        handleDocumentSubmit(event) {
            const form = event.target.closest('.socialoud-search-form');
            if (!form) return;

            event.preventDefault();
            const url = new URL(form.action, window.location.href);
            const query = new FormData(form);
            url.search = new URLSearchParams(query).toString();
            this.navigate(url.href);
        },

        isNavigableAnchor(anchor, event) {
            if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return false;
            if (anchor.target && anchor.target !== '_self') return false;
            if (anchor.hasAttribute('download') || anchor.href.startsWith('mailto:') || anchor.href.startsWith('tel:')) return false;

            const url = new URL(anchor.href, window.location.href);
            return url.origin === window.location.origin && !url.pathname.startsWith('/admin');
        },

        async loadPageScripts(nextDocument) {
            const loadedScripts = new Set([...document.scripts].map((script) => script.src).filter(Boolean));
            const scripts = [...nextDocument.scripts].filter((script) => {
                return script.src
                    && !loadedScripts.has(script.src)
                    && !script.src.includes('/themes/socialoud/js/socialoud.js');
            });

            for (const source of scripts) {
                await new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = source.src;
                    script.onload = resolve;
                    script.onerror = reject;
                    document.head.appendChild(script);
                });
            }

            nextDocument.querySelectorAll('script:not([src])').forEach((source) => {
                if (!source.textContent.includes('.validate(')) return;

                const script = document.createElement('script');
                script.textContent = source.textContent;
                document.body.appendChild(script);
            });
        },

        async navigate(url, push = true) {
            if (this.navigationLoading) return;

            this.navigationLoading = true;
            document.documentElement.classList.add('socialoud-navigation-loading');
            try {
                const response = await fetch(url, {
                    headers: {
                        Accept: 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                if (!response.ok) throw new Error(`Navigation failed: ${response.status}`);

                const html = await response.text();
                const documentParser = new DOMParser();
                const nextDocument = documentParser.parseFromString(html, 'text/html');
                const nextMain = nextDocument.querySelector('main.socialoud-article-page')
                    || nextDocument.querySelector('main.socialoud-home, main.socialoud-category-page')
                    || nextDocument.querySelector('main.socialoud-default-content');
                const currentMain = document.querySelector('main.socialoud-home, main.socialoud-category-page') || document.querySelector('main.socialoud-default-content');
                const existingStyles = new Set([...document.querySelectorAll('link[rel="stylesheet"][href]')].map((link) => link.href));
                nextDocument.querySelectorAll('link[rel="stylesheet"][href]').forEach((link) => {
                    if (!existingStyles.has(link.href)) document.head.appendChild(document.importNode(link, true));
                });
                if (!nextMain || !currentMain) {
                    window.location.href = url;
                    return;
                }
                await this.loadPageScripts(nextDocument);

                const nextMainElement = document.importNode(nextMain, true);
                nextMainElement.classList.add('socialoud-page-enter');
                currentMain.replaceWith(nextMainElement);
                document.title = nextDocument.title;
                this.updateMeta(nextDocument);
                if (push) window.history.pushState({}, '', url);
                window.scrollTo({ top: 0, behavior: 'auto' });
                this.bindPage();
                const commentListUrl = nextMainElement.querySelector('[data-fob-comment-list-url]')?.dataset.fobCommentListUrl;
                const csrfToken = nextDocument.querySelector('meta[name="csrf-token"]')?.content;
                if (commentListUrl) {
                    window.fobComment = {
                        ...(window.fobComment || {}),
                        listUrl: commentListUrl,
                        ...(csrfToken ? { csrfToken } : {}),
                    };
                }
                window.dispatchEvent(new CustomEvent('socialoud:page-updated'));
            } catch (error) {
                console.error(error);
                window.location.href = url;
            } finally {
                this.navigationLoading = false;
                document.documentElement.classList.remove('socialoud-navigation-loading');
            }
        },

        updateMeta(nextDocument) {
            ['description', 'robots'].forEach((name) => {
                const incoming = nextDocument.head.querySelector(`meta[name="${name}"]`);
                const current = document.head.querySelector(`meta[name="${name}"]`);
                if (incoming && current) current.setAttribute('content', incoming.content);
            });

            nextDocument.head.querySelectorAll('meta[property^="og:"], meta[name^="twitter:"]').forEach((incoming) => {
                const current = document.head.querySelector(`meta[property="${incoming.getAttribute('property')}"]`) || document.head.querySelector(`meta[name="${incoming.getAttribute('name')}"]`);
                if (current) current.setAttribute('content', incoming.content);
            });
        },


        openGalleryPreview(link) {
            const modal = document.querySelector('[data-socialoud-gallery-modal]');
            const image = link.querySelector('img');
            const previewImage = modal?.querySelector('[data-socialoud-gallery-preview-image]');
            const previewTitle = modal?.querySelector('[data-socialoud-gallery-preview-title]');
            const source = link.dataset.galleryImage || image?.currentSrc || image?.src;
            if (!modal || !previewImage || !source) return;

            previewImage.src = source;
            previewImage.alt = link.dataset.galleryTitle || image.alt || '';
            previewImage.hidden = false;
            if (previewTitle) previewTitle.textContent = link.dataset.galleryTitle || image.alt || '';
            modal.hidden = false;
            document.body.classList.add('socialoud-modal-open');
            modal.querySelector('.socialoud-gallery-modal-close')?.focus();
        },

        closeGalleryPreview() {
            const modal = document.querySelector('[data-socialoud-gallery-modal]');
            if (!modal || modal.hidden) return;
            modal.hidden = true;
            if (!document.querySelector('[data-socialoud-cover-ad]:not([hidden])')) {
                document.body.classList.remove('socialoud-modal-open');
            }
        },


        updateCoverAdCountdown(coverAd, seconds) {
            const countdown = coverAd.querySelector('[data-socialoud-ad-countdown]');
            if (countdown) countdown.textContent = `Menutup dalam ${seconds} detik`;
        },

        bindCoverAd() {
            const coverAd = document.querySelector('[data-socialoud-cover-ad]');
            if (!coverAd) return;


            const open = () => {
                if (!coverAd.hidden) return;
                window.clearTimeout(this.modalTimer);
                window.clearInterval(this.modalCountdownTimer);
                coverAd.hidden = false;
                document.body.classList.add('socialoud-modal-open');
                coverAd.querySelector('.socialoud-ad-modal-close')?.focus();

                let seconds = 5;
                this.updateCoverAdCountdown(coverAd, seconds);
                this.modalCountdownTimer = window.setInterval(() => {
                    seconds -= 1;
                    if (seconds <= 0) return;
                    this.updateCoverAdCountdown(coverAd, seconds);
                }, 1000);
                this.modalTimer = window.setTimeout(() => this.closeCoverAd(), 5000);
            };

            const waitForScroll = () => {
                const openOnScroll = () => {
                    this.coverAdScrollHandler = null;
                    open();
                };
                this.coverAdScrollHandler = openOnScroll;
                window.addEventListener('scroll', openOnScroll, { once: true, passive: true });
            };

            const image = coverAd.querySelector('.socialoud-ad-modal-creative img');
            if (!image) {
                waitForScroll();
                return;
            }

            image.loading = 'eager';
            const showWhenReady = () => {
                if (image.naturalWidth === 0) return;
                waitForScroll();
            };
            if (image.complete) {
                showWhenReady();
            } else {
                image.addEventListener('load', showWhenReady, { once: true });
            }
        },

        closeCoverAd() {
            const coverAd = document.querySelector('[data-socialoud-cover-ad]');
            if (!coverAd || coverAd.hidden) return;
            window.clearTimeout(this.modalTimer);
            window.clearInterval(this.modalCountdownTimer);
            coverAd.hidden = true;
            document.body.classList.remove('socialoud-modal-open');
        },

        updateSubcategoryArrows() {
            const subcategoryNav = document.querySelector('.socialoud-subcategory-nav');
            if (!subcategoryNav) return;
            const previous = document.querySelector('[data-subcategory-prev]');
            const next = document.querySelector('[data-subcategory-next]');
            if (!previous || !next) return;

            previous.disabled = subcategoryNav.scrollLeft <= 0;
            next.disabled = subcategoryNav.scrollLeft + subcategoryNav.clientWidth >= subcategoryNav.scrollWidth - 1;
            subcategoryNav.onscroll = () => this.updateSubcategoryArrows();
        },

        scrollSubcategories(direction) {
            const subcategoryNav = document.querySelector('.socialoud-subcategory-nav');
            if (!subcategoryNav) return;
            subcategoryNav.scrollBy({ left: subcategoryNav.clientWidth * .75 * direction, behavior: 'smooth' });
        },
        bindAdSliders() {
            this.adSliderTimers = this.adSliderTimers.filter(({ slider, timer }) => {
                if (document.body.contains(slider)) return true;
                window.clearInterval(timer);
                return false;
            });

            document.querySelectorAll('[data-socialoud-ad-slider]').forEach((slider) => {
                if (slider.dataset.socialoudAdSliderBound) return;

                const slidesContainer = slider.querySelector('.socialoud-ad-slides');
                const slides = slidesContainer ? [...slidesContainer.children].filter((child) => child.tagName === 'DIV') : [];
                if (slides.length < 2) return;

                const dots = slider.querySelector('[data-socialoud-ad-dots]');
                const previous = slider.querySelector('[data-socialoud-ad-prev]');
                const next = slider.querySelector('[data-socialoud-ad-next]');
                let active = 0;
                const sliderState = { slider, timer: null };
                const hydrate = (slide) => {
                    slide.querySelectorAll('source[data-srcset]').forEach((source) => {
                        source.srcset = source.dataset.srcset;
                        source.removeAttribute('data-srcset');
                    });
                    slide.querySelectorAll('img[data-src]').forEach((image) => {
                        image.loading = 'eager';
                        image.setAttribute('src', image.dataset.src);
                        image.removeAttribute('data-src');
                    });
                };

                const show = (index) => {
                    active = (index + slides.length) % slides.length;
                    slides.forEach((slide, slideIndex) => {
                        const isActive = slideIndex === active;
                        if (isActive) hydrate(slide);
                        slide.classList.toggle('is-active', isActive);
                        slide.setAttribute('aria-hidden', String(!isActive));
                    });
                    dotButtons.forEach((dot, dotIndex) => {
                        const isActive = dotIndex === active;
                        dot.classList.toggle('is-active', isActive);
                        dot.setAttribute('aria-current', String(isActive));
                    });
                };

                const restartTimer = () => {
                    window.clearInterval(sliderState.timer);
                    sliderState.timer = window.setInterval(() => show(active + 1), 5000);
                };

                const dotButtons = slides.map((_, index) => {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'socialoud-ad-slider-dot';
                    dot.setAttribute('aria-label', `Tampilkan iklan ${index + 1}`);
                    dot.setAttribute('aria-current', 'false');
                    dot.addEventListener('click', () => {
                        show(index);
                        restartTimer();
                    });
                    dots?.append(dot);
                    return dot;
                });

                if (previous && next) {
                    previous.hidden = false;
                    next.hidden = false;
                    previous.addEventListener('click', () => {
                        show(active - 1);
                        restartTimer();
                    });
                    next.addEventListener('click', () => {
                        show(active + 1);
                        restartTimer();
                    });
                }

                if (dots) dots.hidden = false;
                slider.dataset.socialoudAdSliderBound = 'true';
                show(0);
                restartTimer();
                this.adSliderTimers.push(sliderState);
            });

        },
        async loadCategory(filter) {
            const categoryList = document.querySelector('[data-category-post-list]');
            if (!categoryList || this.categoryLoading) return;

            const endpoint = categoryList.dataset.filterEndpoint;
            const params = new URLSearchParams({ page: '1' });
            if (filter.dataset.categoryAll === '1') params.set('all', '1');

            this.categoryLoading = true;
            document.querySelectorAll('[data-category-filter]').forEach((item) => item.classList.toggle('is-active', item === filter));
            categoryList.setAttribute('aria-busy', 'true');
            try {
                const response = await fetch(`${endpoint}?${params}`, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error(`Category request failed: ${response.status}`);
                const data = await response.json();
                categoryList.innerHTML = data.html;
                this.updateLoadMore(data);
            } catch (error) {
                console.error(error);
            } finally {
                this.categoryLoading = false;
                categoryList.setAttribute('aria-busy', 'false');
            }
        },

        async loadMoreHome(button) {
            const homeList = document.querySelector('[data-socialoud-home-post-list]');
            const nextUrl = button.dataset.nextUrl;
            if (!homeList || !nextUrl || this.categoryLoading) return;

            this.categoryLoading = true;
            button.disabled = true;
            homeList.setAttribute('aria-busy', 'true');
            try {
                const response = await fetch(nextUrl, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error(`Homepage request failed: ${response.status}`);
                const data = await response.json();
                homeList.insertAdjacentHTML('beforeend', data.html);
                if (data.has_more && data.next_url) {
                    button.dataset.nextUrl = data.next_url;
                    button.disabled = false;
                } else {
                    button.remove();
                }
            } catch (error) {
                console.error(error);
                button.disabled = false;
            } finally {
                this.categoryLoading = false;
                homeList.setAttribute('aria-busy', 'false');
            }
        },

        async loadMoreCategory(button) {
            const categoryList = document.querySelector('[data-category-post-list]');
            const nextUrl = button.dataset.nextUrl;
            if (!categoryList || !nextUrl || this.categoryLoading) return;

            this.categoryLoading = true;
            button.disabled = true;
            categoryList.setAttribute('aria-busy', 'true');
            try {
                const response = await fetch(nextUrl, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error(`Category request failed: ${response.status}`);
                const data = await response.json();
                categoryList.insertAdjacentHTML('beforeend', data.html);
                this.updateLoadMore(data);
            } catch (error) {
                console.error(error);
                button.disabled = false;
            } finally {
                this.categoryLoading = false;
                categoryList.setAttribute('aria-busy', 'false');
            }
        },

        loadMoreGallery(button) {
            const hiddenCards = [...document.querySelectorAll('[data-socialoud-gallery-card][hidden]')];
            hiddenCards.slice(0, 6).forEach((card) => {
                card.hidden = false;
            });
            if (hiddenCards.length <= 6) button.remove();
        },

        updateLoadMore(data) {
            let button = document.querySelector('[data-socialoud-load-more]');
            if (!data.has_more || !data.next_url) {
                button?.remove();
                return;
            }

            if (!button) {
                button = document.createElement('button');
                button.className = 'socialoud-load-more';
                button.type = 'button';
                button.dataset.socialoudLoadMore = '';
                button.innerHTML = 'Lihat lebih banyak <span aria-hidden="true">↓</span>';
                document.querySelector('[data-category-post-list]')?.insertAdjacentElement('afterend', button);
            }

            button.disabled = false;
            button.dataset.nextUrl = data.next_url;
        },
    },
};

const mountPoint = document.querySelector('#socialoud-runtime');
if (mountPoint) {
    const runtime = SocialoudRuntime.data();
    Object.entries(SocialoudRuntime.methods).forEach(([name, method]) => {
        runtime[name] = method.bind(runtime);
    });
    SocialoudRuntime.mounted.call(runtime);
}
