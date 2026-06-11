(function () {
    const header = document.querySelector('[data-marcan-header]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const primaryNav = document.querySelector('[data-primary-nav]');
    const revealNodes = document.querySelectorAll('[data-reveal]');
    const heroSliders = document.querySelectorAll('[data-hero-slider]');
    const footerAnimationNodes = document.querySelectorAll('[data-footer-animation]');
    const virtualTours = document.querySelectorAll('[data-marcan-tour]');
    const propertyFilterBars = document.querySelectorAll('[data-property-filters]');
    const propertyGalleries = document.querySelectorAll('[data-property-gallery]');

    function initContactModal() {
        const contactModal = document.querySelector('[data-contact-modal]');
        if (!contactModal) {
            return;
        }

        function openContactModal() {
            contactModal.classList.remove('is-contact-sent');
            if (typeof contactModal.showModal === 'function' && !contactModal.open) {
                contactModal.showModal();
            } else if (!contactModal.open) {
                contactModal.setAttribute('open', '');
            }
        }

        function closeContactModal() {
            if (typeof contactModal.close === 'function') {
                contactModal.close();
            } else {
                contactModal.removeAttribute('open');
            }
        }

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-open-contact-modal]');
            if (trigger) {
                event.preventDefault();
                const parentDialog = trigger.closest('dialog[open]');
                if (parentDialog && parentDialog !== contactModal) {
                    if (typeof parentDialog.close === 'function') {
                        parentDialog.close();
                    } else {
                        parentDialog.removeAttribute('open');
                    }
                }
                openContactModal();
                return;
            }

            const link = event.target.closest('a[href]');
            if (link) {
                const href = link.getAttribute('href') || '';
                if (/\/contactanos\/?($|[?#])/.test(href) || /#contacto$/.test(href)) {
                    event.preventDefault();
                    openContactModal();
                    return;
                }
            }

            if (event.target.closest('[data-contact-modal-close]')) {
                closeContactModal();
                return;
            }

            if (event.target.closest('[data-contact-thanks-close]')) {
                closeContactModal();
                return;
            }

            if (event.target === contactModal) {
                closeContactModal();
            }
        });
    }

    initContactModal();

    function initContactForm() {
        const form = document.querySelector('[data-contact-form]');
        if (!form || !window.marcanContactForm) {
            return;
        }

        const submit = form.querySelector('.marcan-property-contact-modal-submit');
        const contactModal = form.closest('[data-contact-modal]');
        const message = form.querySelector('[data-contact-form-message]');
        const sourceUrl = form.querySelector('input[name="source_url"]');
        const sourceTitle = form.querySelector('input[name="source_title"]');
        let thanksCloseTimer = null;

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (sourceUrl) {
                sourceUrl.value = window.location.href;
            }
            if (sourceTitle) {
                sourceTitle.value = document.title;
            }

            var contextEl = form.querySelector('input[name="source_context"]');
            var propertyEl = form.querySelector('input[name="source_property"]');
            var unitEl = form.querySelector('input[name="source_unit"]');

            if (contextEl) {
                var context = [];
                if (document.querySelector('.marcan-property-unit-detail:not([hidden])')) {
                    context.push('Tipologia / Unidad');
                    var unitDetail = document.querySelector('.marcan-property-unit-detail:not([hidden])');
                    var unitTitle = unitDetail.querySelector('.marcan-property-unit-info h3');
                    if (unitTitle) context.push(unitTitle.textContent.trim());
                } else if (document.querySelector('.marcan-property-single')) {
                    context.push('Ficha de proyecto');
                } else if (document.querySelector('.marcan-property-archive')) {
                    context.push('Listado');
                }
                contextEl.value = context.join(' | ');
            }

            if (propertyEl) {
                var stickyTitle = document.querySelector('.marcan-property-sticky-quote h1');
                if (stickyTitle) {
                    propertyEl.value = stickyTitle.textContent.trim();
                }
            }

            if (unitEl) {
                var unitDetailVis = document.querySelector('.marcan-property-unit-detail:not([hidden])');
                if (unitDetailVis) {
                    var unitH3 = unitDetailVis.querySelector('.marcan-property-unit-info h3');
                    if (unitH3) unitEl.value = unitH3.textContent.trim();
                }
            }

            const data = new FormData(form);
            data.append('action', 'marcan_contact_submit');
            data.append('nonce', window.marcanContactForm.nonce);

            form.classList.remove('is-sent', 'is-error');
            if (contactModal) {
                contactModal.classList.remove('is-contact-sent');
            }
            if (thanksCloseTimer) {
                window.clearTimeout(thanksCloseTimer);
                thanksCloseTimer = null;
            }
            form.classList.add('is-sending');
            if (submit) {
                submit.disabled = true;
            }
            if (message) {
                message.hidden = true;
                message.textContent = '';
            }

            const request = new XMLHttpRequest();
            request.open('POST', window.marcanContactForm.ajaxUrl, true);
            request.onload = function () {
                let payload = {};
                try {
                    payload = JSON.parse(request.responseText || '{}');
                } catch (error) {
                    payload = {};
                }

                if (request.status >= 200 && request.status < 300 && payload.success) {
                    form.classList.remove('is-sending');
                    form.classList.add('is-sent');
                    if (contactModal) {
                        contactModal.classList.add('is-contact-sent');
                        thanksCloseTimer = window.setTimeout(function () {
                            if (typeof contactModal.close === 'function') {
                                contactModal.close();
                            } else {
                                contactModal.removeAttribute('open');
                            }
                            contactModal.classList.remove('is-contact-sent');
                            form.classList.remove('is-sent');
                            thanksCloseTimer = null;
                        }, 5000);
                    }
                    form.reset();
                    if (message) {
                        message.hidden = false;
                        message.textContent = payload.data && payload.data.message ? payload.data.message : 'Gracias. Hemos recibido tus datos.';
                    }
                } else {
                    form.classList.remove('is-sending');
                    form.classList.add('is-error');
                    if (message) {
                        message.hidden = false;
                        message.textContent = payload && payload.data && payload.data.message ? payload.data.message : 'No se pudo enviar el formulario. Inténtalo nuevamente.';
                    }
                }

                if (submit) {
                    submit.disabled = false;
                }
            };
            request.onerror = function () {
                form.classList.remove('is-sending');
                form.classList.add('is-error');
                if (message) {
                    message.hidden = false;
                    message.textContent = 'No se pudo enviar el formulario. Inténtalo nuevamente.';
                }
                if (submit) {
                    submit.disabled = false;
                }
            };
            request.send(data);
        });
    }

    initContactForm();

    function syncHeader() {
        if (!header) {
            return;
        }

        const scrolled = window.scrollY > 24;

        header.classList.add('is-visible');
        header.classList.toggle('is-scrolled', scrolled);
    }

    if (menuToggle && primaryNav) {
        function closeMenu() {
            primaryNav.classList.remove('is-open');
            header.classList.remove('is-menu-open');
            primaryNav.hidden = true;
            menuToggle.setAttribute('aria-expanded', 'false');
        }

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

        header.addEventListener('mouseleave', function () {
            if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
                closeMenu();
            }
        });

        header.addEventListener('pointerout', function (event) {
            if (
                window.matchMedia('(hover: hover) and (pointer: fine)').matches
                && !header.contains(event.relatedTarget)
            ) {
                closeMenu();
            }
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

            if (event.pointerType !== 'mouse') {
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
        });

        slider.addEventListener('pointermove', function (event) {
            if (!isDown || event.pointerId !== activePointerId) {
                return;
            }

            const delta = event.clientX - startX;
            if (!dragged && Math.abs(delta) > 5) {
                dragged = true;
                slider.classList.add('is-dragging');
                try {
                    slider.setPointerCapture(activePointerId);
                } catch (err) {}
            }

            if (dragged) {
                slider.scrollLeft = startScrollLeft - delta;
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

    function initAboutSlider(slider) {
        let isDown = false;
        let startX = 0;
        let startScrollLeft = 0;
        let dragged = false;
        let activePointerId = null;
        let activeFrame = null;
        const items = Array.prototype.slice.call(slider.querySelectorAll('.marcan-about-timeline-item'));

        function syncActiveItem() {
            activeFrame = null;
            if (!items.length) {
                return;
            }

            const sliderRect = slider.getBoundingClientRect();
            const sliderCenter = sliderRect.left + (sliderRect.width / 2);
            let activeItem = items[0];
            let activeDistance = Infinity;

            items.forEach(function (item) {
                const itemRect = item.getBoundingClientRect();
                const itemCenter = itemRect.left + (itemRect.width / 2);
                const distance = Math.abs(itemCenter - sliderCenter);
                if (distance < activeDistance) {
                    activeDistance = distance;
                    activeItem = item;
                }
            });

            items.forEach(function (item) {
                item.classList.toggle('is-active', item === activeItem);
            });
        }

        function scheduleActiveItemSync() {
            if (activeFrame !== null) {
                return;
            }
            activeFrame = window.requestAnimationFrame(syncActiveItem);
        }

        syncActiveItem();
        slider.addEventListener('scroll', scheduleActiveItemSync, { passive: true });
        window.addEventListener('resize', scheduleActiveItemSync);

        slider.addEventListener('dragstart', function (event) {
            event.preventDefault();
        });

        slider.addEventListener('pointerdown', function (event) {
            if (event.pointerType === 'mouse' && event.button !== 0) {
                return;
            }

            if (event.pointerType !== 'mouse') {
                return;
            }

            if (slider.scrollWidth <= slider.clientWidth) {
                return;
            }

            isDown = true;
            dragged = false;
            activePointerId = event.pointerId;
            startX = event.clientX;
            startScrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('pointermove', function (event) {
            if (!isDown || event.pointerId !== activePointerId) {
                return;
            }

            const delta = event.clientX - startX;
            if (!dragged && Math.abs(delta) > 5) {
                dragged = true;
                slider.classList.add('is-dragging');
                try {
                    slider.setPointerCapture(activePointerId);
                } catch (err) {}
            }

            if (dragged) {
                slider.scrollLeft = startScrollLeft - delta;
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

    function initAboutScrollButtons() {
        document.querySelectorAll('[data-about-scroll]').forEach(function (button) {
            button.addEventListener('click', function () {
                const scope = button.closest('.marcan-about-iconic') || document;
                const slider = scope.querySelector('[data-about-slider="timeline"]');
                if (!slider) {
                    return;
                }

                const direction = button.getAttribute('data-about-scroll') === 'prev' ? -1 : 1;
                const amount = Math.max(240, Math.round(slider.clientWidth * 0.8));
                slider.scrollBy({ left: amount * direction, top: 0, behavior: 'smooth' });
            });
        });
    }

    function initFooterAnimations() {
        if (!footerAnimationNodes.length) {
            return;
        }

        const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            footerAnimationNodes.forEach(function (node) {
                node.classList.add('is-footer-animated');
            });
            return;
        }

        const footerObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-footer-animated');
                footerObserver.unobserve(entry.target);
            });
        }, {
            threshold: 0.35,
            rootMargin: '0px 0px -8% 0px',
        });

        footerAnimationNodes.forEach(function (node) {
            footerObserver.observe(node);
        });
    }

    function initVirtualTour(tour) {
        const buttons = Array.prototype.slice.call(tour.querySelectorAll('[data-tour-src]'));
        let frame = tour.querySelector('iframe');
        const externalLink = tour.querySelector('[data-tour-external-link]');
        const poster = tour.querySelector('.marcan-property-tour-poster');
        const collapse = tour.querySelector('[data-tour-collapse]');
        const groupToggles = Array.prototype.slice.call(tour.querySelectorAll('[data-tour-group-toggle]'));
        const loading = tour.querySelector('[data-tour-loading]');

        if (!buttons.length || !frame) {
            return;
        }

        function syncTourHeight() {
            const header = document.querySelector('.marcan-site-header');
            const quote = document.querySelector('.marcan-property-sticky-quote');
            const headerHeight = header ? header.getBoundingClientRect().height : 0;
            const quoteHeight = quote ? quote.getBoundingClientRect().height : 0;
            tour.style.setProperty('--marcan-tour-available-height', Math.max(360, window.innerHeight - headerHeight - quoteHeight) + 'px');
        }

        function warmTourConnection() {
            if (document.querySelector('link[data-marcan-tour-preconnect]')) {
                return;
            }

            const preconnect = document.createElement('link');
            preconnect.rel = 'preconnect';
            preconnect.href = 'https://kuula.co';
            preconnect.crossOrigin = 'anonymous';
            preconnect.setAttribute('data-marcan-tour-preconnect', '');
            document.head.appendChild(preconnect);

            const dnsPrefetch = document.createElement('link');
            dnsPrefetch.rel = 'dns-prefetch';
            dnsPrefetch.href = '//kuula.co';
            dnsPrefetch.setAttribute('data-marcan-tour-preconnect', '');
            document.head.appendChild(dnsPrefetch);
        }

        syncTourHeight();
        window.addEventListener('resize', syncTourHeight);

        if (window.matchMedia('(max-width: 900px)').matches) {
            tour.classList.add('is-menu-collapsed');
            if (collapse) {
                collapse.setAttribute('aria-expanded', 'false');
                collapse.setAttribute('aria-label', 'Expandir selector de recorridos');
            }
        }

        if ('IntersectionObserver' in window) {
            const preloadObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    warmTourConnection();
                    preloadObserver.disconnect();
                });
            }, { rootMargin: '900px 0px' });
            preloadObserver.observe(tour);
        } else {
            warmTourConnection();
        }

        groupToggles.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const group = toggle.closest('.marcan-property-tour-group');
                if (!group) {
                    return;
                }
                const collapsed = group.classList.toggle('is-collapsed');
                toggle.setAttribute('aria-expanded', String(!collapsed));
            });
        });

        if (collapse) {
            collapse.addEventListener('click', function () {
                const collapsed = tour.classList.toggle('is-menu-collapsed');
                collapse.setAttribute('aria-expanded', String(!collapsed));
                collapse.setAttribute('aria-label', collapsed ? 'Expandir selector de recorridos' : 'Minimizar selector de recorridos');
            });
        }

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                const src = button.getAttribute('data-tour-src') || '';
                const external = button.getAttribute('data-tour-external') || src;
                const title = button.getAttribute('data-tour-title') || button.textContent.trim();

                if (!src) {
                    return;
                }

                if (loading) {
                    loading.hidden = false;
                }
                frame.setAttribute('title', title);
                frame.hidden = false;
                if (poster) {
                    poster.hidden = true;
                }

                frame.onload = function () {
                    if (loading) {
                        loading.hidden = true;
                    }
                };

                if (frame.getAttribute('src') !== src) {
                    frame.setAttribute('src', src);
                } else if (loading) {
                    loading.hidden = true;
                }

                if (externalLink) {
                    externalLink.setAttribute('href', external);
                    externalLink.hidden = false;
                }

                if (collapse && window.matchMedia('(max-width: 900px)').matches) {
                    tour.classList.add('is-menu-collapsed');
                    collapse.setAttribute('aria-expanded', 'false');
                    collapse.setAttribute('aria-label', 'Expandir selector de recorridos');
                }

                buttons.forEach(function (item) {
                    const active = item === button;
                    item.classList.toggle('is-active', active);
                    item.setAttribute('aria-pressed', String(active));
                });
            });
        });
    }

    function initPropertyFilters(filterBar) {
        const section = filterBar.closest('.marcan-property-units');
        if (!section) {
            return;
        }

        const checks = Array.prototype.slice.call(filterBar.querySelectorAll('[data-property-check]'));
        const ranges = Array.prototype.slice.call(filterBar.querySelectorAll('[data-property-range]'));
        const rows = Array.prototype.slice.call(section.querySelectorAll('[data-unit-row]'));
        const empty = section.querySelector('[data-property-units-empty]');
        const clear = section.querySelector('[data-property-filter-clear]');
        const toggle = section.querySelector('[data-property-filter-toggle]');
        const modalClose = filterBar.querySelector('[data-property-filter-close]');
        const modalClear = filterBar.querySelector('[data-property-filter-modal-clear]');
        const modalSave = filterBar.querySelector('[data-property-filter-save]');
        const responsiveFilters = window.matchMedia('(max-width: 900px)');

        if ((!checks.length && !ranges.length) || !rows.length) {
            return;
        }

        function formatNumber(value, unit) {
            const number = Number(value) || 0;
            if (unit === 'S/') {
                return 'S/ ' + new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 }).format(number);
            }
            return new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 }).format(number) + ' ' + unit;
        }

        function getRangeState(range) {
            const minControl = range.querySelector('[data-range-min]');
            const maxControl = range.querySelector('[data-range-max]');
            if (!minControl || !maxControl) {
                return null;
            }

            let min = Number(minControl.value);
            let max = Number(maxControl.value);
            if (min > max) {
                const nextMin = max;
                max = min;
                min = nextMin;
                minControl.value = String(min);
                maxControl.value = String(max);
            }

            return {
                key: range.getAttribute('data-property-range'),
                min: min,
                max: max,
            };
        }

        function syncRange(range) {
            const state = getRangeState(range);
            const unit = range.getAttribute('data-range-unit') || '';
            const minLabel = range.querySelector('[data-range-label="min"]');
            const maxLabel = range.querySelector('[data-range-label="max"]');

            if (!state) {
                return;
            }

            if (minLabel) {
                minLabel.textContent = formatNumber(state.min, unit);
            }
            if (maxLabel) {
                maxLabel.textContent = formatNumber(state.max, unit);
            }
        }

        function setUnitExpanded(card, expanded) {
            const detail = card.querySelector('[data-unit-detail]');
            const button = card.querySelector('[data-unit-toggle]');
            const icon = card.querySelector('.marcan-property-unit-toggle-icon');

            card.classList.toggle('is-expanded', expanded);
            if (detail) {
                detail.hidden = !expanded;
            }
            if (button) {
                button.setAttribute('aria-expanded', String(expanded));
            }
            if (icon) {
                icon.textContent = expanded ? '−' : '+';
            }
        }

        function scrollSharedUnitIntoView(card) {
            const rect = card.getBoundingClientRect();
            const headerOffset = window.matchMedia('(max-width: 900px)').matches ? 68 : 90;
            const availableHeight = Math.max(320, window.innerHeight - headerOffset);
            const topMargin = rect.height > availableHeight
                ? Math.max(24, (window.innerHeight - rect.height) / 2)
                : headerOffset + ((availableHeight - rect.height) / 2);
            let target = window.scrollY + rect.top - topMargin;
            const maxScroll = Math.max(0, document.documentElement.scrollHeight - window.innerHeight);
            target = Math.max(0, Math.min(maxScroll, target));

            document.body.classList.add('is-shared-unit-scrolling');
            window.scrollTo({ top: target, behavior: 'smooth' });
            window.setTimeout(function () {
                document.body.classList.remove('is-shared-unit-scrolling');
            }, 950);
        }

        function applyFilters() {
            const activeChecks = checks.reduce(function (filters, control) {
                const key = control.getAttribute('data-property-check');
                if (key && control.checked) {
                    if (!filters[key]) {
                        filters[key] = [];
                    }
                    filters[key].push(control.value);
                }
                return filters;
            }, {});
            const activeRanges = ranges.map(getRangeState).filter(Boolean);
            let visibleCount = 0;
            let firstVisible = null;
            let hasExpandedVisible = false;

            rows.forEach(function (row) {
                const checksMatch = Object.keys(activeChecks).every(function (key) {
                    const value = row.getAttribute('data-' + key) || '';
                    return activeChecks[key].indexOf(value) !== -1;
                });
                const rangesMatch = activeRanges.every(function (range) {
                    const value = Number(row.getAttribute('data-' + range.key)) || 0;
                    return value >= range.min && value <= range.max;
                });
                const visible = checksMatch && rangesMatch;
                row.classList.toggle('is-hidden', !visible);
                if (!visible) {
                    setUnitExpanded(row, false);
                }
                if (visible) {
                    if (!firstVisible) {
                        firstVisible = row;
                    }
                    if (row.classList.contains('is-expanded')) {
                        hasExpandedVisible = true;
                    }
                    visibleCount += 1;
                }
            });

            if (firstVisible && !hasExpandedVisible) {
                setUnitExpanded(firstVisible, true);
            }

            if (empty) {
                empty.hidden = visibleCount > 0;
            }
        }

        checks.forEach(function (control) {
            control.addEventListener('change', applyFilters);
        });

        ranges.forEach(function (range) {
            const inputs = Array.prototype.slice.call(range.querySelectorAll('input[type="range"]'));
            syncRange(range);
            inputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    syncRange(range);
                    applyFilters();
                });
            });
        });

        rows.forEach(function (row) {
            const button = row.querySelector('[data-unit-toggle]');
            if (!button) {
                return;
            }

            button.addEventListener('click', function () {
                const shouldExpand = !row.classList.contains('is-expanded');
                rows.forEach(function (item) {
                    setUnitExpanded(item, false);
                });
                setUnitExpanded(row, shouldExpand);
            });
        });

        function copyShareUrl(url) {
            if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
                return navigator.clipboard.writeText(url);
            }

            return new Promise(function (resolve, reject) {
                const input = document.createElement('textarea');
                input.value = url;
                input.setAttribute('readonly', '');
                input.style.position = 'fixed';
                input.style.opacity = '0';
                document.body.appendChild(input);
                input.select();
                try {
                    document.execCommand('copy') ? resolve() : reject(new Error('Copy failed'));
                } catch (error) {
                    reject(error);
                }
                document.body.removeChild(input);
            });
        }

        function getUnitShareSheet() {
            let sheet = document.querySelector('[data-unit-share-sheet]');
            if (sheet) {
                return sheet;
            }

            sheet = document.createElement('div');
            sheet.className = 'marcan-unit-share-sheet';
            sheet.setAttribute('data-unit-share-sheet', '');
            sheet.hidden = true;
            sheet.innerHTML = '<div class="marcan-unit-share-sheet-backdrop" data-unit-share-sheet-close></div><div class="marcan-unit-share-sheet-panel" role="dialog" aria-modal="true" aria-label="Compartir tipología"><span class="marcan-unit-share-sheet-handle" aria-hidden="true"></span><h3>Compartir</h3><div class="marcan-unit-share-sheet-actions"><button class="marcan-unit-share-option is-native" type="button" data-share-sheet-native><span aria-hidden="true"></span><strong>Compartir</strong></button><a class="marcan-unit-share-option is-whatsapp" data-share-sheet-whatsapp target="_blank" rel="noreferrer"><span aria-hidden="true"></span><strong>WhatsApp</strong></a><a class="marcan-unit-share-option is-email" data-share-sheet-email><span aria-hidden="true"></span><strong>Correo</strong></a><button class="marcan-unit-share-option is-copy" type="button" data-share-sheet-copy><span aria-hidden="true"></span><strong>Copiar</strong></button></div><p data-share-sheet-message hidden></p><button class="marcan-unit-share-sheet-cancel" type="button" data-unit-share-sheet-close>Cancelar</button></div>';
            document.body.appendChild(sheet);
            sheet.addEventListener('click', function (event) {
                if (event.target.closest('[data-unit-share-sheet-close]')) {
                    sheet.hidden = true;
                    document.body.classList.remove('has-unit-share-sheet');
                }
            });

            return sheet;
        }

        function openUnitShareSheet(url, title, onCopy) {
            const sheet = getUnitShareSheet();
            const native = sheet.querySelector('[data-share-sheet-native]');
            const whatsapp = sheet.querySelector('[data-share-sheet-whatsapp]');
            const email = sheet.querySelector('[data-share-sheet-email]');
            const copy = sheet.querySelector('[data-share-sheet-copy]');
            const message = sheet.querySelector('[data-share-sheet-message]');

            if (native) {
                native.hidden = typeof navigator.share !== 'function';
                native.onclick = function () {
                    if (typeof navigator.share === 'function') {
                        navigator.share({ title: title, url: url }).catch(function () {});
                    }
                };
            }
            if (whatsapp) {
                whatsapp.href = 'https://wa.me/?text=' + encodeURIComponent(title + ' ' + url);
            }
            if (email) {
                email.href = 'mailto:?subject=' + encodeURIComponent(title) + '&body=' + encodeURIComponent(url);
            }
            if (message) {
                message.hidden = true;
                message.textContent = '';
            }
            if (copy) {
                copy.onclick = function () {
                    copyShareUrl(url).then(function () {
                        if (message) {
                            message.textContent = 'Enlace copiado';
                            message.hidden = false;
                        }
                        if (typeof onCopy === 'function') {
                            onCopy('Enlace copiado');
                        }
                    }).catch(function () {
                        window.prompt('Copia este enlace para compartir la tipología:', url);
                    });
                };
            }

            sheet.hidden = false;
            document.body.classList.add('has-unit-share-sheet');
        }

        section.querySelectorAll('[data-unit-share]').forEach(function (button) {
            const label = button.querySelector('span');
            const defaultLabel = label ? label.textContent : '';
            let resetTimer = null;

            function showShareResult(message) {
                if (!label) {
                    return;
                }
                label.textContent = message;
                window.clearTimeout(resetTimer);
                resetTimer = window.setTimeout(function () {
                    label.textContent = defaultLabel;
                }, 2200);
            }

            function offerManualCopy(url) {
                window.prompt('Copia este enlace para compartir la tipología:', url);
                showShareResult('Enlace listo');
            }

            button.addEventListener('click', function () {
                const url = button.getAttribute('data-share-url') || window.location.href;
                const title = button.getAttribute('data-share-title') || document.title;
                const isMobileShare = window.matchMedia('(max-width: 900px)').matches;

                if (isMobileShare && navigator.share) {
                    navigator.share({ title: title, url: url }).catch(function (error) {
                        if (error && error.name !== 'AbortError') {
                            openUnitShareSheet(url, title, showShareResult);
                        }
                    });
                    return;
                }

                if (isMobileShare) {
                    openUnitShareSheet(url, title, showShareResult);
                    return;
                }

                copyShareUrl(url).then(function () {
                    showShareResult('Enlace copiado');
                }).catch(function () {
                    offerManualCopy(url);
                });
            });
        });

        function openSharedUnit() {
            const params = new URLSearchParams(window.location.search);
            let slug = params.get('tipologia') || '';
            if (!slug && window.location.hash.indexOf('#tipologia-') === 0) {
                slug = decodeURIComponent(window.location.hash.replace('#tipologia-', ''));
            }
            if (!slug) {
                return;
            }

            const sharedRow = rows.find(function (row) {
                return row.getAttribute('data-unit-slug') === slug;
            });
            if (!sharedRow) {
                return;
            }

            rows.forEach(function (row) {
                setUnitExpanded(row, row === sharedRow);
            });
            window.requestAnimationFrame(function () {
                scrollSharedUnitIntoView(sharedRow);
            });
        }

        function setFilterBarOpen(open) {
            filterBar.hidden = !open;
            filterBar.classList.toggle('is-mobile-open', open && responsiveFilters.matches);
            document.body.classList.toggle('has-property-filter-modal', open && responsiveFilters.matches);
            if (toggle) {
                toggle.setAttribute('aria-expanded', String(open));
                const label = toggle.querySelector('span');
                if (label) {
                    label.textContent = open && !responsiveFilters.matches ? 'Ocultar Filtros' : 'Mostrar Filtros';
                }
            }
        }

        function syncFilterLayout() {
            setFilterBarOpen(!responsiveFilters.matches);
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                setFilterBarOpen(filterBar.hidden);
            });
        }

        if (clear) {
            clear.addEventListener('click', function () {
                checks.forEach(function (control) {
                    control.checked = false;
                });
                ranges.forEach(function (range) {
                    const minControl = range.querySelector('[data-range-min]');
                    const maxControl = range.querySelector('[data-range-max]');
                    if (minControl) {
                        minControl.value = minControl.getAttribute('min') || minControl.value;
                    }
                    if (maxControl) {
                        maxControl.value = maxControl.getAttribute('max') || maxControl.value;
                    }
                    syncRange(range);
                });
                applyFilters();
            });
        }

        if (modalClear && clear) {
            modalClear.addEventListener('click', function () {
                clear.click();
            });
        }

        if (modalClose) {
            modalClose.addEventListener('click', function () {
                setFilterBarOpen(false);
            });
        }

        if (modalSave) {
            modalSave.addEventListener('click', function () {
                setFilterBarOpen(false);
            });
        }

        if (typeof responsiveFilters.addEventListener === 'function') {
            responsiveFilters.addEventListener('change', syncFilterLayout);
        } else if (typeof responsiveFilters.addListener === 'function') {
            responsiveFilters.addListener(syncFilterLayout);
        }

        applyFilters();
        syncFilterLayout();
        openSharedUnit();
    }

    function initUnitPlanZoom(plan) {
        const image = plan.querySelector('img');
        if (!image) {
            return;
        }

        const lens = document.createElement('span');
        const lensImage = image.cloneNode(false);
        const zoom = 1.85;
        lens.className = 'marcan-property-unit-plan-lens';
        lens.setAttribute('aria-hidden', 'true');
        lensImage.removeAttribute('srcset');
        lensImage.removeAttribute('sizes');
        lens.appendChild(lensImage);
        plan.appendChild(lens);

        function updateLens(event) {
            plan.classList.add('is-lens-active');
            const planRect = plan.getBoundingClientRect();
            const imageRect = image.getBoundingClientRect();
            const lensSize = lens.offsetWidth || 130;
            if (!planRect.width || !planRect.height || !imageRect.width || !imageRect.height) {
                return;
            }

            const x = Math.max(0, Math.min(imageRect.width, event.clientX - imageRect.left));
            const y = Math.max(0, Math.min(imageRect.height, event.clientY - imageRect.top));
            const imageLeft = imageRect.left - planRect.left;
            const imageTop = imageRect.top - planRect.top;
            const lensLeft = imageLeft + Math.max(0, Math.min(imageRect.width - lensSize, x - (lensSize / 2)));
            const lensTop = imageTop + Math.max(0, Math.min(imageRect.height - lensSize, y - (lensSize / 2)));
            const lensCenterX = (lensLeft - imageLeft) + (lensSize / 2);
            const lensCenterY = (lensTop - imageTop) + (lensSize / 2);

            lens.style.transform = 'translate(' + lensLeft.toFixed(1) + 'px, ' + lensTop.toFixed(1) + 'px)';
            lensImage.style.width = imageRect.width.toFixed(1) + 'px';
            lensImage.style.height = imageRect.height.toFixed(1) + 'px';
            lensImage.style.left = ((lensSize / 2) - (lensCenterX * zoom)).toFixed(1) + 'px';
            lensImage.style.top = ((lensSize / 2) - (lensCenterY * zoom)).toFixed(1) + 'px';
            lensImage.style.transform = 'scale(' + zoom + ')';
        }

        function showLens(event) {
            plan.classList.add('is-lens-active');
            updateLens(event);
        }

        image.addEventListener('pointerenter', showLens);
        image.addEventListener('pointermove', updateLens);
        image.addEventListener('pointerdown', showLens);
        image.addEventListener('pointerup', function () {
            if (window.matchMedia('(hover: none)').matches) {
                plan.classList.remove('is-lens-active');
            }
        });
        image.addEventListener('pointerleave', function () {
            plan.classList.remove('is-lens-active');
        });
        image.addEventListener('mouseenter', showLens);
        image.addEventListener('mousemove', updateLens);
        image.addEventListener('mouseleave', function () {
            plan.classList.remove('is-lens-active');
        });
    }

    function initPropertyGallery(gallery) {
        const track = gallery.querySelector('[data-gallery-track]');
        const buttons = Array.prototype.slice.call(gallery.querySelectorAll('[data-gallery-jump]'));
        const items = Array.prototype.slice.call(gallery.querySelectorAll('[data-gallery-item]'));
        let isDown = false;
        let startX = 0;
        let startScrollLeft = 0;
        let dragged = false;
        let activePointerId = null;

        if (!track || !items.length) {
            return;
        }

        track.addEventListener('dragstart', function (event) {
            event.preventDefault();
        });

        track.addEventListener('pointerdown', function (event) {
            if (event.pointerType === 'mouse' && event.button !== 0) {
                return;
            }

            if (track.scrollWidth <= track.clientWidth) {
                return;
            }

            isDown = true;
            dragged = false;
            activePointerId = event.pointerId;
            startX = event.clientX;
            startScrollLeft = track.scrollLeft;
        });

        track.addEventListener('pointermove', function (event) {
            if (!isDown || event.pointerId !== activePointerId) {
                return;
            }

            const delta = event.clientX - startX;
            if (!dragged && Math.abs(delta) > 5) {
                dragged = true;
                track.classList.add('is-dragging');
                try {
                    track.setPointerCapture(activePointerId);
                } catch (err) {}
            }

            if (dragged) {
                track.scrollLeft = startScrollLeft - delta;
                event.preventDefault();
            }
        });

        function endDrag(event) {
            if (!isDown || event.pointerId !== activePointerId) {
                return;
            }
            isDown = false;
            track.classList.remove('is-dragging');
            try {
                track.releasePointerCapture(activePointerId);
            } catch (err) {}
            activePointerId = null;
        }

        track.addEventListener('pointerup', endDrag);
        track.addEventListener('pointercancel', endDrag);
        track.addEventListener('pointerleave', endDrag);
        track.addEventListener('click', function (event) {
            if (!dragged) {
                return;
            }
            event.preventDefault();
            event.stopPropagation();
            dragged = false;
        }, true);

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                const index = Number(button.getAttribute('data-gallery-jump')) || 0;
                const item = items[index];
                if (!item) {
                    return;
                }

                track.scrollTo({
                    left: item.offsetLeft - track.offsetLeft,
                    top: 0,
                    behavior: 'smooth',
                });

                buttons.forEach(function (navButton) {
                    navButton.classList.toggle('is-active', navButton === button);
                });
            });
        });

        track.addEventListener('scroll', function () {
            const center = track.scrollLeft + track.clientWidth * 0.25;
            let activeIndex = 0;
            let activeDistance = Infinity;

            items.forEach(function (item, index) {
                const distance = Math.abs(item.offsetLeft - center);
                if (distance < activeDistance) {
                    activeDistance = distance;
                    activeIndex = index;
                }
            });

            buttons.forEach(function (button, index) {
                button.classList.toggle('is-active', index === activeIndex);
            });
        }, { passive: true });

        gallery.querySelectorAll('[data-gallery-image]').forEach(function (button) {
            button.addEventListener('click', function () {
                const src = button.getAttribute('data-gallery-image') || '';
                const title = button.getAttribute('data-gallery-title') || '';
                if (!src) {
                    return;
                }

                let dialog = document.querySelector('[data-gallery-lightbox-dialog]');
                if (!dialog) {
                    dialog = document.createElement('dialog');
                    dialog.className = 'marcan-property-gallery-lightbox';
                    dialog.setAttribute('data-gallery-lightbox-dialog', '');
                    dialog.innerHTML = '<div><img data-gallery-lightbox-image alt=""><p data-gallery-lightbox-title></p><button type="button" data-gallery-lightbox-close aria-label="Cerrar"></button></div>';
                    document.body.appendChild(dialog);
                    dialog.addEventListener('click', function (event) {
                        if (event.target === dialog) {
                            dialog.close();
                        }
                    });
                    dialog.querySelector('[data-gallery-lightbox-close]').addEventListener('click', function () {
                        dialog.close();
                    });
                }

                const image = dialog.querySelector('[data-gallery-lightbox-image]');
                const caption = dialog.querySelector('[data-gallery-lightbox-title]');
                if (image) {
                    image.setAttribute('src', src);
                    image.setAttribute('alt', title);
                }
                if (caption) {
                    caption.textContent = title;
                }

                if (typeof dialog.showModal === 'function') {
                    dialog.showModal();
                } else {
                    window.open(src, '_blank', 'noopener');
                }
            });
        });
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
        document.querySelectorAll('[data-about-slider]').forEach(initAboutSlider);
        virtualTours.forEach(initVirtualTour);
        propertyFilterBars.forEach(initPropertyFilters);
        document.querySelectorAll('[data-unit-plan-zoom]').forEach(initUnitPlanZoom);
        propertyGalleries.forEach(initPropertyGallery);
        initAboutScrollButtons();
        initFooterAnimations();
    });
}());
