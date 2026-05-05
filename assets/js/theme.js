(function () {
    const header = document.querySelector('[data-marcan-header]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const primaryNav = document.querySelector('[data-primary-nav]');
    const revealNodes = document.querySelectorAll('[data-reveal]');
    const introHero = document.querySelector('.intro-hero');

    function syncHeader() {
        if (!header) {
            return;
        }

        const scrolled = window.scrollY > 24;
        const shouldShow = !introHero || window.scrollY > window.innerHeight * 0.65;

        header.classList.toggle('is-visible', shouldShow);
        header.classList.toggle('is-scrolled', scrolled);
    }

    if (menuToggle && primaryNav) {
        menuToggle.addEventListener('click', function () {
            const isOpen = primaryNav.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', String(isOpen));
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
        document.querySelectorAll('.intro-logo').forEach(function (node) {
            node.classList.add('is-visible');
        });
    });
}());
