<?php
// Kontaktmodul: Titel, Text, optionales Bild, klickbares E-Mail-Signet.
// E-Mail wird via antispambot() verschleiert, damit Crawler sie nicht abgreifen.
$data = $args['data'];
?>
<section data-module="contact">
    <?php if ($data['image'] && !empty($data['image']['src'])): ?>
        <img class="image" src="<?= esc_url($data['image']['src']) ?>" alt="<?= esc_attr($data['image']['alt']) ?>" loading="lazy">
    <?php endif; ?>
    <div class="content">
        <?php if ($data['title']): ?>
            <h2 class="title"><?= esc_html($data['title']) ?></h2>
        <?php endif; ?>
        <?php if ($data['text']): ?>
            <p class="text"><?= esc_html($data['text']) ?></p>
        <?php endif; ?>
        <?php if ($data['email']):
            $email_path_id = 'email-path-' . wp_unique_id();
            $email_text    = antispambot($data['email']) . ' · ';
        ?>
            <a class="email-badge" href="mailto:<?= esc_attr(antispambot($data['email'])) ?>" aria-label="<?= esc_attr(antispambot($data['email'])) ?>">
                <svg viewBox="0 0 200 200" aria-hidden="true">
                    <defs>
                        <path id="<?= esc_attr($email_path_id) ?>" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/>
                    </defs>
                    <circle class="dot" cx="100" cy="100" r="46"/>
                    <text class="ring"><textPath href="#<?= esc_attr($email_path_id) ?>" textLength="502" lengthAdjust="spacing"><?= esc_html(str_repeat($email_text, 2)) ?></textPath></text>
                </svg>
            </a>
        <?php endif; ?>
    </div>
</section>
