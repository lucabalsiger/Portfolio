<?php
$data = $args['data'];
?>
<section data-module="contact">
    <?php if ($data['image'] && !empty($data['image']['src'])): ?>
        <img class="image" src="<?= esc_url($data['image']['src']) ?>" alt="<?= esc_attr($data['image']['alt']) ?>" loading="lazy">
    <?php endif; ?>
    <div class="content">
        <?php if ($data['title']): ?>
            <h2 class="title"><?= esc_html($data['title']) ?></h2>
        <?php endif; ?>
        <?php if ($data['text']): ?>
            <p class="text"><?= esc_html($data['text']) ?></p>
        <?php endif; ?>
        <?php if ($data['email']): ?>
            <a class="email" href="mailto:<?= esc_attr(antispambot($data['email'])) ?>"><?= esc_html(antispambot($data['email'])) ?></a>
        <?php endif; ?>
    </div>
</section>
