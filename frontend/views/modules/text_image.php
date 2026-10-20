<?php
// Text-Bild-Modul. data-image-position steuert per CSS ob das Bild links oder rechts erscheint.
$data = $args['data'];
?>
<section data-module="text-image" data-image-position="<?= esc_attr($data['image_position']) ?>">
    <div class="content">
        <?php if ($data['title']): ?>
            <h2 class="title"><?= esc_html($data['title']) ?></h2>
        <?php endif; ?>
        <div class="text"><?php the_rich_text($data['text']); ?></div>
    </div>
    <?php if (!empty($data['image']['src'])): ?>
        <img class="image" src="<?= esc_url($data['image']['src']) ?>" alt="<?= esc_attr($data['image']['alt']) ?>" loading="lazy">
    <?php endif; ?>
</section>
