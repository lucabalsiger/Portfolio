<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Inhaltsfelder des Post Types «Projekt» (Case Study)
Container::make('post_meta', 'Projekt')
    ->where('post_type', '=', 'project')
    ->add_tab('Übersicht', [
        Field::make('image', 'image', 'Teaserbild')->set_required(),
        Field::make('text', 'year', 'Jahr')->set_width(33),
        Field::make('text', 'context', 'Kontext (Kunde, Modul, Auftrag)')->set_width(33),
        Field::make('text', 'role', 'Meine Rolle')->set_width(33),
        Field::make('textarea', 'lead', 'Lead')->set_rows(3),
        Field::make('text', 'link', 'Externer Link (optional)')->set_attribute('type', 'url'),
    ])
    ->add_tab('Konzept & Ziel', [
        Field::make('rich_text', 'concept', 'Konzept'),
        Field::make('rich_text', 'goal', 'Ziel'),
    ])
    ->add_tab('Prozess', [
        Field::make('rich_text', 'process', 'Prozess'),
        Field::make('media_gallery', 'process_images', 'Prozessbilder')->set_type(['image']),
    ])
    ->add_tab('Ergebnis', [
        Field::make('rich_text', 'outcome', 'Ergebnis'),
        Field::make('media_gallery', 'outcome_images', 'Ergebnisbilder')->set_type(['image']),
    ]);
