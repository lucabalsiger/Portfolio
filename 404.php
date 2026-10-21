<?php
// Template für nicht gefundene Seiten (404). Gleicher Aufbau wie page.php.
get_header() ?>
<?php get_template_part('frontend/views/layout/header') ?>
<main><?php get_template_part('frontend/views/pages/404') ?></main>
<?php get_template_part('frontend/views/layout/footer') ?>
<?php get_footer() ?>
