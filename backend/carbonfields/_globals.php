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
        Field::make('text', 'github_username', 'GitHub-Benutzername')
            ->set_help_text('Für das Modul «GitHub-Repos», z. B. «lucabalsiger». Nur öffentliche Repos werden angezeigt.'),
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
        Field::make('separator', 'seo_separator', 'Suchmaschinen und Social Media'),
        Field::make('textarea', 'meta_description', 'Standard-Beschreibung')
            ->set_rows(3)
            ->set_attribute('maxLength', 160)
            ->set_help_text('Kurze Beschreibung der Webseite für Google und beim Teilen von Links (höchstens 160 Zeichen). Gilt für die Startseite und alle Seiten ohne eigenen Text.'),
        Field::make('image', 'share_image', 'Vorschaubild')
            ->set_help_text('Erscheint beim Teilen eines Links, wenn die Seite kein eigenes Bild hat. Ideal: 1200 × 630 px, JPG oder PNG (kein SVG).'),
    ]);
