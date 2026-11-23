<?php
/**
 * Aufrufe und Applaus aus der Tabelle «portfolio_stats» (backend/db/)
 * im Backend anzeigen:
 * - zwei sortierbare Spalten in der Liste «Alle Projekte»
 * - ein Dashboard-Widget mit den Top-Projekten und den Summen
 * - ein Button im Widget, der die Statistik zurücksetzt (nur Admins, z. B. vor dem Go-Live)
 *
 * Dokumentation: https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
 * Dokumentation: https://developer.wordpress.org/reference/functions/wp_add_dashboard_widget/
 */

// -> Spalten «Aufrufe» und «Applaus» in der Projektliste
add_filter('manage_project_posts_columns', function (array $columns) {
    $columns['views'] = 'Aufrufe';
    $columns['applause'] = 'Applaus';
    return $columns;
});

add_action('manage_project_posts_custom_column', function (string $column, int $id) {
    // -> pro Projekt nur eine Abfrage, auch wenn beide Spalten danach fragen
    static $stats = [];
    if ($column !== 'views' && $column !== 'applause') {
        return;
    }
    $stats[$id] ??= get_project_stats($id);
    echo esc_html(number_format_i18n($stats[$id][$column]));
}, 10, 2);

// -> Spalten sortierbar machen. true: beim ersten Klick absteigend, also höchste Zahl zuoberst
add_filter('manage_edit-project_sortable_columns', function (array $columns) {
    $columns['views'] = ['views', true];
    $columns['applause'] = ['applause', true];
    return $columns;
});

// -> Für die Sortierung die eigene Tabelle dazuholen. Projekte ohne Zeile zählen als 0
add_filter('posts_clauses', function (array $clauses, WP_Query $query) {
    global $wpdb;
    $orderby = $query->get('orderby');
    if (
        !is_admin() ||
        !$query->is_main_query() ||
        $query->get('post_type') !== 'project' ||
        !in_array($orderby, ['views', 'applause'], true) // -> nur diese beiden Werte landen im SQL
    ) {
        return $clauses;
    }
    $order = strtoupper($query->get('order')) === 'ASC' ? 'ASC' : 'DESC';
    $table = get_stats_table_name();
    $clauses['join'] .= " LEFT JOIN $table AS stats ON stats.project_id = {$wpdb->posts}.ID";
    $clauses['orderby'] = "COALESCE(stats.$orderby, 0) $order, {$wpdb->posts}.menu_order ASC";
    return $clauses;
}, 10, 2);

// -> Dashboard-Widget registrieren
add_action('wp_dashboard_setup', function () {
    wp_add_dashboard_widget('portfolio_stats', 'Projekte: Aufrufe und Applaus', 'render_stats_dashboard_widget');
});

// -> Inhalt des Dashboard-Widgets
function render_stats_dashboard_widget(): void
{
    $projects = get_top_projects(5);
    $totals = get_stats_totals();
    if (isset($_GET['portfolio_stats_reset'])) {
        echo '<p><strong>Die Statistik wurde zurückgesetzt.</strong></p>';
    }
    if (!$projects) {
        echo '<p>Noch keine Aufrufe oder Applaus gezählt.</p>';
    } else {
        ?>
        <p>
            Total: <strong><?= esc_html(number_format_i18n($totals['views'])) ?></strong> Aufrufe,
            <strong><?= esc_html(number_format_i18n($totals['applause'])) ?></strong> Applaus
        </p>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Projekt</th>
                    <th>Aufrufe</th>
                    <th>Applaus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><a href="<?= esc_url(get_permalink($project['id'])) ?>"><?= esc_html($project['title']) ?></a></td>
                        <td><?= esc_html(number_format_i18n($project['views'])) ?></td>
                        <td><?= esc_html(number_format_i18n($project['applause'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
    if (current_user_can('manage_options')) {
        ?>
        <form method="post" action="<?= esc_url(admin_url('admin-post.php')) ?>" style="margin-top: 12px"
            onsubmit="return confirm('Alle Aufrufe und den ganzen Applaus unwiderruflich auf 0 setzen?');">
            <input type="hidden" name="action" value="portfolio_reset_stats">
            <?php wp_nonce_field('portfolio_reset_stats'); ?>
            <button type="submit" class="button">Statistik zurücksetzen</button>
        </form>
        <?php
    }
}

// -> Statistik zurücksetzen: nur Admins, nur mit gültigem Nonce (Schutz gegen untergeschobene Anfragen)
add_action('admin_post_portfolio_reset_stats', function () {
    if (!current_user_can('manage_options')) {
        wp_die('Keine Berechtigung.', 403);
    }
    check_admin_referer('portfolio_reset_stats');
    global $wpdb;
    $wpdb->query($wpdb->prepare('DELETE FROM %i', get_stats_table_name()));
    wp_safe_redirect(add_query_arg('portfolio_stats_reset', '1', admin_url('index.php')));
    exit;
});
