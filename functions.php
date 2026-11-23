<?php
// Einstiegspunkt des Themes. Lädt alle Backend-Module der Reihe nach.
require_once __DIR__ . '/backend/init/index.php';           // Basis-Setup: Bildgrössen, Bereinigung
require_once __DIR__ . '/backend/enqueue/index.php';        // Styles und Scripts einbinden (main.css, main.js)
require_once __DIR__ . '/backend/helpers/index.php';        // Globale Hilfsfunktionen
require_once __DIR__ . '/backend/nav/index.php';            // Navigationsmenüs registrieren
require_once __DIR__ . '/backend/cpt/index.php';            // Custom Post Types (Projekte)
require_once __DIR__ . '/backend/tax/index.php';            // Taxonomien (Projektkategorien)
require_once __DIR__ . '/backend/db/index.php';             // Eigene Datenbank-Tabellen (Statistik, Nachrichten)
require_once __DIR__ . '/backend/rest/index.php';           // Eigene REST-Endpunkte (Statistik, Kontaktformular)
require_once __DIR__ . '/backend/mail/index.php';           // Mailversand: Absender passend zur Domain, optional SMTP
require_once __DIR__ . '/backend/admin/index.php';          // Backend-Ansichten: Statistik, Nachrichten
require_once __DIR__ . '/backend/carbonfields/index.php';   // CMS-Felder (Carbon Fields)
require_once __DIR__ . '/backend/getter/index.php';         // Getter: Daten für Templates aufbereiten
require_once __DIR__ . '/backend/seo/index.php';            // SEO: Beschreibung, Open Graph, strukturierte Daten
