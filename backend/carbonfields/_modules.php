<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Konfiguration der verschiedenen Module
Container::make('post_meta', 'Module')
    ->where('post_type', '=', 'page')
    ->add_fields([
        Field::make('complex', 'modules', 'Module')
            // -> hero_front
            ->add_fields('hero_front', 'Hero Startseite', [
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('textarea', 'intro', 'Intro')->set_rows(3),
                Field::make('image', 'image', 'Bild')->set_required(),
                Field::make('text', 'badge_text', 'Text im Signet (roter Kreis)')
                    ->set_help_text('Kurzer Text, der um den roten Kreis läuft, z. B. «multimedia production · fhgr ·»'),
            ])
            // -> hero_default
            ->add_fields('hero_default', 'Hero Standard', [
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('textarea', 'subline', 'Subline')->set_rows(2),
            ])
            // -> text
            ->add_fields('text', 'Text', [
                Field::make('text', 'title', 'Titel'),
                Field::make('rich_text', 'text', 'Text')->set_required(),
            ])
            // -> text_image
            ->add_fields('text_image', 'Text mit Bild', [
                Field::make('text', 'title', 'Titel'),
                Field::make('rich_text', 'text', 'Text')->set_required(),
                Field::make('image', 'image', 'Bild')->set_required(),
                Field::make('select', 'image_position', 'Bildposition')
                    ->set_options(['right' => 'Rechts', 'left' => 'Links']),
            ])
            // -> overview_projects
            ->add_fields('overview_projects', 'Übersicht Projekte', [
                Field::make('text', 'title', 'Titel'),
                Field::make('association', 'selection', 'Auswahl Projekte')
                    ->set_types([['type' => 'post', 'post_type' => 'project']])
                    ->set_help_text('Leer lassen, um alle Projekte in ihrer Reihenfolge anzuzeigen.'),
            ])
            // -> images
            ->add_fields('images', 'Bilder', [
                Field::make('media_gallery', 'images', 'Bilder')
                    ->set_type(['image'])
                    ->set_required(),
            ])
            // -> statement
            ->add_fields('statement', 'Statement', [
                Field::make('textarea', 'text', 'Text')->set_rows(3)->set_required(),
            ])
            // -> contact
            ->add_fields('contact', 'Kontakt', [
                Field::make('text', 'title', 'Titel')->set_help_text('z. B. «thank you.»'),
                Field::make('textarea', 'text', 'Text')->set_rows(3),
                Field::make('image', 'image', 'Bild'),
            ])
            // -> legal_text
            ->add_fields('legal_text', 'Rechtstext', [
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('rich_text', 'text', 'Text')->set_required(),
            ])
            // -> SETTINGS
            ->set_layout('tabbed-vertical')
    ]);

// -> Funktion, um die Module und die entsprechenden Templates auf der Seite zu laden
function render_modules (): void {
    $modules = carbon_get_the_post_meta('modules');
    if (empty($modules)) {
        return;
    }
    foreach ($modules as $module) {
        // -> Modifiziere die Daten des Moduls, falls nötig, basierend auf dem Modultyp
        switch ($module['_type']) {
            case 'hero_front':
                $module['image'] = get_media_by_id($module['image'], 'xl');
                break;
            case 'text_image':
                $module['image'] = get_media_by_id($module['image'], 'l');
                $module['image_position'] = $module['image_position'] ?: 'right';
                break;
            case 'overview_projects':
                $ids = array_map(fn($item) => (int) $item['id'], $module['selection'] ?: []);
                $module['projects'] = array_map('get_project_teaser_by_id', $ids ?: get_project_ids());
                break;
            case 'images':
                $module['images'] = array_map(fn($id) => get_media_by_id($id, 'l'), $module['images'] ?: []);
                break;
            case 'contact':
                $module['image'] = $module['image'] ? get_media_by_id($module['image'], 'm') : false;
                $module['email'] = get_globals_email();
                $module['socials'] = get_globals_socials();
                break;
        }
        // -> Laden des Templates basierend auf dem Modultyp und weitergeben der Daten
        get_template_part(
            'frontend/views/modules/' . $module['_type'],
            null,
            [
                'data' => $module
            ]
        );
    }
}
