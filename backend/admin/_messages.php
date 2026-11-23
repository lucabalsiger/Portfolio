<?php
/**
 * Backend-Seite «Nachrichten»: listet die Nachrichten aus dem Kontaktformular
 * (Tabelle «portfolio_messages», backend/db/), die neuste zuerst, mit einem
 * Antwort-Link und der Möglichkeit, einzelne Nachrichten zu löschen.
 *
 * Nur für Admins (manage_options), weil die Nachrichten Personendaten enthalten.
 *
 * Dokumentation: https://developer.wordpress.org/reference/functions/add_menu_page/
 */

const PORTFOLIO_MESSAGES_PER_PAGE = 20;

// -> Menüpunkt direkt unter «Projekte»
add_action('admin_menu', function () {
    add_menu_page('Nachrichten', 'Nachrichten', 'manage_options', 'portfolio-messages', 'render_messages_page', 'dashicons-email', 21);
});

// -> Inhalt der Seite «Nachrichten»
function render_messages_page(): void
{
    $total = count_contact_messages();
    $pages = max(1, (int) ceil($total / PORTFOLIO_MESSAGES_PER_PAGE));
    $page = min($pages, max(1, absint($_GET['paged'] ?? 1)));
    $messages = get_contact_messages($page, PORTFOLIO_MESSAGES_PER_PAGE);
    $reply_subject = 'Re: deine Nachricht über ' . get_bloginfo('name');
    ?>
    <div class="wrap">
        <h1>Nachrichten</h1>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="notice notice-success is-dismissible"><p>Die Nachricht wurde gelöscht.</p></div>
        <?php endif; ?>
        <?php render_test_mail_box(); ?>
        <p>Nachrichten aus dem Kontaktformular, die neuste zuerst. Sie bleiben gespeichert, bis sie hier gelöscht werden.</p>
        <?php if (!$messages): ?>
            <p><strong>Noch keine Nachrichten.</strong></p>
        <?php else: ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th style="width: 130px">Datum</th>
                        <th style="width: 240px">Von</th>
                        <th>Nachricht</th>
                        <th style="width: 180px">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?= esc_html($message['date']) ?></td>
                            <td>
                                <strong><?= esc_html($message['name']) ?></strong><br>
                                <?= esc_html($message['email']) ?>
                            </td>
                            <td><?= nl2br(esc_html($message['message'])) ?></td>
                            <td>
                                <a class="button" href="<?= esc_url('mailto:' . $message['email'] . '?subject=' . rawurlencode($reply_subject)) ?>">Antworten</a>
                                <form method="post" action="<?= esc_url(admin_url('admin-post.php')) ?>" style="display: inline"
                                    onsubmit="return confirm('Diese Nachricht unwiderruflich löschen?');">
                                    <input type="hidden" name="action" value="portfolio_delete_message">
                                    <input type="hidden" name="id" value="<?= esc_attr($message['id']) ?>">
                                    <?php wp_nonce_field('portfolio_delete_message_' . $message['id']); ?>
                                    <button type="submit" class="button button-link-delete">Löschen</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($pages > 1): ?>
                <div class="tablenav"><div class="tablenav-pages">
                    <?= paginate_links([
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'current' => $page,
                        'total' => $pages,
                    ]) ?>
                </div></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php
}

// -> Kasten «Mailversand»: Empfänger des Kontaktformulars und Testmail mit Ergebnis der letzten Prüfung
function render_test_mail_box(): void
{
    $result = isset($_GET['testmail']) ? get_transient('portfolio_test_mail_result') : false;
    if ($result) {
        delete_transient('portfolio_test_mail_result');
        ?>
        <div class="notice notice-<?= $result['sent'] ? 'success' : 'error' ?>">
            <p>
                <?php if ($result['sent']): ?>
                    <strong>Die Testmail an <?= esc_html($result['to']) ?> wurde verschickt.</strong>
                    Kommt sie nicht an, im Spam-Ordner nachschauen.
                <?php else: ?>
                    <strong>Testmail an <?= esc_html($result['to']) ?> fehlgeschlagen:</strong> <?= esc_html($result['error'] ?: 'keine Fehlermeldung') ?>
                <?php endif; ?>
            </p>
        </div>
        <?php
    }
    ?>
    <div class="card" style="max-width: none; margin-bottom: 16px">
        <h2 class="title">Mailversand</h2>
        <p>Nachrichten aus dem Kontaktformular gehen an <code><?= esc_html(get_globals_email() ?: get_option('admin_email')) ?></code> (änderbar unter «Globales»).</p>
        <form method="post" action="<?= esc_url(admin_url('admin-post.php')) ?>">
            <input type="hidden" name="action" value="portfolio_test_mail">
            <?php wp_nonce_field('portfolio_test_mail'); ?>
            <button type="submit" class="button">Testmail senden</button>
        </form>
    </div>
    <?php
}

// -> Testmail senden und die Fehlermeldung des Mailservers für die Anzeige merken (nur Admins, mit Nonce)
add_action('admin_post_portfolio_test_mail', function () {
    if (!current_user_can('manage_options')) {
        wp_die('Keine Berechtigung.', 403);
    }
    check_admin_referer('portfolio_test_mail');
    $error = '';
    $catch = function (WP_Error $wp_error) use (&$error) {
        $error = $wp_error->get_error_message();
    };
    add_action('wp_mail_failed', $catch);
    $to = get_globals_email() ?: get_option('admin_email');
    $sent = wp_mail($to, 'Testmail von ' . get_bloginfo('name'), "Diese Testmail kommt aus dem Backend (Nachrichten > Testmail senden).\nWenn du sie liest, funktioniert der Mailversand.");
    remove_action('wp_mail_failed', $catch);
    set_transient('portfolio_test_mail_result', ['sent' => $sent, 'to' => $to, 'error' => $error], 5 * MINUTE_IN_SECONDS);
    wp_safe_redirect(add_query_arg(['page' => 'portfolio-messages', 'testmail' => '1'], admin_url('admin.php')));
    exit;
});

// -> Nachricht löschen: nur Admins, nur mit gültigem Nonce für genau diese Nachricht
add_action('admin_post_portfolio_delete_message', function () {
    if (!current_user_can('manage_options')) {
        wp_die('Keine Berechtigung.', 403);
    }
    $id = absint($_POST['id'] ?? 0);
    check_admin_referer('portfolio_delete_message_' . $id);
    delete_contact_message($id);
    wp_safe_redirect(add_query_arg(['page' => 'portfolio-messages', 'deleted' => '1'], admin_url('admin.php')));
    exit;
});
