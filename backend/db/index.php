<?php
/**
 * In dieser Datei werden die eigenen Datenbank-Tabellen des Themes verwaltet:
 * - «portfolio_stats» zählt pro Projekt die Aufrufe und den Applaus
 * - «portfolio_messages» speichert die Nachrichten aus dem Kontaktformular
 *
 * Die Tabellen werden automatisch angelegt oder angepasst, sobald die
 * gespeicherte Version nicht mehr mit PORTFOLIO_DB_VERSION übereinstimmt,
 * also auch nach dem Umzug auf einen neuen Server.
 *
 * Wird der Aufbau einer Tabelle geändert, PORTFOLIO_DB_VERSION erhöhen.
 *
 * Dokumentation: https://developer.wordpress.org/reference/functions/dbdelta/
 * Dokumentation: https://developer.wordpress.org/reference/classes/wpdb/
 */

const PORTFOLIO_DB_VERSION = '1.1';

// -> Name der Statistik-Tabelle mit dem Präfix der Installation (meist «wp_»)
function get_stats_table_name(): string
{
    global $wpdb;
    return $wpdb->prefix . 'portfolio_stats';
}

// -> Name der Tabelle für Nachrichten aus dem Kontaktformular
function get_messages_table_name(): string
{
    global $wpdb;
    return $wpdb->prefix . 'portfolio_messages';
}

// -> Tabellen anlegen oder anpassen. dbDelta() verlangt: jedes Feld auf einer eigenen Zeile, zwei Leerzeichen nach PRIMARY KEY
function portfolio_db_install(): void
{
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $stats = get_stats_table_name();
    $messages = get_messages_table_name();
    $charset_collate = $wpdb->get_charset_collate();
    dbDelta([
        "CREATE TABLE $stats (
            project_id bigint(20) unsigned NOT NULL,
            views bigint(20) unsigned NOT NULL DEFAULT 0,
            applause bigint(20) unsigned NOT NULL DEFAULT 0,
            updated_at datetime NOT NULL,
            PRIMARY KEY  (project_id)
        ) $charset_collate;",
        "CREATE TABLE $messages (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(254) NOT NULL,
            message text NOT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY created_at (created_at)
        ) $charset_collate;",
    ]);
    update_option('portfolio_db_version', PORTFOLIO_DB_VERSION);
}

add_action('init', function () {
    if (get_option('portfolio_db_version') !== PORTFOLIO_DB_VERSION) {
        portfolio_db_install();
    }
});

// -> Zähler eines Projekts um 1 erhöhen. Gibt es noch keine Zeile, wird sie angelegt. false bei Datenbankfehler
function increase_project_stat(int $id, string $column): bool
{
    global $wpdb;
    // -> Spaltenname nur aus dieser Liste, nie direkt aus einer Anfrage übernehmen
    $column = match ($column) {
        'views'    => 'views',
        'applause' => 'applause',
    };
    $now = current_time('mysql', true);
    $result = $wpdb->query($wpdb->prepare(
        "INSERT INTO %i (project_id, %i, updated_at) VALUES (%d, 1, %s)
        ON DUPLICATE KEY UPDATE %i = %i + 1, updated_at = %s",
        get_stats_table_name(), $column, $id, $now, $column, $column, $now
    ));
    return $result !== false;
}

// -> Zeile entfernen, wenn ein Projekt endgültig gelöscht wird
add_action('deleted_post', function (int $id) {
    global $wpdb;
    $wpdb->delete(get_stats_table_name(), ['project_id' => $id], ['%d']);
});

// -> Nachricht aus dem Kontaktformular speichern. Gibt die neue ID zurück, false bei Datenbankfehler
function insert_contact_message(string $name, string $email, string $message): int|false
{
    global $wpdb;
    $result = $wpdb->insert(
        get_messages_table_name(),
        [
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'created_at' => current_time('mysql', true),
        ],
        ['%s', '%s', '%s', '%s']
    );
    return $result ? (int) $wpdb->insert_id : false;
}

// -> Eine Nachricht endgültig löschen. false, wenn es sie nicht gibt oder ein Datenbankfehler auftritt
function delete_contact_message(int $id): bool
{
    global $wpdb;
    return (bool) $wpdb->delete(get_messages_table_name(), ['id' => $id], ['%d']);
}
