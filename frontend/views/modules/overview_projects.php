<?php
// Projektübersicht. $data['projects'] ist bereits die fertige Liste (aufbereitet in _modules.php).
$data = $args['data'];
?>
<section data-module="overview-projects">
    <?php if ($data['title']): ?>
        <h2 class="title"><?= esc_html($data['title']) ?></h2>
    <?php endif; ?>
    <?php if ($data['projects']): ?>
        <div class="projects">
            <?php foreach ($data['projects'] as $project): ?>
                <?php get_template_part('frontend/views/teaser/project', null, ['data' => $project]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
