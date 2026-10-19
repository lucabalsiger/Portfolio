<?php
// Roter Kreis mit umlaufendem Text (Signet). Erwartet $args['text'].
$text = trim($args['text'] ?? '');
if (!$text) {
    return;
}
$id = 'badge-' . wp_unique_id();
?>
<div class="badge" aria-hidden="true">
    <svg viewBox="0 0 200 200">
        <defs>
            <path id="<?= esc_attr($id) ?>" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/>
        </defs>
        <circle class="dot" cx="100" cy="100" r="46"/>
        <text class="ring"><textPath href="#<?= esc_attr($id) ?>" textLength="496" lengthAdjust="spacing"><?= esc_html(str_repeat($text . ' ', 2)) ?></textPath></text>
    </svg>
</div>
