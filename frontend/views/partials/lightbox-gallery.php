<?php
// Galerie mit Lightbox. Erwartet $args['images'] als Liste aus get_project_gallery().
// Ohne JavaScript öffnet ein Klick einfach das grosse Bild.
$images = $args['images'] ?? [];
if (!$images) {
    return;
}
?>
<div class="lightbox-gallery">
    <div class="gallery">
        <?php foreach ($images as $image): ?>
            <a class="gallery-item" href="<?= esc_url($image['full']) ?>">
                <img src="<?= esc_url($image['thumb']) ?>" alt="<?= esc_attr($image['alt']) ?>" loading="lazy">
            </a>
        <?php endforeach; ?>
    </div>
    <dialog class="lightbox" aria-label="Bildansicht"<?= count($images) === 1 ? ' data-single' : '' ?>>
        <button class="lightbox-close" type="button" aria-label="Schliessen">&times;</button>
        <button class="lightbox-prev" type="button" aria-label="Vorheriges Bild">&lsaquo;</button>
        <img class="lightbox-image" src="" alt="">
        <button class="lightbox-next" type="button" aria-label="Nächstes Bild">&rsaquo;</button>
    </dialog>
</div>
<script>
(function () {
    const root = document.currentScript.previousElementSibling;
    const dialog = root.querySelector('.lightbox');
    if (!dialog || typeof dialog.showModal !== 'function') return;
    const items = [...root.querySelectorAll('.gallery-item')];
    const image = dialog.querySelector('.lightbox-image');
    let current = 0;
    let opener = null;

    function show(index) {
        current = (index + items.length) % items.length;
        image.src = items[current].href;
        image.alt = items[current].querySelector('img').alt;
    }

    items.forEach((item, index) => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            opener = item;
            show(index);
            dialog.showModal();
        });
    });

    dialog.querySelector('.lightbox-close').addEventListener('click', () => dialog.close());
    dialog.querySelector('.lightbox-prev').addEventListener('click', () => show(current - 1));
    dialog.querySelector('.lightbox-next').addEventListener('click', () => show(current + 1));
    dialog.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') show(current - 1);
        if (event.key === 'ArrowRight') show(current + 1);
    });
    // Klick auf den dunklen Hintergrund schliesst die Ansicht
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.close();
    });
    // Fokus zurück auf das angeklickte Bild
    dialog.addEventListener('close', () => opener && opener.focus());
})();
</script>
