<?php
register_taxonomy(
    'project_category',
    ['project'],
    [
        'label' => 'Projektkategorie',
        'labels' => [
            'name' => 'Projektkategorien',
            'singular_name' => 'Projektkategorie',
            'menu_name' => 'Kategorien',
            'all_items' => 'Alle Kategorien',
            'edit_item' => 'Kategorie bearbeiten',
            'view_item' => 'Kategorie ansehen',
            'update_item' => 'Kategorie updaten',
            'add_new_item' => 'Neue Kategorie hinzufügen',
        ],
        'public' => true,
        // keine Kategorie-Archivseiten im Frontend, Kategorien erscheinen nur in den Teasern
        'publicly_queryable' => false,
        // show_in_rest true, damit die Kategorien im klassischen Editor als Box erscheinen
        'show_in_rest' => true,
        'show_in_nav_menus' => false,
        'show_admin_column' => true,
        'query_var' => true,
        'hierarchical' => true,
        // false statt weglassen: sonst erzeugt WordPress Standard-URLs (/project_category/...), die auf die Startseite führen
        'rewrite' => false,
    ],
);
