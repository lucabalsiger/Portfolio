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

// Aufrufe und Applaus auf der Projektseite: zählt über die REST-Endpunkte in backend/rest/
const projectStats = document.querySelector('.project-stats');
if (projectStats) {
    const id = projectStats.dataset.projectId;
    const readonly = projectStats.hasAttribute('data-readonly'); // angemeldet: nichts zählen
    const viewsEl = projectStats.querySelector('.stats-views');
    const applauseEl = projectStats.querySelector('.stats-applause');
    const button = projectStats.querySelector('.applause-button');
    const message = projectStats.querySelector('.stats-message');

    // Projekte mit Applaus merkt sich der Browser dauerhaft, der Server sperrt nur einen Tag
    function getApplauded() {
        try {
            return JSON.parse(localStorage.getItem('applauded')) || [];
        } catch (e) {
            return [];
        }
    }
    function rememberApplause() {
        try {
            localStorage.setItem('applauded', JSON.stringify([...new Set([...getApplauded(), id])]));
        } catch (e) {}
    }

    function showStats(data) {
        viewsEl.textContent = data.views.toLocaleString(html.lang);
        applauseEl.textContent = data.applause.toLocaleString(html.lang);
    }
    function markApplauded() {
        button.disabled = true;
        button.setAttribute('aria-pressed', 'true');
        button.textContent = 'applaudiert';
    }
    async function send(url) {
        const response = await fetch(url, { method: 'POST' });
        if (!response.ok) throw new Error(response.status);
        return response.json();
    }

    if (getApplauded().includes(id)) markApplauded();

    // Aufruf zählen: still im Hintergrund, ohne Meldung bei einem Fehler
    if (!readonly) {
        send(projectStats.dataset.viewUrl).then(showStats).catch(() => {});
    }

    button.addEventListener('click', async () => {
        if (readonly) {
            message.textContent = 'du bist angemeldet, dein applaus wird nicht gezählt.';
            return;
        }
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        message.textContent = '';
        try {
            const data = await send(projectStats.dataset.applauseUrl);
            showStats(data);
            rememberApplause();
            markApplauded();
            message.textContent = data.counted ? 'danke für deinen applaus!' : 'du hast schon applaudiert.';
            applauseEl.classList.add('is-bumped');
            applauseEl.addEventListener('animationend', () => applauseEl.classList.remove('is-bumped'), { once: true });
        } catch (e) {
            button.disabled = false;
            message.textContent = 'das hat nicht geklappt, bitte versuch es nochmals.';
        } finally {
            button.removeAttribute('aria-busy');
        }
    });
}

// Kontaktformular: sendet per fetch() an backend/rest/_contact.php und zeigt Fehler direkt beim Feld
document.querySelectorAll('.contact-form').forEach(form => {
    form.noValidate = true; // eigene Meldungen statt der Hinweisblasen des Browsers, geprüft wird auf dem Server
    const button = form.querySelector('.form-submit');
    const status = form.querySelector('.form-status');
    const fallback = 'das hat nicht geklappt. bitte prüfe deine verbindung und versuch es nochmals.';

    function setFieldError(name, text) {
        const field = form.elements[name];
        const error = form.querySelector(`[data-error-for="${name}"]`);
        error.textContent = text;
        if (text) {
            field.setAttribute('aria-invalid', 'true');
        } else {
            field.removeAttribute('aria-invalid');
        }
    }
    function showStatus(text, state) {
        status.textContent = text;
        status.dataset.state = state;
    }

    form.addEventListener('submit', async e => {
        e.preventDefault();
        ['name', 'email', 'message'].forEach(name => setFieldError(name, ''));
        showStatus('wird gesendet …', 'loading');
        button.disabled = true;
        form.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form) });
            let data = {};
            try {
                data = await response.json();
            } catch (error) {}
            if (response.ok) {
                form.reset();
                showStatus(data.message, 'success');
            } else {
                Object.entries(data.data?.fields || {}).forEach(([name, text]) => setFieldError(name, text));
                showStatus(data.message || fallback, 'error');
                form.querySelector('[aria-invalid="true"]')?.focus();
            }
        } catch (error) {
            showStatus(fallback, 'error'); // keine Verbindung
        } finally {
            button.disabled = false;
            form.removeAttribute('aria-busy');
        }
    });
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

    // Event-Delegation: ein Listener am document, wirkt auch für Elemente, die später ins HTML kommen
    document.addEventListener('pointerover', e => {
        cursor.classList.toggle('cursor--hover', !!e.target.closest('a, button'));
        cursor.classList.toggle('cursor--zoom', !!e.target.closest('.filmstrip-item'));
    });
}
