<?php
/**
 * In dieser Datei werden die eigenen Styles und Scripts des Themes eingebunden.
 * Als Version dient filemtime(), also das Änderungsdatum der Datei. Nach jeder
 * Änderung lädt der Browser die neue Datei statt der alten aus dem Cache.
 *
 * Dokumentation: https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * Dokumentation: https://developer.wordpress.org/reference/functions/wp_enqueue_script_module/
 */

add_action('wp_enqueue_scripts', function () {
    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    // -> main.css (bindet _fonts.css und _resets.css selbst per @import ein)
    wp_enqueue_style(
        'portfolio-main',
        $uri . '/frontend/styles/main.css',
        [],
        filemtime($dir . '/frontend/styles/main.css')
    );

    // -> main.js als ES-Modul
    $js_version = filemtime($dir . '/frontend/scripts/main.js');
    if (function_exists('wp_enqueue_script_module')) {
        // -> ab WordPress 6.5: gibt type="module" selbst aus, bei klassischen Themes im Footer
        wp_enqueue_script_module('portfolio-main', $uri . '/frontend/scripts/main.js', [], $js_version);
    } else {
        // -> ältere Versionen: normales Script im Footer, type="module" setzt der Filter unten
        wp_enqueue_script('portfolio-main', $uri . '/frontend/scripts/main.js', [], $js_version, true);
    }
});

// -> type="module" für main.js setzen (nur nötig, wenn wp_enqueue_script_module() fehlt)
add_filter('script_loader_tag', function ($tag, $handle) {
    if ($handle !== 'portfolio-main') {
        return $tag;
    }
    // -> ein allfälliges type="text/javascript" zuerst entfernen, damit das Attribut nicht doppelt vorkommt
    $tag = preg_replace('/\stype=(["\'])[^"\']*\1/', '', $tag);
    return str_replace('<script ', '<script type="module" ', $tag);
}, 10, 2);
