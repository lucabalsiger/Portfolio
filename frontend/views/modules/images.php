<?php
$data = $args['data'];
?>
<section data-module="images">
    <?php get_template_part('frontend/views/partials/gallery', null, ['images' => $data['images']]); ?>
</section>
