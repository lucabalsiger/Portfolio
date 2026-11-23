<?php
// Zweispaltiges Text-Modul: optionaler Titel links, Fliesstext rechts.
$data = $args['data'];
?>
<section data-module="text">
    <?php if ($data['title']): ?>
        <<?= $data['heading'] ?> class="title"><?= esc_html($data['title']) ?></<?= $data['heading'] ?>>
    <?php endif; ?>
    <div class="text"><?php the_rich_text($data['text']); ?></div>
</section>
