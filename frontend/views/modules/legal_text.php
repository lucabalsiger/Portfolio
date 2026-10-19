<?php
$data = $args['data'];
?>
<section data-module="legal-text">
    <h1 class="title"><?= esc_html($data['title']) ?></h1>
    <div class="text"><?php the_rich_text($data['text']); ?></div>
</section>
