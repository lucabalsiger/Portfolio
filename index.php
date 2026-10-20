<?php
// Fallback-Template — wird angezeigt, wenn kein spezifischeres Template passt.
// Im normalen Betrieb bleibt <main> leer; Inhalt kommt über page.php oder single-project.php.
get_header() ?>
<?php get_template_part('frontend/views/layout/header') ?>
<main></main>
<?php get_template_part('frontend/views/layout/footer') ?>
<?php get_footer() ?>
