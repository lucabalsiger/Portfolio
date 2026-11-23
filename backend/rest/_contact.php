<?php
/**
 * Kontaktformular. Das Formular im Kontaktmodul schickt die Daten per fetch()
 * an diesen Endpunkt. Die Nachricht wird in «portfolio_messages» (backend/db/)
 * gespeichert und per wp_mail() an die E-Mail-Adresse aus «Globales» geschickt.
 *
 *   POST /wp-json/portfolio/v1/contact
 *   Felder: name, email, message, token, website (Honeypot, muss leer sein)
 *
 * Antwort bei Erfolg (200): { "message": "danke ..." }
 * Bei ungültigen Feldern (400): WP_Error mit data.fields = { feldname: fehlermeldung }
 *
 * Schutz gegen Spam, ohne Captcha und ohne Dienste von Dritten:
 * - Honeypot: ein verstecktes Feld, das nur Bots ausfüllen
 * - Zeitstempel: signiert mit wp_hash(). Wer schneller als in 3 Sekunden absendet, ist kein Mensch
 * - Limit: höchstens 3 Nachrichten pro Person und Stunde (IP nur gehasht, als Transient)
 * Bots erhalten trotzdem eine Erfolgsmeldung, damit sie nicht lernen, was sie verraten hat.
 */

const PORTFOLIO_CONTACT_MIN_SECONDS = 3;
const PORTFOLIO_CONTACT_LIMIT = 3;
const PORTFOLIO_CONTACT_SUCCESS = 'danke für deine nachricht! ich melde mich so bald wie möglich.';

// -> Zeitstempel für das Formular, signiert, damit er sich nicht fälschen lässt
function get_contact_form_token(): string
{
    $time = (string) time();
    return $time . '.' . wp_hash('portfolio_contact|' . $time);
}

// -> Zeitstempel prüfen: 'spam' (gefälscht oder zu schnell), 'expired' (älter als ein Tag) oder 'ok'
function check_contact_form_token(string $token): string
{
    [$time, $hash] = array_pad(explode('.', $token, 2), 2, '');
    if (!ctype_digit($time) || !hash_equals(wp_hash('portfolio_contact|' . $time), $hash)) {
        return 'spam';
    }
    $age = time() - (int) $time;
    if ($age < PORTFOLIO_CONTACT_MIN_SECONDS) {
        return 'spam';
    }
    return $age > DAY_IN_SECONDS ? 'expired' : 'ok';
}

// -> Felder prüfen. Gibt pro fehlerhaftem Feld eine Meldung zurück, leer wenn alles stimmt
function validate_contact_fields(string $name, string $email, string $message): array
{
    $errors = [];
    if ($name === '') {
        $errors['name'] = 'bitte gib deinen namen ein.';
    } elseif (mb_strlen($name) > 100) {
        $errors['name'] = 'der name darf höchstens 100 zeichen lang sein.';
    }
    if (!is_email($email)) {
        $errors['email'] = 'bitte gib eine gültige e-mail-adresse ein.';
    }
    if (mb_strlen($message) < 10) {
        $errors['message'] = 'deine nachricht braucht mindestens 10 zeichen.';
    } elseif (mb_strlen($message) > 5000) {
        $errors['message'] = 'deine nachricht darf höchstens 5000 zeichen lang sein.';
    }
    return $errors;
}

// -> Nachricht per E-Mail weiterleiten. Reply-To enthält nur die geprüfte Adresse, nie den Namen (Schutz vor Header-Injection)
function send_contact_mail(string $name, string $email, string $message): bool
{
    $to = get_globals_email() ?: get_option('admin_email');
    $subject = 'Neue Nachricht von ' . $name . ' über ' . get_bloginfo('name');
    $body = "Name: $name\nE-Mail: $email\n\n$message";
    return wp_mail($to, $subject, $body, ['Reply-To: ' . $email]);
}

// -> Anfrage verarbeiten: Spam abfangen, prüfen, Limit, speichern und senden
function handle_contact_request(WP_REST_Request $request): WP_REST_Response|WP_Error
{
    $success = rest_ensure_response(['message' => PORTFOLIO_CONTACT_SUCCESS]);

    $token_status = check_contact_form_token((string) $request->get_param('token'));
    if ((string) $request->get_param('website') !== '' || $token_status === 'spam') {
        return $success; // -> Bot: nichts speichern, nichts senden
    }
    if ($token_status === 'expired') {
        return new WP_Error('portfolio_expired', 'das formular ist abgelaufen. bitte lade die seite neu und versuch es nochmals.', ['status' => 400]);
    }

    $name = sanitize_text_field((string) $request->get_param('name'));
    $email = sanitize_email((string) $request->get_param('email'));
    $message = sanitize_textarea_field((string) $request->get_param('message'));
    $errors = validate_contact_fields($name, $email, $message);
    if ($errors) {
        return new WP_Error('portfolio_invalid', 'bitte prüfe die markierten felder.', ['status' => 400, 'fields' => $errors]);
    }

    $limit_key = 'portfolio_contact_' . wp_hash($_SERVER['REMOTE_ADDR'] ?? '');
    $count = (int) get_transient($limit_key);
    if ($count >= PORTFOLIO_CONTACT_LIMIT) {
        return new WP_Error('portfolio_limit', 'du hast schon mehrere nachrichten geschickt. bitte versuch es in einer stunde nochmals.', ['status' => 429]);
    }

    // -> Gespeichert oder gesendet reicht: dann ist die Nachricht nicht verloren
    $saved = insert_contact_message($name, $email, $message);
    $sent = send_contact_mail($name, $email, $message);
    if (!$saved && !$sent) {
        return new WP_Error('portfolio_send_error', 'deine nachricht konnte nicht gesendet werden. bitte schreib mir direkt per e-mail.', ['status' => 500]);
    }
    set_transient($limit_key, $count + 1, HOUR_IN_SECONDS);
    return $success;
}

add_action('rest_api_init', function () {
    register_rest_route('portfolio/v1', '/contact', [
        'methods' => 'POST',
        'callback' => 'handle_contact_request',
        'permission_callback' => '__return_true', // -> öffentlich, auch ohne Anmeldung
    ]);
});
