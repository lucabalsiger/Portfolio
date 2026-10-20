<?php
$project = $args['data'];
?>
<a class="teaser-project" href="<?= esc_url($project['url']) ?>">
    <?php if ($project['image']): ?>
        <img class="image" src="<?= esc_url($project['image']['src']) ?>" alt="<?= esc_attr($project['image']['alt']) ?>" loading="lazy">
    <?php endif; ?>
    <?php if ($project['categories'] || $project['year']): ?>
        <div class="tags">
            <?php foreach ($project['categories'] as $cat): ?>
                <span class="tag"><?= esc_html($cat) ?></span>
            <?php endforeach; ?>
            <?php if ($project['year']): ?>
                <span class="tag"><?= esc_html($project['year']) ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <span class="number"><?= esc_html($project['number']) ?>.</span>
    <h3 class="title"><?= esc_html($project['title']) ?></h3>
</a>
