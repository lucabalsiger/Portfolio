<?php
/**
 * Aufrufe und Applaus eines Projekts zählen. main.js ruft die Endpunkte per
 * fetch() auf. Gespeichert wird in der Tabelle «portfolio_stats» (backend/db/).
 *
 *   POST /wp-json/portfolio/v1/projects/{id}/view
 *   POST /wp-json/portfolio/v1/projects/{id}/applause
 *
 * Antwort: { "views": 12, "applause": 3, "counted": true }
 * «counted» ist false, wenn diese Person das Projekt kürzlich schon gezählt hat.
 *
 * Damit niemand die Zahlen in die Höhe treiben kann, merkt sich der Server
 * pro Person und Projekt eine Sperre als Transient. Die IP-Adresse wird
 * dafür mit wp_hash() und dem geheimen Schlüssel aus wp-config.php gehasht
 * und nie im Klartext gespeichert.
 */

// -> Sperrdauer in Sekunden: ein Aufruf pro 30 Minuten, ein Applaus pro Tag (dauerhaft merkt es sich der Browser)
const PORTFOLIO_VIEW_LOCK = 1800;
const PORTFOLIO_APPLAUSE_LOCK = 86400;

// -> Gehört die ID zu einem veröffentlichten Projekt?
function is_public_project(int $id): bool
{
    return get_post_type($id) === 'project' && get_post_status($id) === 'publish';
}

// -> Schlüssel der Sperre pro Person, Zähler und Projekt. REMOTE_ADDR statt X-Forwarded-For, weil sich dieser Header fälschen lässt
function get_stat_lock_key(string $column, int $id): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    return 'portfolio_lock_' . wp_hash($ip . '|' . $column . '|' . $id);
}

// -> Zähler erhöhen, sofern diese Person nicht gesperrt ist, und den aktuellen Stand zurückgeben
function count_project_stat(int $id, string $column, int $lock_seconds): WP_REST_Response|WP_Error
{
    $lock_key = get_stat_lock_key($column, $id);
    $counted = false;
    if (!get_transient($lock_key)) {
        if (!increase_project_stat($id, $column)) {
            return new WP_Error('portfolio_db_error', 'Der Zähler konnte nicht gespeichert werden.', ['status' => 500]);
        }
        set_transient($lock_key, 1, $lock_seconds);
        $counted = true;
    }
    return rest_ensure_response(get_project_stats($id) + ['counted' => $counted]);
}

add_action('rest_api_init', function () {
    $args = [
        'id' => [
            'validate_callback' => fn($id) => is_public_project((int) $id),
        ],
    ];
    register_rest_route('portfolio/v1', '/projects/(?P<id>\d+)/view', [
        'methods' => 'POST',
        'callback' => fn($request) => count_project_stat((int) $request['id'], 'views', PORTFOLIO_VIEW_LOCK),
        'permission_callback' => '__return_true', // -> öffentlich, auch ohne Anmeldung
        'args' => $args,
    ]);
    register_rest_route('portfolio/v1', '/projects/(?P<id>\d+)/applause', [
        'methods' => 'POST',
        'callback' => fn($request) => count_project_stat((int) $request['id'], 'applause', PORTFOLIO_APPLAUSE_LOCK),
        'permission_callback' => '__return_true',
        'args' => $args,
    ]);
});
