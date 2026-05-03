// ─────────────────────────────────────────────────────────
//  VESTES — Visual Effects Script  |  Lab 3, Sarcina 3
// ─────────────────────────────────────────────────────────


// ── 1. FADE-IN ON SCROLL ──
(function initScrollReveal() {
    const products = document.querySelectorAll('.product');
    if (!products.length) return;

    products.forEach(function (card) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });

    function revealOnScroll() {
        products.forEach(function (card, index) {
            const rect = card.getBoundingClientRect();
            if (rect.top < window.innerHeight - 80) {
                setTimeout(function () {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 120);
            }
        });
    }

    window.addEventListener('scroll', revealOnScroll);
    // Небольшая задержка чтобы страница успела отрендериться
    setTimeout(revealOnScroll, 100);
})();


// ── 2. HEADER SHRINK ON SCROLL ──
(function initHeaderShrink() {
    const header = document.querySelector('header');
    if (!header) return;

    window.addEventListener('scroll', function () {
        if (window.scrollY > 60) {
            header.style.padding = '16px 64px';
            header.style.transition = 'padding 0.4s ease';
        } else {
            header.style.padding = '32px 64px';
        }
    });
})();


// ── 3. ACTIVE NAV HIGHLIGHT ──
(function initActiveNav() {
    const links = document.querySelectorAll('.menu a');
    const currentFile = window.location.pathname.split('/').pop();

    links.forEach(function (link) {
        const linkFile = link.getAttribute('href').split('/').pop();
        if (linkFile === currentFile) {
            link.style.borderBottom = '1px solid black';
        }
    });
})();


// ── 4. SMOOTH PAGE TRANSITIONS ──
(function initPageTransitions() {
    // Fade in immediately on load
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.4s ease';

    // Use both DOMContentLoaded and load to ensure it triggers
    function fadeIn() {
        document.body.style.opacity = '1';
    }
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(fadeIn, 50);
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(fadeIn, 50);
        });
    }
    window.addEventListener('load', fadeIn);

    // Fade out on link click — skip form submits and CGI links
    document.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            // Skip: empty, anchor, mailto, tel, javascript, CGI scripts
            if (!href) return;
            if (href.startsWith('#')) return;
            if (href.startsWith('mailto')) return;
            if (href.startsWith('tel')) return;
            if (href.startsWith('javascript')) return;
            if (href.indexOf('cgi-bin') !== -1) return;

            e.preventDefault();
            document.body.style.opacity = '0';
            setTimeout(function () {
                window.location.href = href;
            }, 350);
        });
    });
})();


// ── 5. PRODUCT CARD TILT EFFECT ──
(function initTiltEffect() {
    const cards = document.querySelectorAll('.product');
    if (!cards.length) return;

    cards.forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const cx = rect.width / 2;
            const cy = rect.height / 2;

            const rotateX = ((y - cy) / cy) * -6;
            const rotateY = ((x - cx) / cx) * 6;

            card.style.transform = 'scale(1.03) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg)';
            card.style.transition = 'transform 0.1s ease';
        });

        card.addEventListener('mouseleave', function () {
            card.style.transform = 'scale(1) rotateX(0deg) rotateY(0deg)';
            card.style.transition = 'transform 0.4s ease';
        });
    });
})();


// ── 6. HERO TEXT TYPEWRITER (index.html only) ──
(function initTypewriter() {
    const hero = document.querySelector('.hero h1');
    if (!hero) return;

    const fullText = hero.textContent;
    hero.textContent = '';
    hero.style.borderRight = '2px solid white';

    let i = 0;
    const interval = setInterval(function () {
        hero.textContent += fullText[i];
        i++;
        if (i >= fullText.length) {
            clearInterval(interval);
            setTimeout(function () {
                hero.style.borderRight = 'none';
            }, 800);
        }
    }, 80);
})();
