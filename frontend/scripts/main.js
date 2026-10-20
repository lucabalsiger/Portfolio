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

// Text scramble on brand name hover
const brand = document.querySelector('[data-layout="header"] .brand');
if (brand && !brand.querySelector('img')) {
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

// Dark mode
const html = document.documentElement;
const themeToggle = document.getElementById('theme-toggle');

function setTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

const saved = localStorage.getItem('theme');
if (saved) {
    setTheme(saved);
} else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    setTheme('dark');
}

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
}
