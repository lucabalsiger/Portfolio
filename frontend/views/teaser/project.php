<?php
$project = $args['data'];
?>
<a class="teaser-project" href="<?= esc_url($project['url']) ?>">
    <?php if ($project['image']): ?>
        <img class="image" src="<?= esc_url($project['image']['src']) ?>" alt="<?= esc_attr($project['image']['alt']) ?>" loading="lazy">
    <?php endif; ?>
    <span class="number"><?= esc_html($project['number']) ?>.</span>
    <h3 class="title"><?= esc_html($project['title']) ?></h3>
    <?php if ($project['categories'] || $project['year']): ?>
        <p class="meta"><?= esc_html(implode(', ', array_filter([...$project['categories'], $project['year']]))) ?></p>
    <?php endif; ?>
</a>
