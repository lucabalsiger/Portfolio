<?php
$project = get_project_by_id(get_the_ID());
// Unterzeile: Jahr und Untertitel, leere Werte werden weggelassen
$subline = implode(' · ', array_filter([$project['year'], $project['subtitle']]));
?>
<div data-page="project">
    <section class="project-hero">
        <h1 class="title"><?= esc_html($project['title']) ?></h1>
        <?php if ($project['image']): ?>
            <img class="image" src="<?= esc_url($project['image']['src']) ?>" alt="<?= esc_attr($project['image']['alt']) ?>">
        <?php endif; ?>
        <?php if ($subline): ?>
            <p class="lead"><?= esc_html($subline) ?></p>
        <?php endif; ?>
    </section>

    <?php if ($project['description'] || $project['gallery']): ?>
        <section class="project-section">
            <?php if ($project['description']): ?>
                <h2 class="section-title"><?= esc_html($project['description_title']) ?></h2>
                <div class="section-text"><?php the_rich_text($project['description']); ?></div>
            <?php endif; ?>
            <?php get_template_part('frontend/views/partials/lightbox-gallery', null, ['images' => $project['gallery']]); ?>
        </section>
    <?php endif; ?>

    <?php if ($project['link']): ?>
        <p class="project-link">
            <a href="<?= esc_url($project['link']) ?>"<?= external_link_attrs($project['link']) ?>><?= esc_html($project['link_label']) ?></a>
        </p>
    <?php endif; ?>
</div>
