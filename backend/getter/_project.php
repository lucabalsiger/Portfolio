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

// -> Galeriebilder mit Vorschau- und Grossversion für die Lightbox
function get_project_gallery(int $id, string $field): array {
    $ids = carbon_get_post_meta($id, $field) ?: [];
    $images = [];
    foreach ($ids as $image_id) {
        $thumb = wp_get_attachment_image_src((int) $image_id, 'large');
        $full = wp_get_attachment_image_src((int) $image_id, 'full');
        if (!$thumb || !$full) {
            continue;
        }
        $images[] = [
            'thumb' => $thumb[0],
            'full' => $full[0],
            'alt' => get_post_meta((int) $image_id, '_wp_attachment_image_alt', true) ?: '',
        ];
    }
    return $images;
}

// -> Kurzdaten für Teaser in der Projektübersicht
function get_project_teaser_by_id(int $id): array {
    $image = carbon_get_post_meta($id, 'image');
    return [
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
    ];
}
