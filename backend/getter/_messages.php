<?php

// -> Anzahl aller gespeicherten Nachrichten aus dem Kontaktformular
function count_contact_messages(): int {
    global $wpdb;
    return (int) $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM %i', get_messages_table_name()));
}

// -> Nachrichten seitenweise, die neuste zuerst
function get_contact_messages(int $page = 1, int $per_page = 20): array {
    global $wpdb;
    $rows = $wpdb->get_results($wpdb->prepare(
        'SELECT id, name, email, message, created_at FROM %i ORDER BY created_at DESC, id DESC LIMIT %d OFFSET %d',
        get_messages_table_name(), $per_page, ($page - 1) * $per_page
    ), ARRAY_A) ?: [];
    return array_map(fn($row) => [
        'id' => (int) $row['id'],
        'name' => $row['name'],
        'email' => $row['email'],
        'message' => $row['message'],
        // -> gespeichert in UTC, angezeigt in der Zeitzone aus Einstellungen > Allgemein
        'date' => get_date_from_gmt($row['created_at'], 'd.m.Y, H:i'),
    ], $rows);
}
