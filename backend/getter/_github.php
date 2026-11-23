<?php
/**
 * Öffentliche Repos eines GitHub-Kontos über die GitHub-API laden.
 * Der Abruf läuft in PHP auf dem Server, nicht im Browser: Besucher:innen
 * schicken so keine Daten an GitHub (gleicher Gedanke wie bei den lokalen Schriften).
 *
 * - Zwischenspeicher: 6 Stunden als Transient, damit nicht jeder Seitenaufruf
 *   die API fragt (ohne Anmeldung erlaubt GitHub 60 Anfragen pro Stunde)
 * - Fällt GitHub aus, wird der letzte erfolgreiche Stand weiterverwendet.
 *   Gibt es keinen, liefert der Getter eine leere Liste und das Modul wird ausgeblendet.
 *
 * Dokumentation: https://docs.github.com/en/rest/repos/repos#list-repositories-for-a-user
 * Dokumentation: https://developer.wordpress.org/reference/functions/wp_remote_get/
 */

const PORTFOLIO_GITHUB_CACHE = 6 * 3600;    // 6 Stunden
const PORTFOLIO_GITHUB_RETRY = 15 * 60;     // nach einem Fehler erst in 15 Minuten wieder versuchen

// -> Rohdaten von GitHub holen und auf das Nötige reduzieren. false bei einem Fehler
function fetch_github_repos(string $username): array|false {
    $response = wp_remote_get(
        'https://api.github.com/users/' . rawurlencode($username) . '/repos?type=owner&sort=pushed&per_page=30',
        [
            'timeout' => 5,
            'headers' => ['Accept' => 'application/vnd.github+json'],
        ]
    );
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return false;
    }
    $repos = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($repos)) {
        return false;
    }
    // -> Forks und archivierte Repos weglassen, sie zeigen keine eigene, aktuelle Arbeit
    $repos = array_filter($repos, fn($repo) => empty($repo['fork']) && empty($repo['archived']));
    return array_values(array_map(fn($repo) => [
        'name' => (string) $repo['name'],
        'description' => (string) ($repo['description'] ?? ''),
        'url' => (string) $repo['html_url'],
        'language' => (string) ($repo['language'] ?? ''),
        'stars' => (int) $repo['stargazers_count'],
        'pushed_at' => (string) $repo['pushed_at'],
    ], $repos));
}

// -> Repos für das Modul: aus dem Zwischenspeicher, sonst frisch von GitHub, sonst der letzte gute Stand
function get_github_repos(string|false $username, int $limit = 6): array {
    if (!$username) {
        return [];
    }
    $key = 'portfolio_github_' . md5(strtolower($username));
    $repos = get_transient($key);
    if ($repos === false) {
        $repos = fetch_github_repos($username);
        if ($repos !== false) {
            set_transient($key, $repos, PORTFOLIO_GITHUB_CACHE);
            update_option($key . '_last', $repos, false); // -> letzter guter Stand, ohne Autoload
        } else {
            $repos = get_option($key . '_last', []);
            set_transient($key, $repos, PORTFOLIO_GITHUB_RETRY);
        }
    }
    return array_map(fn($repo) => $repo + [
        'updated' => wp_date('F Y', strtotime($repo['pushed_at'])), // -> z. B. «Oktober 2026»
    ], array_slice($repos, 0, max(1, $limit)));
}
