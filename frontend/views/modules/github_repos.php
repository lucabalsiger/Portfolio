<?php
// GitHub-Repos: die zuletzt bearbeiteten öffentlichen Repos (geladen in backend/getter/_github.php).
// Ohne Repos, etwa wenn GitHub nicht erreichbar ist, wird das Modul nicht ausgegeben.
$data = $args['data'];
if (!$data['repos']) {
    return;
}
?>
<section data-module="github-repos">
    <?php if ($data['title']): ?>
        <<?= $data['heading'] ?> class="title"><?= esc_html($data['title']) ?></<?= $data['heading'] ?>>
    <?php endif; ?>
    <ul class="repos">
        <?php foreach ($data['repos'] as $repo): ?>
            <li class="repo">
                <a class="repo-name" href="<?= esc_url($repo['url']) ?>"<?= external_link_attrs($repo['url']) ?>><?= esc_html($repo['name']) ?></a>
                <?php if ($repo['description']): ?>
                    <p class="repo-description"><?= esc_html($repo['description']) ?></p>
                <?php endif; ?>
                <p class="repo-meta">
                    <?php if ($repo['language']): ?>
                        <span class="tag"><?= esc_html($repo['language']) ?></span>
                    <?php endif; ?>
                    <?php if ($repo['stars']): ?>
                        <span>★ <?= esc_html(number_format_i18n($repo['stars'])) ?></span>
                    <?php endif; ?>
                    <span>aktualisiert <?= esc_html(mb_strtolower($repo['updated'])) ?></span>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if ($data['profile_url']): ?>
        <p class="profile-link">
            <a href="<?= esc_url($data['profile_url']) ?>"<?= external_link_attrs($data['profile_url']) ?>>alle repos auf github</a>
        </p>
    <?php endif; ?>
</section>
