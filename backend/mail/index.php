<?php
/**
 * In dieser Datei wird der Mailversand von WordPress (wp_mail()) angepasst,
 * damit Mails aus dem Kontaktformular auch bei Domains mit strengem
 * DMARC (p=reject) ankommen. Ein Plugin ist dafür nicht nötig.
 *
 * Stufe 1, immer aktiv: Der Umschlag-Absender (Return-Path) wird auf die
 * Absenderadresse gesetzt. Sonst nennt PHP eine interne Adresse des
 * Webservers, die nicht zur Domain passt, und die Mail wird abgelehnt.
 *
 * Stufe 2, nur wenn in wp-config.php Zugangsdaten stehen: Versand über
 * SMTP mit einem echten Postfach. Die Zugangsdaten gehören nicht ins Theme,
 * weil das Theme öffentlich auf GitHub liegt. In wp-config.php auf dem Server:
 *
 *   define('PORTFOLIO_SMTP_HOST', 'mail.infomaniak.com');
 *   define('PORTFOLIO_SMTP_USER', 'kontakt@lucabalsiger.ch');
 *   define('PORTFOLIO_SMTP_PASS', 'Kennwort des Postfachs');
 *   // optional, Standard: 465 und ssl
 *   define('PORTFOLIO_SMTP_PORT', 465);
 *   define('PORTFOLIO_SMTP_SECURE', 'ssl');
 *
 * Lokal in Local fehlen diese Angaben, dann fängt Mailpit die Mails ab.
 *
 * Dokumentation: https://developer.wordpress.org/reference/hooks/phpmailer_init/
 */

add_action('phpmailer_init', function (PHPMailer\PHPMailer\PHPMailer $mailer) {
    if (defined('PORTFOLIO_SMTP_HOST') && defined('PORTFOLIO_SMTP_USER') && defined('PORTFOLIO_SMTP_PASS')) {
        // -> Stufe 2: über das Postfach senden, Absender muss genau dieses Postfach sein
        $mailer->isSMTP();
        $mailer->Host = PORTFOLIO_SMTP_HOST;
        $mailer->Port = defined('PORTFOLIO_SMTP_PORT') ? (int) PORTFOLIO_SMTP_PORT : 465;
        $mailer->SMTPSecure = defined('PORTFOLIO_SMTP_SECURE') ? PORTFOLIO_SMTP_SECURE : 'ssl';
        $mailer->SMTPAuth = true;
        $mailer->Username = PORTFOLIO_SMTP_USER;
        $mailer->Password = PORTFOLIO_SMTP_PASS;
        $mailer->setFrom(PORTFOLIO_SMTP_USER, get_bloginfo('name'), false);
    }
    // -> Stufe 1: Umschlag-Absender gleich Absender, damit SPF und DMARC zur Domain passen
    $mailer->Sender = $mailer->From;
});

// -> Fehlgeschlagene Mails ins PHP-Fehlerprotokoll schreiben, damit sich die Ursache finden lässt
add_action('wp_mail_failed', function (WP_Error $error) {
    error_log('Portfolio: Mailversand fehlgeschlagen: ' . $error->get_error_message());
});
