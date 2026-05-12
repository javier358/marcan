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

    function initProjectSlider(slider) {
        const track = slider.querySelector('.marcan-home-project-slider-track');
        if (!track) {
            return;
        }

        let isDown = false;
        let startX = 0;
        let startScrollLeft = 0;
        let dragged = false;
        let activePointerId = null;

        slider.addEventListener('dragstart', function (event) {
            event.preventDefault();
        });

        slider.addEventListener('pointerdown', function (event) {
            if (event.pointerType === 'mouse' && event.button !== 0) {
                return;
            }

            if (track.scrollWidth <= slider.clientWidth) {
                return;
            }

            isDown = true;
            dragged = false;
            activePointerId = event.pointerId;
            startX = event.clientX;
            startScrollLeft = slider.scrollLeft;
            slider.classList.add('is-dragging');
            slider.setPointerCapture(activePointerId);

            if (event.pointerType === 'mouse') {
                event.preventDefault();
            }
        });

        slider.addEventListener('pointermove', function (event) {
            if (!isDown || event.pointerId !== activePointerId) {
                return;
            }

            const delta = event.clientX - startX;
            if (Math.abs(delta) > 5) {
                dragged = true;
            }
            slider.scrollLeft = startScrollLeft - delta;

            if (dragged && event.pointerType === 'mouse') {
                event.preventDefault();
            }
        });

        function endDrag(event) {
            if (!isDown || event.pointerId !== activePointerId) {
                return;
            }
            isDown = false;
            slider.classList.remove('is-dragging');
            try {
                slider.releasePointerCapture(activePointerId);
            } catch (err) {}
            activePointerId = null;
        }

        slider.addEventListener('pointerup', endDrag);
        slider.addEventListener('pointercancel', endDrag);
        slider.addEventListener('pointerleave', endDrag);
        slider.addEventListener('click', function (event) {
            if (!dragged) {
                return;
            }
            event.preventDefault();
            event.stopPropagation();
            dragged = false;
        }, true);
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
        document.querySelectorAll('[data-project-slider]').forEach(initProjectSlider);
    });
}());
