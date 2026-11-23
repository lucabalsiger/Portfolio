<?php
/**
 * In dieser Datei werden Angaben für Suchmaschinen (SEO) und KI-Suchsysteme
 * (GEO) in den <head> geschrieben:
 * - <meta name="description"> aus dem Inhalt der Seite, sonst die Standard-Beschreibung aus «Globales»
 * - Open Graph und Twitter Card: Titel, Beschreibung und Bild beim Teilen eines Links
 * - Canonical-Link nur für eine Startseite ohne statische Seite (sonst setzt ihn WordPress selbst)
 * - strukturierte Daten als JSON-LD (schema.org): Person, Webseite und Projekte als CreativeWork
 * - noindex für die 404-Seite
 *
 * Ist ein SEO-Plugin wie Yoast oder Rank Math aktiv, gibt das Theme nichts aus,
 * damit keine Angaben doppelt im <head> stehen.
 *
 * Dokumentation: https://ogp.me/
 * Dokumentation: https://schema.org/CreativeWork
 * Dokumentation: https://developers.google.com/search/docs/appearance/structured-data/intro-structured-data
 */

// -> Ist ein bekanntes SEO-Plugin aktiv?
function seo_plugin_active(): bool
{
    return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION') || defined('SEOPRESS_VERSION');
}

// -> Beschreibung der aktuellen Seite, gekürzt auf 155 Zeichen
function get_seo_description(): string
{
    $text = '';
    $id = get_queried_object_id();
    if (is_singular('project')) {
        // -> Projekt: Beschrieb, sonst Untertitel
        $text = wp_strip_all_tags(carbon_get_post_meta($id, 'description') ?: '') ?: carbon_get_post_meta($id, 'subtitle');
    } elseif (is_page() && !is_front_page()) {
        // -> Seite: erster Text aus den Modulen (Subline, Text, Statement, Kontakt)
        foreach (carbon_get_post_meta($id, 'modules') ?: [] as $module) {
            $text = match ($module['_type']) {
                'hero_default' => $module['subline'] ?? '',
                'text', 'text_image', 'statement', 'contact' => wp_strip_all_tags($module['text'] ?? ''),
                default => '',
            };
            if (trim($text) !== '') {
                break;
            }
        }
    }
    return shorten_text(trim($text) ?: get_globals_meta_description());
}

// -> Bild taugt zum Teilen: Rasterbild mit Grösse (SVG zeigen Facebook, LinkedIn & Co. nicht an)
function is_shareable_image(array|false $image): bool
{
    return $image && !empty($image['src']) && !empty($image['width']) && !str_ends_with(strtolower($image['src']), '.svg');
}

// -> Vorschaubild der aktuellen Seite: Herobild des Projekts oder erstes Bild aus den Modulen, sonst das Vorschaubild aus «Globales»
function get_seo_image(): array|false
{
    $id = get_queried_object_id();
    $image_id = 0;
    if (is_singular('project')) {
        $image_id = carbon_get_post_meta($id, 'image');
    } elseif (is_page()) {
        foreach (carbon_get_post_meta($id, 'modules') ?: [] as $module) {
            if (in_array($module['_type'], ['hero_front', 'text_image', 'contact'], true) && !empty($module['image'])) {
                $image_id = $module['image'];
                break;
            }
        }
    }
    $image = $image_id ? get_media_by_id($image_id, 'm') : false;
    if (is_shareable_image($image)) {
        return $image;
    }
    $fallback = get_globals_share_image();
    return is_shareable_image($fallback) ? $fallback : false;
}

// -> Strukturierte Daten (schema.org) als @graph. Die Person ist die Inhaberin der Webseite (Name der Webseite)
function get_seo_schema(string $description, array|false $image): array
{
    $person_id = home_url('/#person');
    $graph = [
        array_filter([
            '@type' => 'Person',
            '@id' => $person_id,
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'description' => get_globals_meta_description(),
            'sameAs' => array_values(array_filter(array_column(get_globals_socials(), 'link'))),
        ]),
    ];
    if (is_front_page()) {
        $graph[] = [
            '@type' => 'WebSite',
            '@id' => home_url('/#website'),
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'inLanguage' => get_bloginfo('language'),
            'author' => ['@id' => $person_id],
        ];
    }
    if (is_singular('project')) {
        $id = get_queried_object_id();
        $year = (string) carbon_get_post_meta($id, 'year');
        $graph[] = array_filter([
            '@type' => 'CreativeWork',
            '@id' => get_permalink($id) . '#project',
            'name' => get_the_title($id),
            'url' => get_permalink($id),
            'description' => $description,
            'image' => $image['src'] ?? null,
            'dateCreated' => preg_match('/^\d{4}$/', $year) ? $year : null,
            'keywords' => implode(', ', get_project_categories($id)) ?: null,
            'creator' => ['@id' => $person_id],
            'inLanguage' => get_bloginfo('language'),
        ]);
    }
    return ['@context' => 'https://schema.org', '@graph' => $graph];
}

// -> Alles in den <head> schreiben
add_action('wp_head', function () {
    if (seo_plugin_active() || is_404()) {
        return;
    }
    $description = get_seo_description();
    $image = get_seo_image();
    $url = is_front_page() ? home_url('/') : (is_singular() ? get_permalink() : '');
    ?>
    <?php if ($description): ?>
        <meta name="description" content="<?= esc_attr($description) ?>">
    <?php endif; ?>
    <?php // -> bei Seiten und Projekten (auch einer statischen Startseite) setzt WordPress den Canonical selbst, sonst stünde er doppelt ?>
    <?php if (is_front_page() && !is_singular()): ?>
        <link rel="canonical" href="<?= esc_url($url) ?>">
    <?php endif; ?>
    <meta property="og:type" content="<?= is_singular('project') ? 'article' : 'website' ?>">
    <meta property="og:site_name" content="<?= esc_attr(get_bloginfo('name')) ?>">
    <meta property="og:locale" content="<?= esc_attr(get_locale()) ?>">
    <meta property="og:title" content="<?= esc_attr(wp_get_document_title()) ?>">
    <?php if ($description): ?>
        <meta property="og:description" content="<?= esc_attr($description) ?>">
    <?php endif; ?>
    <?php if ($url): ?>
        <meta property="og:url" content="<?= esc_url($url) ?>">
    <?php endif; ?>
    <?php if ($image): ?>
        <meta property="og:image" content="<?= esc_url($image['src']) ?>">
        <meta property="og:image:width" content="<?= esc_attr($image['width']) ?>">
        <meta property="og:image:height" content="<?= esc_attr($image['height']) ?>">
        <meta property="og:image:alt" content="<?= esc_attr($image['alt']) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="<?= $image ? 'summary_large_image' : 'summary' ?>">
    <?php // -> JSON_HEX_TAG wandelt < und > um, damit ein Text nie das <script> beenden kann ?>
    <script type="application/ld+json"><?= wp_json_encode(get_seo_schema($description, $image), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
    <?php
}, 5);

// -> Die 404-Seite soll nicht in Suchmaschinen erscheinen
add_filter('wp_robots', function (array $robots) {
    if (is_404()) {
        $robots['noindex'] = true;
        $robots['follow'] = true;
    }
    return $robots;
});
