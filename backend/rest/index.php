<?php
/**
 * In diesem Ordner werden die eigenen REST-Endpunkte des Themes registriert.
 * Alle liegen unter /wp-json/portfolio/v1/ und werden von main.js per
 * fetch() aufgerufen. Pro Thema gibt es eine eigene Datei, die hier
 * importiert wird.
 *
 * Dokumentation: https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 */

require_once __DIR__ . '/_stats.php';      // Aufrufe und Applaus zählen
require_once __DIR__ . '/_contact.php';    // Kontaktformular
