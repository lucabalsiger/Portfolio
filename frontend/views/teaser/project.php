<?php
// Projektkachel für die Übersicht. Bild, Tags und Titel werden leicht angewinkelt dargestellt.
$project = $args['data'];
?>
<a class="teaser-project" href="<?= esc_url($project['url']) ?>">
    <?php if ($project['image']): ?>
        <div class="image-wrap">
            <img class="image" src="<?= esc_url($project['image']['src']) ?>" alt="<?= esc_attr($project['image']['alt']) ?>"<?php if (!empty($project['image']['width']) && !empty($project['image']['height'])): ?> width="<?= esc_attr($project['image']['width']) ?>" height="<?= esc_attr($project['image']['height']) ?>"<?php endif; ?> loading="lazy">
        </div>
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
    <h3 class="title"><?= esc_html($project['title']) ?></h3>
</a>
