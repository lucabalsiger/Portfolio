<?php

// -> Logo als Bilddaten, false wenn keines erfasst ist
function get_globals_logo(): array|false {
    $logo = carbon_get_theme_option('logo');
    if (!$logo) {
        return false;
    }
    return get_media_by_id($logo, 'xs');
}

// -> E-Mail-Adresse, false wenn keine gültige erfasst ist
function get_globals_email(): string|false {
    $email = sanitize_email(carbon_get_theme_option('email') ?: '');
    if (!$email || !is_email($email)) {
        return false;
    }
    return $email;
}

// -> Footer-Text, Fallback: Name der Webseite und aktuelles Jahr
function get_globals_footer_text(): string {
    $text = carbon_get_theme_option('footer_text');
    return $text ?: get_bloginfo('name') . ' / ' . wp_date('Y');
}

// -> GitHub-Benutzername, false wenn keiner oder ein ungültiger erfasst ist (erlaubt: Buchstaben, Zahlen, Bindestrich, max. 39 Zeichen)
function get_globals_github_username(): string|false {
    $username = trim(carbon_get_theme_option('github_username') ?: '');
    if (!preg_match('/^[a-z\d](?:[a-z\d-]{0,38})$/i', $username)) {
        return false;
    }
    return $username;
}

// -> Standard-Beschreibung für Suchmaschinen, Fallback: Untertitel aus Einstellungen > Allgemein
function get_globals_meta_description(): string {
    return trim(carbon_get_theme_option('meta_description') ?: get_bloginfo('description'));
}

// -> Vorschaubild für Social Media als Bilddaten, false wenn keines erfasst ist
function get_globals_share_image(): array|false {
    $image = carbon_get_theme_option('share_image');
    if (!$image) {
        return false;
    }
    return get_media_by_id($image, 'm');
}

// -> Socials als Liste mit Titel, Link und optionalem Icon
function get_globals_socials(): array {
    $socials = carbon_get_theme_option('socials') ?: [];
    return array_map(function ($social) {
        return [
            'title' => $social['title'],
            'link' => $social['link'],
            'icon' => $social['icon'] ? get_media_by_id($social['icon'], 'xxs') : false,
        ];
    }, $socials);
}
