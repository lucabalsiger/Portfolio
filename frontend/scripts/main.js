// Hero intro: fit each line to container width, respecting size-factor and stretch-x
(function fitIntroLines() {
    const container = document.querySelector('[data-module="hero-front"] .intro-display');
    if (!container) return;
    function fit() {
        // On mobile CSS handles font sizing; skip JS scaling
        if (window.innerWidth <= 768) {
            container.querySelectorAll('.intro-line').forEach(line => {
                line.style.fontSize = '';
                line.style.transform = '';
            });
            container.classList.add('is-fitted');
            return;
        }
        const w = container.offsetWidth;
        container.querySelectorAll('.intro-line').forEach(line => {
            line.style.fontSize = '100px';
            line.style.transform = 'none';
            const scrollW = line.scrollWidth;
            const cs = getComputedStyle(line);
            const sizeFactor = parseFloat(cs.getPropertyValue('--size-factor')) || 1;
            const stretchX   = parseFloat(cs.getPropertyValue('--stretch-x'))   || 1;
            line.style.fontSize  = (100 * w * sizeFactor / (stretchX * scrollW)) + 'px';
            line.style.transform = stretchX !== 1 ? `scaleX(${stretchX})` : '';
        });
        container.classList.add('is-fitted');
    }
    document.fonts.ready.then(fit);
    window.addEventListener('resize', fit);
})();

// Scroll reveal
document.body.classList.add('js');

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.08, rootMargin: '0px 0px -32px 0px' });

document.querySelectorAll('[data-module]:not([data-module="overview-projects"]), .teaser-project').forEach(el => {
    revealObserver.observe(el);
});

// Reduzierte Bewegung: Scramble und magnetische Links werden dann nicht ausgeführt (wie im CSS)
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Text scramble on brand name hover (nur wenn kein Logo-Bild vorhanden)
const brand = document.querySelector('[data-layout="header"] .brand');
if (brand && !brand.querySelector('img') && !reduceMotion) {
    const textNode = brand.firstChild;
    const original = textNode.textContent;
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789!?#@';
    let raf = null;
    let startTime = null;

    brand.addEventListener('mouseenter', () => {
        cancelAnimationFrame(raf);
        startTime = null;
        function scramble(ts) {
            if (!startTime) startTime = ts;
            const progress = Math.min((ts - startTime) / 600, 1);
            const resolved = Math.floor(progress * original.length);
            textNode.textContent = original.split('').map((char, i) => {
                if (i < resolved) return char;
                return chars[Math.floor(Math.random() * chars.length)];
            }).join('');
            if (progress < 1) {
                raf = requestAnimationFrame(scramble);
            } else {
                textNode.textContent = original;
            }
        }
        raf = requestAnimationFrame(scramble);
    });

    brand.addEventListener('mouseleave', () => {
        cancelAnimationFrame(raf);
        textNode.textContent = original;
    });
}

// Magnetic nav links
if (!reduceMotion) {
    document.querySelectorAll('[data-layout="header"] .nav a, [data-layout="header"] .brand').forEach(el => {
        el.addEventListener('mouseenter', () => {
            el.style.transition = 'transform 0.1s ease, background-color 0.18s, color 0.18s';
        });
        el.addEventListener('mousemove', e => {
            const rect = el.getBoundingClientRect();
            const dx = (e.clientX - (rect.left + rect.width / 2)) * 0.3;
            const dy = (e.clientY - (rect.top + rect.height / 2)) * 0.3;
            el.style.transform = `translate(${dx}px, ${dy}px)`;
        });
        el.addEventListener('mouseleave', () => {
            el.style.transition = 'transform 0.5s cubic-bezier(0.23, 1, 0.32, 1), background-color 0.18s, color 0.18s';
            el.style.transform = '';
            setTimeout(() => { el.style.transition = ''; }, 500);
        });
    });
}

// Dark mode: Startwert setzt bereits das Inline-Script in header.php, hier nur noch das Umschalten
const html = document.documentElement;
const themeToggle = document.getElementById('theme-toggle');

// aria-pressed zeigt Screenreadern, ob der Dark Mode aktiv ist
function updateThemePressed() {
    themeToggle?.setAttribute('aria-pressed', html.getAttribute('data-theme') === 'dark' ? 'true' : 'false');
}

function setTheme(theme) {
    html.setAttribute('data-theme', theme);
    updateThemePressed();
    try {
        localStorage.setItem('theme', theme);
    } catch (e) {}
}

updateThemePressed();

themeToggle?.addEventListener('click', () => {
    setTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
});

// Custom cursor (desktop only)
if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    const cursor = document.createElement('div');
    cursor.id = 'cursor';
    cursor.setAttribute('aria-hidden', 'true');
    document.body.appendChild(cursor);

    let mx = -100, my = -100, cx = -100, cy = -100;

    document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });

    (function tick() {
        cx += (mx - cx) * 0.5;
        cy += (my - cy) * 0.5;
        cursor.style.left = cx + 'px';
        cursor.style.top = cy + 'px';
        requestAnimationFrame(tick);
    })();

    document.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('mouseenter', () => cursor.classList.add('cursor--hover'));
        el.addEventListener('mouseleave', () => cursor.classList.remove('cursor--hover'));
    });

    document.querySelectorAll('.filmstrip-item').forEach(el => {
        el.addEventListener('mouseenter', () => cursor.classList.add('cursor--zoom'));
        el.addEventListener('mouseleave', () => cursor.classList.remove('cursor--zoom'));
    });
}
