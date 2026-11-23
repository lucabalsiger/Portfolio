<?php
// Kontaktformular. Erwartet $args['form'] mit action, token und privacy_url (aufbereitet in _modules.php).
// main.js schickt es per fetch() ab und zeigt Fehler direkt beim Feld. Ohne JavaScript sendet der Browser es normal.
// Das Feld «website» ist ein Honeypot: unsichtbar, nur Bots füllen es aus.
$form = $args['form'];
$id = 'contact-' . wp_unique_id();
?>
<form class="contact-form" action="<?= esc_url($form['action']) ?>" method="post">
    <div class="form-field">
        <label for="<?= esc_attr($id) ?>-name">name</label>
        <input id="<?= esc_attr($id) ?>-name" name="name" type="text" autocomplete="name" maxlength="100" required aria-describedby="<?= esc_attr($id) ?>-name-error">
        <p class="field-error" id="<?= esc_attr($id) ?>-name-error" data-error-for="name"></p>
    </div>
    <div class="form-field">
        <label for="<?= esc_attr($id) ?>-email">e-mail</label>
        <input id="<?= esc_attr($id) ?>-email" name="email" type="email" autocomplete="email" required aria-describedby="<?= esc_attr($id) ?>-email-error">
        <p class="field-error" id="<?= esc_attr($id) ?>-email-error" data-error-for="email"></p>
    </div>
    <div class="form-field">
        <label for="<?= esc_attr($id) ?>-message">nachricht</label>
        <textarea id="<?= esc_attr($id) ?>-message" name="message" rows="5" minlength="10" maxlength="5000" required aria-describedby="<?= esc_attr($id) ?>-message-error"></textarea>
        <p class="field-error" id="<?= esc_attr($id) ?>-message-error" data-error-for="message"></p>
    </div>
    <div class="form-honeypot" aria-hidden="true">
        <label for="<?= esc_attr($id) ?>-website">website</label>
        <input id="<?= esc_attr($id) ?>-website" name="website" type="text" tabindex="-1" autocomplete="off">
    </div>
    <input type="hidden" name="token" value="<?= esc_attr($form['token']) ?>">
    <p class="form-privacy">
        deine angaben verwende ich nur, um dir zu antworten.
        <?php if ($form['privacy_url']): ?>
            mehr dazu in der <a href="<?= esc_url($form['privacy_url']) ?>">datenschutzerklärung</a>.
        <?php endif; ?>
    </p>
    <button class="form-submit" type="submit">nachricht senden</button>
    <p class="form-status" role="status"></p>
</form>
