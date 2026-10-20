<?php
// Einstiegspunkt des Themes. Lädt alle Backend-Module der Reihe nach.
require_once __DIR__ . '/backend/init/index.php';           // Basis-Setup: Bildgrössen, Bereinigung
require_once __DIR__ . '/backend/helpers/index.php';        // Globale Hilfsfunktionen
require_once __DIR__ . '/backend/nav/index.php';            // Navigationsmenüs registrieren
require_once __DIR__ . '/backend/cpt/index.php';            // Custom Post Types (Projekte)
require_once __DIR__ . '/backend/tax/index.php';            // Taxonomien (Projektkategorien)
require_once __DIR__ . '/backend/carbonfields/index.php';   // CMS-Felder (Carbon Fields)
require_once __DIR__ . '/backend/getter/index.php';         // Getter: Daten für Templates aufbereiten
