<?php
$data = $args['data'];
?>
<section data-module="hero-front">
    <h1 class="title"><?= esc_html($data['title']) ?></h1>
    <div class="visual">
        <?php if (!empty($data['image']['src'])): ?>
            <img class="image" src="<?= esc_url($data['image']['src']) ?>" alt="<?= esc_attr($data['image']['alt']) ?>">
        <?php endif; ?>
        <?php get_template_part('frontend/views/partials/badge', null, ['text' => $data['badge_text']]); ?>
    </div>
    <?php if ($data['intro']): ?>
        <p class="intro"><?= esc_html($data['intro']) ?></p>
    <?php endif; ?>
</section>
