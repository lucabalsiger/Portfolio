<?php
$project = get_project_by_id(get_the_ID());
// Unterzeile: Jahr und Untertitel, leere Werte werden weggelassen
$subline = implode(' · ', array_filter([$project['year'], $project['subtitle']]));
?>
<div data-page="project">
    <section class="project-hero">
        <h1 class="title"><?= esc_html($project['title']) ?></h1>
        <?php if ($project['image']): ?>
            <?php /* gleicher view-transition-name wie die Kachel in teaser/project.php. srcset/sizes: Bild ist 10 von 12 Spalten breit, auf Mobile ganz. fetchpriority: wichtigstes Bild zuerst laden */ ?>
            <img class="image" src="<?= esc_url($project['image']['src']) ?>"<?php if (!empty($project['image']['srcset'])): ?> srcset="<?= esc_attr($project['image']['srcset']) ?>" sizes="(max-width: 768px) 100vw, 84vw"<?php endif; ?> alt="<?= esc_attr($project['image']['alt']) ?>" fetchpriority="high" style="view-transition-name: project-<?= (int) get_the_ID() ?>">
        <?php endif; ?>
        <?php if ($subline): ?>
            <p class="lead"><?= esc_html($subline) ?></p>
        <?php endif; ?>
        <?php get_template_part('frontend/views/partials/project-stats', null, ['id' => get_the_ID(), 'stats' => $project['stats']]); ?>
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
