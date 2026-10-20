<?php
$images = $args['images'] ?? [];
if (!$images) return;
$count = count($images);
$single = $count === 1;
?>
<div class="filmstrip-wrapper">
    <div class="filmstrip">
        <?php foreach ($images as $i => $image): ?>
            <div class="filmstrip-item">
                <img src="<?= esc_url($image['full']) ?>" alt="<?= esc_attr($image['alt']) ?>" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($count > 1): ?>
        <div class="filmstrip-controls">
            <button class="filmstrip-prev" type="button" aria-label="Vorheriges Bild">&#8592;</button>
            <span class="filmstrip-counter"><span class="filmstrip-current">1</span> / <?= $count ?></span>
            <button class="filmstrip-next" type="button" aria-label="Nächstes Bild">&#8594;</button>
        </div>
    <?php endif; ?>

    <dialog class="filmstrip-lightbox" aria-label="Bildansicht"<?= $single ? ' data-single' : '' ?>>
        <button class="filmstrip-lightbox-close" type="button" aria-label="Schliessen">&times;</button>
        <button class="filmstrip-lightbox-prev" type="button" aria-label="Vorheriges">&lsaquo;</button>
        <img class="filmstrip-lightbox-image" src="" alt="">
        <button class="filmstrip-lightbox-next" type="button" aria-label="Nächstes">&rsaquo;</button>
    </dialog>
</div>
<script>
(function () {
    const wrapper = document.currentScript.previousElementSibling;
    const strip = wrapper.querySelector('.filmstrip');
    const items = [...strip.querySelectorAll('.filmstrip-item')];
    const counter = wrapper.querySelector('.filmstrip-current');
    const prev = wrapper.querySelector('.filmstrip-prev');
    const next = wrapper.querySelector('.filmstrip-next');
    let current = 0;
    const INTERVAL = 3000;
    let timer = null;
    let startedAt = null;
    let remaining = INTERVAL;
    let isHovered = false;

    function goTo(index) {
        current = (index + items.length) % items.length;
        const item = items[current];
        strip.scrollTo({
            left: item.offsetLeft - (strip.clientWidth - item.offsetWidth) / 2,
            behavior: 'smooth'
        });
        if (counter) counter.textContent = current + 1;
    }

    function startAutoplay() {
        if (isHovered) return;
        clearTimeout(timer);
        startedAt = Date.now();
        timer = setTimeout(function tick() {
            goTo(current + 1);
            remaining = INTERVAL;
            startedAt = Date.now();
            timer = setTimeout(tick, INTERVAL);
        }, remaining);
    }

    function stopAutoplay() {
        if (startedAt !== null) {
            remaining = Math.max(0, remaining - (Date.now() - startedAt));
        }
        clearTimeout(timer);
        timer = null;
        startedAt = null;
    }

    prev?.addEventListener('click', () => goTo(current - 1));
    next?.addEventListener('click', () => goTo(current + 1));

    wrapper.addEventListener('pointerenter', () => { isHovered = true;  stopAutoplay(); });
    wrapper.addEventListener('pointerleave', () => { isHovered = false; startAutoplay(); });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) stopAutoplay();
        else startAutoplay();
    });

    // Drag to scroll
    let isDown = false, startX, scrollLeft;
    strip.addEventListener('mousedown', e => { isDown = true; startX = e.pageX - strip.offsetLeft; scrollLeft = strip.scrollLeft; });
    strip.addEventListener('mouseleave', () => isDown = false);
    strip.addEventListener('mouseup',    () => isDown = false);
    strip.addEventListener('mousemove', e => {
        if (!isDown) return;
        e.preventDefault();
        strip.scrollLeft = scrollLeft - (e.pageX - strip.offsetLeft - startX) * 1.5;
    });

    // Lightbox
    const lightbox = wrapper.querySelector('.filmstrip-lightbox');
    if (lightbox && typeof lightbox.showModal === 'function') {
        const lbImage = lightbox.querySelector('.filmstrip-lightbox-image');
        const lbClose = lightbox.querySelector('.filmstrip-lightbox-close');
        const lbPrev  = lightbox.querySelector('.filmstrip-lightbox-prev');
        const lbNext  = lightbox.querySelector('.filmstrip-lightbox-next');
        let lbCurrent = 0;

        function lbShow(index) {
            lbCurrent = (index + items.length) % items.length;
            const img = items[lbCurrent].querySelector('img');
            lbImage.src = img.src;
            lbImage.alt = img.alt;
        }

        items.forEach((item, i) => {
            item.addEventListener('click', e => {
                if (isDown) return;
                lbShow(i);
                lightbox.showModal();
                stopAutoplay();
                // Cursor in Top-Layer verschieben damit er über dem Dialog sichtbar bleibt
                const cur = document.getElementById('cursor');
                if (cur) lightbox.appendChild(cur);
            });
        });

        lbClose?.addEventListener('click', () => lightbox.close());
        lbPrev?.addEventListener('click',  () => lbShow(lbCurrent - 1));
        lbNext?.addEventListener('click',  () => lbShow(lbCurrent + 1));
        lightbox.addEventListener('click', e => { if (e.target === lightbox) lightbox.close(); });
        lightbox.addEventListener('keydown', e => {
            if (e.key === 'ArrowLeft')  lbShow(lbCurrent - 1);
            if (e.key === 'ArrowRight') lbShow(lbCurrent + 1);
        });
        lightbox.addEventListener('close', () => {
            startAutoplay();
            const cur = lightbox.querySelector('#cursor');
            if (cur) document.body.appendChild(cur);
        });
    }

    startAutoplay();
})();
</script>
