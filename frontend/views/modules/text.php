<?php
$data = $args['data'];
?>
<section data-module="text">
    <?php if ($data['title']): ?>
        <h2 class="title"><?= esc_html($data['title']) ?></h2>
    <?php endif; ?>
    <div class="text"><?php the_rich_text($data['text']); ?></div>
</section>
