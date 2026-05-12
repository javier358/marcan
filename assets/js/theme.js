(function () {
    const header = document.querySelector('[data-marcan-header]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const primaryNav = document.querySelector('[data-primary-nav]');
    const revealNodes = document.querySelectorAll('[data-reveal]');
    const heroSliders = document.querySelectorAll('[data-hero-slider]');

    function syncHeader() {
        if (!header) {
            return;
        }

        const scrolled = window.scrollY > 24;

        header.classList.add('is-visible');
        header.classList.toggle('is-scrolled', scrolled);
    }

    if (menuToggle && primaryNav) {
        primaryNav.hidden = true;
        header.classList.remove('is-menu-open');
        menuToggle.setAttribute('aria-expanded', 'false');

        menuToggle.addEventListener('click', function () {
            const isOpen = !primaryNav.classList.contains('is-open');
            primaryNav.classList.toggle('is-open', isOpen);
            header.classList.toggle('is-menu-open', isOpen);
            primaryNav.hidden = !isOpen;
            menuToggle.setAttribute('aria-expanded', String(isOpen));
        });
    }

    function initHeroSlider(slider) {
        const slides = Array.prototype.slice.call(slider.querySelectorAll('[data-hero-slide]'));
        if (!slides.length) {
            return;
        }

        const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const autoplay = slider.getAttribute('data-hero-autoplay') !== '0';
        const baseInterval = parseInt(slider.getAttribute('data-hero-interval'), 10) || 5000;
        let activeIndex = Math.max(0, slides.findIndex(function (slide) {
            return slide.classList.contains('is-active');
        }));
        let timer = null;

        function setActive(nextIndex) {
            slides.forEach(function (slide, index) {
                const active = index === nextIndex;
                slide.classList.toggle('is-active', active);
                slide.classList.toggle('is-zooming', active && slide.getAttribute('data-hero-effect') === 'zoom');
            });
            activeIndex = nextIndex;
        }

        function step() {
            if (slides.length < 2) {
                return;
            }
            setActive((activeIndex + 1) % slides.length);
        }

        function start() {
            if (!autoplay || prefersReducedMotion || slides.length < 2) {
                return;
            }
            stop();
            timer = window.setInterval(step, baseInterval);
        }

        function stop() {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        setActive(activeIndex < 0 ? 0 : activeIndex);
        start();

        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
    }

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        revealNodes.forEach(function (node) {
            observer.observe(node);
        });
    } else {
        revealNodes.forEach(function (node) {
            node.classList.add('is-visible');
        });
    }

    window.addEventListener('scroll', syncHeader, { passive: true });
    window.addEventListener('load', function () {
        syncHeader();
        if (primaryNav) {
            primaryNav.hidden = true;
            primaryNav.classList.remove('is-open');
        }
        if (menuToggle) {
            menuToggle.setAttribute('aria-expanded', 'false');
        }
        if (header) {
            header.classList.remove('is-menu-open');
        }
        document.querySelectorAll('.intro-logo').forEach(function (node) {
            node.classList.add('is-visible');
        });
        heroSliders.forEach(initHeroSlider);
    });
}());
