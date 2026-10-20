<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Inhaltsfelder des Post Types «Projekt»
Container::make('post_meta', 'Projekt')
    ->where('post_type', '=', 'project')
    ->add_tab('Kopf', [
        Field::make('image', 'image', 'Herobild')
            ->set_required()
            ->set_help_text('Wird oben auf der Projektseite und als Teaser in der Übersicht verwendet.'),
        Field::make('text', 'year', 'Jahr')
            ->set_width(25),
        Field::make('text', 'subtitle', 'Untertitel')
            ->set_width(75)
            ->set_help_text('z. B. «Fotografie · Adobe Photoshop · Modul IM5»'),
    ])
    ->add_tab('Beschrieb', [
        Field::make('text', 'description_title', 'Titel')
            ->set_help_text('z. B. «über das projekt». Leer: «beschrieb»'),
        Field::make('rich_text', 'description', 'Beschrieb'),
    ])
    ->add_tab('Galerie', [
        Field::make('media_gallery', 'gallery', 'Fotogalerie')
            ->set_type(['image'])
            ->set_help_text('Bilder lassen sich per Drag-and-drop sortieren. Alt-Text in der Mediathek setzen.'),
    ])
    ->add_tab('Link', [
        Field::make('text', 'link_label', 'Linktext')
            ->set_width(33)
            ->set_help_text('z. B. «Zur Live-Seite». Leer: «Projekt ansehen»'),
        Field::make('text', 'link', 'Externer Link (optional)')
            ->set_width(67)
            ->set_attribute('type', 'url')
            ->set_help_text('Vollständige Adresse mit https://'),
    ]);
