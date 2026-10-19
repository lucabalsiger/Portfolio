<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// -> Inhaltsfeld-Konfiguration der globalen Einstellungen des Themes
Container::make('theme_options', 'Globales')
    ->add_fields([
        Field::make('image', 'logo', 'Logo / Wortmarke')
            ->set_help_text('Optional. Ohne Logo wird der Name der Webseite angezeigt. Nur eigene, geprüfte SVG-Dateien hochladen.'),
        Field::make('text', 'email', 'E-Mail-Adresse Kontakt')
            ->set_attribute('type', 'email'),
        Field::make('text', 'footer_text', 'Footer-Text')
            ->set_help_text('z. B. «Luca Balsiger / 2026»'),
        Field::make('complex', 'socials', 'Socials')
            ->add_fields([
                Field::make('text', 'title', 'Titel')->set_required(),
                Field::make('text', 'link', 'Link')
                    ->set_attribute('type', 'url')
                    ->set_required(),
                Field::make('image', 'icon', 'Icon'),
            ])
            ->set_header_template('<%- title %>')
            ->set_layout('tabbed-horizontal'),
    ]);
