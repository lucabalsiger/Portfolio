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
                Field::make('complex', 'intro_lines', 'Intro Zeilen')
                    ->add_fields([
                        Field::make('text', 'text', 'Text der Zeile'),
                        Field::make('select', 'color', 'Farbe')
                            ->set_options([
                                'accent' => 'Akzentrot (Standard)',
                                'ink'    => 'Schwarz — passt sich Dark Mode an',
                                'paper'  => 'Weiss — passt sich Dark Mode an',
                            ]),
                        Field::make('select', 'style', 'Füllstil')
                            ->set_options([
                                'filled'   => 'Gefüllt',
                                'outlined' => 'Outline (nur Kontur)',
                                'grainy'   => 'Grain',
                            ]),
                        Field::make('select', 'stroke_width', 'Strichstärke')
                            ->set_options([
                                'thin'   => 'Dünn',
                                'medium' => 'Mittel',
                                'thick'  => 'Dick',
                                'heavy'  => 'Sehr dick',
                            ])
                            ->set_default_value('medium')
                            ->set_help_text('Bei Outline: Stärke der Kontur. Bei Gefüllt: Stärke der Outline beim Hover.'),
                        Field::make('select', 'spacing', 'Abstand nach unten')
                            ->set_options([
                                'default' => 'Standard — eng beieinander',
                                'overlap' => 'Überlappend',
                                'tight'   => 'Sehr eng',
                                'small'   => 'Klein',
                                'medium'  => 'Mittel',
                                'large'   => 'Gross',
                                'huge'    => 'Sehr gross',
                            ])
                            ->set_default_value('default'),
                        Field::make('select', 'size', 'Schriftgrösse')
                            ->set_options([
                                'full'      => 'Voll — füllt gesamte Breite (Standard)',
                                'oversized' => 'Übergroß — bricht aus dem Raster',
                                'large'     => 'Gross — 75 %',
                                'medium'    => 'Mittel — 55 %',
                                'small'     => 'Klein — 38 %',
                                'tiny'      => 'Winzig — 22 %',
                            ])
                            ->set_default_value('full'),
                        Field::make('select', 'stretch', 'Breite / Stretch')
                            ->set_options([
                                'normal'           => 'Normal (Standard)',
                                'wide'             => 'Breiter — 120 %',
                                'expanded'         => 'Sehr breiter — 140 %',
                                'narrow'           => 'Schmal — 88 %',
                                'condensed'        => 'Komprimiert — 75 %',
                                'ultra-condensed'  => 'Sehr komprimiert — 60 %',
                            ])
                            ->set_default_value('normal'),
                        Field::make('checkbox', 'italic', 'Kursiv'),
                        Field::make('select', 'font', 'Schrift')
                            ->set_options([
                                'default'   => 'Archivo — geometrisch (Standard)',
                                'grotesk'   => 'Space Grotesk — modern, mit Eigenheit',
                                'shoulders' => 'Big Shoulders Display — ultrakondensiert',
                                'barlow'    => 'Barlow Condensed — minimal, editorial',
                            ]),
                    ])
                    ->set_help_text('Jede Zeile wird automatisch auf die volle Breite skaliert.'),
                Field::make('checkbox', 'show_photo', 'Portrait-Foto anzeigen')
                    ->set_default_value('yes')
                    ->set_help_text('Deaktiviert: Signet wird vergrössert dort platziert, wo das Foto wäre.'),
                Field::make('image', 'image', 'Bild'),
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
