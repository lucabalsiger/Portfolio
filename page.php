<?php
// Template für alle WordPress-Seiten (Pages). Lädt Header, dann den Seiteninhalt via Module.
get_header() ?>
<?php get_template_part('frontend/views/layout/header') ?>
<main><?php get_template_part('frontend/views/pages/page') ?></main>
<?php get_template_part('frontend/views/layout/footer') ?>
<?php get_footer() ?>
