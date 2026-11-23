<?php
// Projektübersicht. $data['projects'] ist bereits die fertige Liste (aufbereitet in _modules.php).
$data = $args['data'];
// -> Kacheltitel eine Ebene unter dem Modultitel. Ohne Modultitel direkt h2 (nie mehrere h1)
$teaser_heading = $data['title'] ? ($data['heading'] === 'h1' ? 'h2' : 'h3') : 'h2';
?>
<section data-module="overview-projects">
    <?php if ($data['title']): ?>
        <<?= $data['heading'] ?> class="title"><?= esc_html($data['title']) ?></<?= $data['heading'] ?>>
    <?php endif; ?>
    <?php if ($data['projects']): ?>
        <div class="projects">
            <?php foreach ($data['projects'] as $project): ?>
                <?php get_template_part('frontend/views/teaser/project', null, ['data' => $project, 'heading' => $teaser_heading]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
