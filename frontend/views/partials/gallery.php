<?php
// Bildergalerie. Erwartet $args['images'] als Liste aus get_media_by_id().
$images = array_filter($args['images'] ?? [], fn($image) => !empty($image['src']));
if (!$images) {
    return;
}
?>
<div class="gallery">
    <?php foreach ($images as $image): ?>
        <figure class="gallery-item">
            <img src="<?= esc_url($image['src']) ?>" alt="<?= esc_attr($image['alt']) ?>" loading="lazy">
        </figure>
    <?php endforeach; ?>
</div>
