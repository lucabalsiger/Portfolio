<?php
$data = $args['data'];
?>
<section data-module="hero-front">
    <svg width="0" height="0" aria-hidden="true" focusable="false" style="position:absolute">
        <defs>
            <filter id="intro-grain" x="0%" y="0%" width="100%" height="100%">
                <feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch" result="noise"/>
                <feColorMatrix type="saturate" values="0" in="noise" result="grayNoise"/>
                <feComponentTransfer in="grayNoise" result="subtleGrain">
                    <feFuncR type="linear" slope="0.25" intercept="0.375"/>
                    <feFuncG type="linear" slope="0.25" intercept="0.375"/>
                    <feFuncB type="linear" slope="0.25" intercept="0.375"/>
                </feComponentTransfer>
                <feBlend in="SourceGraphic" in2="subtleGrain" mode="soft-light" result="blended"/>
                <feComposite in="blended" in2="SourceAlpha" operator="in"/>
            </filter>
        </defs>
    </svg>
    <h1 class="title"><?= esc_html($data['title']) ?></h1>
    <?php
    // Carbon Fields Checkbox gibt 'yes'/true (an) oder ''/false (aus) zurück.
    // ?? true als Fallback für nie gespeicherte Felder (Standard: Foto zeigen).
    $badge_only = !($data['show_photo'] ?? true);
    ?>
    <div class="visual<?= $badge_only ? ' badge-only' : '' ?>">
        <?php if (!$badge_only && !empty($data['image']['src'])): ?>
            <img class="image" src="<?= esc_url($data['image']['src']) ?>" alt="<?= esc_attr($data['image']['alt']) ?>">
        <?php endif; ?>
        <?php get_template_part('frontend/views/partials/badge', null, ['text' => $data['badge_text']]); ?>
    </div>
    <?php if (!empty($data['intro_lines'])): ?>
        <div class="intro-display">
            <?php foreach ($data['intro_lines'] as $line):
                $parts = ['intro-line'];
                $color = $line['color'] ?? 'accent';
                if ($color === 'ink')   { $parts[] = 'color-ink'; }
                if ($color === 'paper') { $parts[] = 'color-paper'; }
                $style = $line['style'] ?? 'filled';
                $parts[] = match($style) {
                    'outlined'           => 'is-outlined',
                    'grainy', 'halftone' => 'is-grainy', // 'halftone' für Rückwärtskompatibilität
                    default              => 'is-filled',
                };
                if ($style === 'outlined' || $style === 'filled') {
                    $parts[] = 'stroke-' . ($line['stroke_width'] ?: 'medium');
                }
                if (!empty($line['spacing']) && $line['spacing'] !== 'default') {
                    $parts[] = 'spacing-' . $line['spacing'];
                }
                if (!empty($line['size']) && $line['size'] !== 'full') {
                    $parts[] = 'size-' . $line['size'];
                }
                if (!empty($line['stretch']) && $line['stretch'] !== 'normal') {
                    $parts[] = 'stretch-' . $line['stretch'];
                }
                if (!empty($line['italic'])) {
                    $parts[] = 'is-italic';
                }
                if (!empty($line['font']) && $line['font'] !== 'default') {
                    $parts[] = 'font-' . $line['font'];
                }
                $is_grainy = in_array('is-grainy', $parts);
            ?>
                <span class="<?= esc_attr(implode(' ', $parts)) ?>"<?= $is_grainy ? ' data-text="' . esc_attr($line['text']) . '"' : '' ?>><?= esc_html($line['text']) ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
