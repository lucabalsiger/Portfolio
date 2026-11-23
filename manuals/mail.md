# Kontaktformular und `Mailversand` einrichten
> In dieser Anleitung wird erklärt, wie das Kontaktformular aktiviert wird, wohin die Nachrichten gehen, wie der Mailversand ohne Plugin funktioniert und was bei Problemen zu tun ist.

## 📑 Überblick
- Das Formular im Modul «Kontakt» schickt die Daten per `fetch()` an `/wp-json/portfolio/v1/contact` ([backend/rest/_contact.php](/backend/rest/_contact.php)).
- Jede Nachricht wird in der eigenen Tabelle `portfolio_messages` gespeichert und erscheint im Backend unter **Nachrichten**.
- Zusätzlich wird sie per `wp_mail()` an die Adresse aus **Globales > E-Mail-Adresse Kontakt** geschickt.
- Der Mailversand wird in [backend/mail/index.php](/backend/mail/index.php) angepasst. Ein Plugin wie «WP Mail SMTP» ist nicht nötig.

## ⚒️ Schritt-für-Schritt-Anleitung
1. Im Backend bei der Seite mit dem Modul **Kontakt** die Checkbox **«Kontaktformular anzeigen»** anhaken und speichern.
2. Unter **Globales** bei **E-Mail-Adresse Kontakt** die Adresse eintragen, an die Nachrichten gehen sollen, z. B. `kontakt@lucabalsiger.ch`.
3. Unter **Einstellungen > Datenschutz** die Datenschutzseite auswählen. Der Hinweis im Formular verlinkt sie. In der Datenschutzerklärung muss das Kontaktformular beschrieben sein.
4. Im Backend unter **Nachrichten** auf **«Testmail senden»** klicken. Eine grüne Meldung heisst, die Mail wurde verschickt. Eine rote Meldung enthält die Fehlermeldung des Mailservers.
5. Im Postfach nachschauen, auch im Spam-Ordner.

## 📨 Wie der Versand funktioniert
**Stufe 1, immer aktiv:** Viele Domains verlangen mit einem DMARC-Eintrag (`p=reject`), dass Absender und Umschlag-Absender (Return-Path) zur Domain passen. PHP setzt als Umschlag-Absender sonst eine interne Adresse des Webservers, und die Mail wird ohne Meldung abgelehnt. Das Theme setzt deshalb den Umschlag-Absender auf die Absenderadresse (`wordpress@domain`). Bei Infomaniak reicht das, weil der Webserver laut SPF-Eintrag für die Domain senden darf.

**Stufe 2, nur bei Bedarf:** Kommen Mails trotzdem nicht an, kann der Versand über ein echtes Postfach per SMTP laufen. Dafür in der `wp-config.php` **auf dem Server** über der Zeile `/* That's all, stop editing! */` eintragen:
```php
define('PORTFOLIO_SMTP_HOST', 'mail.infomaniak.com');
define('PORTFOLIO_SMTP_USER', 'kontakt@domain.ch');
define('PORTFOLIO_SMTP_PASS', 'Gerätepasswort des Postfachs');
```
Optional `PORTFOLIO_SMTP_PORT` (Standard `465`) und `PORTFOLIO_SMTP_SECURE` (Standard `ssl`). Bei Infomaniak ist das Passwort ein **Gerätepasswort**, das beim Postfach unter «Mein Gerät konfigurieren» für ein neues Gerät (z. B. «WordPress») erzeugt wird. Die Zugangsdaten gehören nie ins Theme, weil es öffentlich auf GitHub liegt.

## 🔎 Fehlersuche
| Meldung oder Verhalten | Ursache | Lösung |
|---|---|---|
| Nachricht im Backend, aber keine Mail | Empfänger falsch oder Mail im Spam | Empfänger unter Globales prüfen (steht auch im Kasten auf der Seite «Nachrichten»), Spam-Ordner prüfen, «Testmail senden» |
| «SMTP-Fehler: Die Authentifizierung war nicht möglich» | Benutzer oder Passwort in der `wp-config.php` falsch | Gerätepasswort neu erzeugen und mit dem Kopier-Symbol übernehmen, oder die drei SMTP-Zeilen löschen und Stufe 1 verwenden |
| «du hast schon mehrere nachrichten geschickt» | Spam-Schutz: höchstens 3 Nachrichten pro Person und Stunde | eine Stunde warten, oder in phpMyAdmin `DELETE FROM [präfix]options WHERE option_name LIKE '%portfolio_contact%';` ausführen (Präfix mit `SHOW TABLES LIKE '%options';` herausfinden) |
| Erfolgsmeldung, aber nichts gespeichert | Formular in unter 3 Sekunden abgeschickt, Bot-Schutz | einige Sekunden warten, dann senden |
| «das formular ist abgelaufen» | Seite war länger als 24 Stunden offen oder stammt aus einem Cache | Seite neu laden, Caching-Plugins entfernen |
| Mails über das E-Mail-Signet fehlen im Backend | `mailto:` öffnet das Mailprogramm, WordPress bekommt nichts davon mit | gewollt, nur das Formular speichert Nachrichten |

## ✨ Umsetzung mit KI
Schicke der KI diese Anleitung und gib ihr mit:

- die **Domain** und den **Hoster**
- die **Fehlermeldung** aus «Testmail senden»
- die DNS-Einträge der Domain (`dig MX domain`, `dig TXT domain`, `dig TXT _dmarc.domain`), aber **nie** Passwörter oder die ganze `wp-config.php`

## 📚 Ressourcen
- [WordPress: phpmailer_init](https://developer.wordpress.org/reference/hooks/phpmailer_init/)
- [WordPress: wp_mail()](https://developer.wordpress.org/reference/functions/wp_mail/)
- [Infomaniak Support](https://www.infomaniak.com/de/support), Suchbegriff «SMTP» bzw. «E-Mail-Programm einrichten»
