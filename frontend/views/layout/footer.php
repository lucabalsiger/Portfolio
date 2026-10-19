<?php
$email = get_globals_email();
$socials = get_globals_socials();
$legal_items = get_menu_by_slug('legals');
?>
<footer data-layout="footer">
    <p class="footer-text"><?= esc_html(get_globals_footer_text()) ?></p>
    <?php if ($email): ?>
        <a class="email" href="mailto:<?= esc_attr(antispambot($email)) ?>"><?= esc_html(antispambot($email)) ?></a>
    <?php endif; ?>
    <?php if ($socials): ?>
        <ul class="socials">
            <?php foreach ($socials as $social): ?>
                <li><a href="<?= esc_url($social['link']) ?>"<?= external_link_attrs($social['link']) ?>><?= esc_html($social['title']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($legal_items): ?>
        <nav class="legals" aria-label="Rechtliches">
            <ul>
                <?php foreach ($legal_items as $item): ?>
                    <li><a href="<?= esc_url($item['url']) ?>"><?= esc_html($item['title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>
</footer>
