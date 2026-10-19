<?php
$project = get_project_by_id(get_the_ID());
$sections = [
    ['title' => 'konzept', 'text' => $project['concept'], 'images' => []],
    ['title' => 'ziel', 'text' => $project['goal'], 'images' => []],
    ['title' => 'prozess', 'text' => $project['process'], 'images' => $project['process_images']],
    ['title' => 'ergebnis', 'text' => $project['outcome'], 'images' => $project['outcome_images']],
];
$meta = array_filter([
    'Jahr' => $project['year'],
    'Kontext' => $project['context'],
    'Rolle' => $project['role'],
    'Kategorie' => implode(', ', $project['categories']),
]);
?>
<main data-page="project">
    <section class="project-hero">
        <h1 class="title"><?= esc_html($project['title']) ?></h1>
        <span class="number"><?= esc_html($project['number']) ?>.</span>
        <?php if ($project['image']): ?>
            <img class="image" src="<?= esc_url($project['image']['src']) ?>" alt="<?= esc_attr($project['image']['alt']) ?>">
        <?php endif; ?>
        <?php if ($project['lead']): ?>
            <p class="lead"><?= esc_html($project['lead']) ?></p>
        <?php endif; ?>
        <?php if ($meta): ?>
            <dl class="meta">
                <?php foreach ($meta as $label => $value): ?>
                    <div><dt><?= esc_html($label) ?></dt><dd><?= esc_html($value) ?></dd></div>
                <?php endforeach; ?>
            </dl>
        <?php endif; ?>
    </section>

    <?php foreach ($sections as $section): ?>
        <?php if (!$section['text'] && !$section['images']) continue; ?>
        <section class="project-section">
            <h2 class="section-title"><?= esc_html($section['title']) ?></h2>
            <div class="section-text"><?php the_rich_text($section['text']); ?></div>
            <?php get_template_part('frontend/views/partials/gallery', null, ['images' => $section['images']]); ?>
        </section>
    <?php endforeach; ?>

    <?php if ($project['link']): ?>
        <p class="project-link">
            <a href="<?= esc_url($project['link']) ?>"<?= external_link_attrs($project['link']) ?>>Projekt ansehen</a>
        </p>
    <?php endif; ?>
</main>
