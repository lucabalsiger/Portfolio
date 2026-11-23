<?php

// -> IDs aller veröffentlichten Projekte in ihrer Reihenfolge
// -> static: Die Abfrage läuft pro Seitenaufruf nur einmal, danach kommt das gespeicherte Ergebnis
function get_project_ids(): array {
    static $ids = null;
    if ($ids === null) {
        $ids = get_posts([
            'post_type' => 'project',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC'],
            'fields' => 'ids',
        ]);
    }
    return $ids;
}

// -> Nummer eines Projekts (1, 2, 3 ...) aus der Reihenfolge berechnen
function get_project_number(int $id): int {
    $index = array_search($id, get_project_ids(), true);
    return $index === false ? 0 : $index + 1;
}

// -> Namen der zugewiesenen Projektkategorien
function get_project_categories(int $id): array {
    $terms = get_the_terms($id, 'project_category');
    if (!$terms || is_wp_error($terms)) {
        return [];
    }
    return array_map(fn($term) => $term->name, $terms);
}

// -> Galeriebilder: Vorschau mit srcset für den Filmstreifen, Original nur für die Lightbox
function get_project_gallery(int $id, string $field): array {
    $ids = carbon_get_post_meta($id, $field) ?: [];
    $images = [];
    foreach ($ids as $image_id) {
        $thumb = wp_get_attachment_image_src((int) $image_id, 'l');
        $full = wp_get_attachment_image_src((int) $image_id, 'full');
        if (!$thumb || !$full) {
            continue;
        }
        $images[] = [
            'thumb' => $thumb[0],
            // -> alle vorhandenen Grössen bis «l», der Browser wählt passend zur Bildschirmbreite
            'srcset' => wp_get_attachment_image_srcset((int) $image_id, 'l') ?: '',
            'width' => (int) $thumb[1],
            'height' => (int) $thumb[2],
            'full' => $full[0],
            'alt' => get_post_meta((int) $image_id, '_wp_attachment_image_alt', true) ?: '',
        ];
    }
    return $images;
}

// -> Aufrufe und Applaus eines Projekts aus der eigenen Tabelle, 0 wenn noch nichts gezählt wurde
function get_project_stats(int $id): array {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare(
        'SELECT views, applause FROM %i WHERE project_id = %d',
        get_stats_table_name(), $id
    ), ARRAY_A);
    return [
        'views' => (int) ($row['views'] ?? 0),
        'applause' => (int) ($row['applause'] ?? 0),
    ];
}

// -> Veröffentlichte Projekte mit dem meisten Applaus (bei Gleichstand: mehr Aufrufe zuerst)
function get_top_projects(int $limit = 5): array {
    global $wpdb;
    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT s.project_id, s.views, s.applause FROM %i AS s
        INNER JOIN %i AS p ON p.ID = s.project_id
        WHERE p.post_type = 'project' AND p.post_status = 'publish'
        ORDER BY s.applause DESC, s.views DESC
        LIMIT %d",
        get_stats_table_name(), $wpdb->posts, $limit
    ), ARRAY_A) ?: [];
    return array_map(fn($row) => [
        'id' => (int) $row['project_id'],
        'title' => get_the_title((int) $row['project_id']),
        'views' => (int) $row['views'],
        'applause' => (int) $row['applause'],
    ], $rows);
}

// -> Summe aller Aufrufe und des ganzen Applauses über alle veröffentlichten Projekte
function get_stats_totals(): array {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare(
        "SELECT SUM(s.views) AS views, SUM(s.applause) AS applause FROM %i AS s
        INNER JOIN %i AS p ON p.ID = s.project_id
        WHERE p.post_type = 'project' AND p.post_status = 'publish'",
        get_stats_table_name(), $wpdb->posts
    ), ARRAY_A);
    return [
        'views' => (int) ($row['views'] ?? 0),
        'applause' => (int) ($row['applause'] ?? 0),
    ];
}

// -> Kurzdaten für Teaser in der Projektübersicht
function get_project_teaser_by_id(int $id): array {
    $image = carbon_get_post_meta($id, 'image');
    return [
        'id' => $id,
        'title' => get_the_title($id),
        'url' => get_permalink($id),
        'year' => carbon_get_post_meta($id, 'year'),
        'categories' => get_project_categories($id),
        'image' => $image ? get_media_by_id($image, 'm') : false,
    ];
}

// -> Alle Daten für die Projektdetailseite
function get_project_by_id(int $id): array {
    $image = carbon_get_post_meta($id, 'image');
    return [
        'title' => get_the_title($id),
        'number' => get_project_number($id),
        'image' => $image ? get_media_by_id($image, 'xl') : false,
        'year' => carbon_get_post_meta($id, 'year'),
        'subtitle' => carbon_get_post_meta($id, 'subtitle'),
        'description' => carbon_get_post_meta($id, 'description'),
        'description_title' => carbon_get_post_meta($id, 'description_title') ?: 'beschrieb',
        'gallery' => get_project_gallery($id, 'gallery'),
        'link' => carbon_get_post_meta($id, 'link'),
        'link_label' => carbon_get_post_meta($id, 'link_label') ?: 'Projekt ansehen',
        'categories' => get_project_categories($id),
        'stats' => get_project_stats($id),
    ];
}
