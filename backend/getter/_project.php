<?php

// -> IDs aller veröffentlichten Projekte in ihrer Reihenfolge
function get_project_ids(): array {
    return get_posts([
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC'],
        'fields' => 'ids',
    ]);
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

// -> Bilder einer Galerie laden
function get_project_gallery(int $id, string $field): array {
    $ids = carbon_get_post_meta($id, $field) ?: [];
    return array_map(fn($image_id) => get_media_by_id($image_id, 'l'), $ids);
}

// -> Kurzdaten für Teaser in der Projektübersicht
function get_project_teaser_by_id(int $id): array {
    $image = carbon_get_post_meta($id, 'image');
    return [
        'title' => get_the_title($id),
        'url' => get_permalink($id),
        'number' => get_project_number($id),
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
        'context' => carbon_get_post_meta($id, 'context'),
        'role' => carbon_get_post_meta($id, 'role'),
        'lead' => carbon_get_post_meta($id, 'lead'),
        'link' => carbon_get_post_meta($id, 'link'),
        'categories' => get_project_categories($id),
        'concept' => carbon_get_post_meta($id, 'concept'),
        'goal' => carbon_get_post_meta($id, 'goal'),
        'process' => carbon_get_post_meta($id, 'process'),
        'process_images' => get_project_gallery($id, 'process_images'),
        'outcome' => carbon_get_post_meta($id, 'outcome'),
        'outcome_images' => get_project_gallery($id, 'outcome_images'),
    ];
}
