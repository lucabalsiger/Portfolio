<?php
// Hero für Unterseiten: Titel und optionaler Untertitel.
$data = $args['data'];
?>
<section data-module="hero-default">
    <h1 class="title"><?= esc_html($data['title']) ?></h1>
    <?php if ($data['subline']): ?>
        <p class="subline"><?= esc_html($data['subline']) ?></p>
    <?php endif; ?>
</section>
