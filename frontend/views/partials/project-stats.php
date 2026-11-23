<?php
// Aufrufe und Applaus eines Projekts. Erwartet $args['id'] und $args['stats'] aus get_project_stats().
// Gezählt wird in main.js über die REST-Endpunkte (backend/rest/). Angemeldete Benutzer:innen zählen nicht mit.
$id = (int) $args['id'];
$stats = $args['stats'];
?>
<div class="meta project-stats"
    data-project-id="<?= esc_attr($id) ?>"
    data-view-url="<?= esc_url(rest_url('portfolio/v1/projects/' . $id . '/view')) ?>"
    data-applause-url="<?= esc_url(rest_url('portfolio/v1/projects/' . $id . '/applause')) ?>"
    <?= is_user_logged_in() ? 'data-readonly' : '' ?>>
    <dl class="stats">
        <div>
            <dt>aufrufe</dt>
            <dd class="stats-views"><?= esc_html(number_format_i18n($stats['views'])) ?></dd>
        </div>
        <div>
            <dt>applaus</dt>
            <dd class="stats-applause"><?= esc_html(number_format_i18n($stats['applause'])) ?></dd>
        </div>
    </dl>
    <button class="applause-button" type="button" aria-pressed="false">applaus geben</button>
    <p class="stats-message" aria-live="polite"></p>
</div>
