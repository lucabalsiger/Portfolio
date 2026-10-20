<?php
// Statement-Modul: grosse, auffällige Textzeile für kurze Aussagen.
$data = $args['data'];
?>
<section data-module="statement">
    <p class="text"><?= esc_html($data['text']) ?></p>
</section>
