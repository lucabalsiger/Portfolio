<?php
$logo = get_globals_logo();
$menu_items = get_menu_by_slug('main');
?>
<header data-layout="header">
    <a class="brand" href="<?= esc_url(home_url('/')) ?>">
        <?php if ($logo): ?>
            <img class="logo" src="<?= esc_url($logo['src']) ?>" alt="<?= esc_attr(get_bloginfo('name')) ?>">
        <?php else: ?>
            <?= esc_html(get_bloginfo('name')) ?>
        <?php endif; ?>
    </a>
    <?php if ($menu_items): ?>
        <nav class="nav" aria-label="Hauptnavigation">
            <ul>
                <?php foreach ($menu_items as $item): ?>
                    <li><a href="<?= esc_url($item['url']) ?>"<?= external_link_attrs($item['url']) ?>><?= esc_html($item['title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>
</header>
