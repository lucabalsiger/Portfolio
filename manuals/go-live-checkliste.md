# `Go Live` Checkliste

##  Allgemeine Checkliste
> **Legende**
> - 🔴 must
> - 🟠 should
> - 🟢 could

### SEO
- [ ] 🟢 Ich habe [Yoast SEO](#yoast-seo) installiert, aktiviert und konfiguriert. Optional: Das Theme gibt Beschreibung, Open Graph und strukturierte Daten selbst aus (`backend/seo/`) und schaltet diese Ausgabe ab, sobald Yoast aktiv ist.
- [ ] 🔴 Ich erlaube in den Einstellungen das Crawlen der Webseite (Einstellungen > Lesen).
- [ ] 🔴 Ich habe eine korrekte robots.txt Datei. 
- [ ] 🟠 Ich habe eine korrekte sitemap.xml Datei. Ohne Yoast erzeugt WordPress sie selbst unter `/wp-sitemap.xml`.
- [ ] 🟢 Ich habe eine korrekte llms.txt Datei.

### Security
- [ ] 🔴 Die Webseite hat HTTPS aktiviert.
- [ ] 🔴 Ich habe [All in One WP Security](#all-in-one-wp-security) installiert und konfiguriert.
- [ ] 🟠 Ich habe alle Plugins, die ich nicht brauche, gelöscht.
- [ ] 🟠 Ich habe alle Themes, die ich nicht brauche, gelöscht.
- [ ] 🔴 Ich habe nur Nutzer:innen in meinem Backend, welche wirklich auch Zugang dazu brauchen. 
- [ ] 🟠 Ich habe [Updraft-Plus](#updraft-plus) installiert und konfiguriert.

### Performance
- [ ] 🟠 Ich habe mit Google Lighthouse einen Performance-Check durchgeführt und wo nötig Optimierungen vorgenommen.

### Rechtliches
- [ ] 🔴 Die Webseite hat ein korrektes Impressum. [Mehr zur Impressumspflicht](https://www.hostpoint.ch/blog/impressumspflicht-f%C3%BCr-websites/)
- [ ] 🔴 Die Webseite hat eine korrekte Datenschutzpage, welche in den Einstellungen verlinkt ist. [Mehr zur Datenschutzerklärung](https://www.edoeb.admin.ch/de/datenschutzerklaerungen-im-internet)
- [ ] 🔴 Die Webseite hat, falls nötig, einen Cookie-Banner installiert. [Mehr zu Cookie-Bannern](https://www.hostpoint.ch/blog/das-neue-dsg-und-der-cookie-banner-wirrwarr-was-gilt-denn-nun-eigentlich/)

### Allgemeines
- [ ] 🟢 Das Projekt liegt auf GitHub.
- [ ] 🟠 Das Projekt hat ein Favicon.
- [ ] 🟠 Auto-Updates für Plugins sind aktiviert oder ein Update-Zyklus ist definiert und geplant.

### Final Touches
- [ ] 🔴 Basic-Auth ist entfernt.
- [ ] 🟢 Ich habe den [Uptime-Robot](#uptime-robot) installiert und meine Webseite hinzugefügt.

## Checkliste für dieses Theme
Punkte, die nur dieses Theme betreffen: eigene Datenbank-Tabellen, REST-Endpunkte, Kontaktformular, GitHub-Modul und SEO-Ausgabe.

### Server und Umzug
- [ ] 🔴 Der Hoster bietet PHP 8.0 oder neuer und WordPress 6.5 oder neuer.
- [ ] 🔴 Das Plugin Carbon Fields ist installiert und aktiviert.
- [ ] 🔴 Datenbank und Uploads sind umgezogen (z. B. mit «WP Migrate» oder «All-in-One WP Migration»), mit Suchen und Ersetzen von `portfolio.local` auf die neue Domain. Die eigenen Tabellen `wp_portfolio_stats` und `wp_portfolio_messages` kommen mit, solange sie dasselbe Präfix wie die übrigen Tabellen haben.
- [ ] 🔴 Ich habe einmal Einstellungen > Permalinks geöffnet und gespeichert, damit `/projekt/...` und `/wp-json/...` funktionieren.
- [ ] 🔴 Unter `https://meinedomain.ch/wp-json/portfolio/v1/` antwortet die REST-API mit JSON und nicht mit einem Fehler.
- [ ] 🟠 Unter Einstellungen > Allgemein ist die Sprache «Deutsch (Schweiz)» gesetzt (im Quelltext steht `<html lang="de-CH">`).

### Daten aufräumen
- [ ] 🔴 Ich habe auf dem Dashboard im Widget «Projekte: Aufrufe und Applaus» die Statistik zurückgesetzt, damit keine Testzahlen von lokal online stehen.
- [ ] 🟠 Unter **Nachrichten** sind alle Testnachrichten gelöscht.

### Kontaktformular und Mail
- [ ] 🔴 Ich habe das Kontaktformular auf der Live-Seite ausgefüllt (nach mindestens 3 Sekunden) und die Mail ist an der Adresse aus «Globales» angekommen.
- [ ] 🟠 Falls keine Mail ankommt: In der `wp-config.php` auf dem Server die SMTP-Zugangsdaten eines Postfachs der eigenen Domain eintragen (`PORTFOLIO_SMTP_HOST`, `PORTFOLIO_SMTP_USER`, `PORTFOLIO_SMTP_PASS`, siehe `backend/mail/index.php`). Ein Plugin ist nicht nötig.
- [ ] 🟠 Ein Caching-Plugin speichert Seiten höchstens 24 Stunden. Sonst ist der Zeitstempel im Formular abgelaufen und Besucher:innen sehen «das formular ist abgelaufen». Alternativ die Kontaktseite vom Cache ausnehmen.

### Rechtliches für dieses Theme
- [ ] 🔴 Die Datenschutzerklärung beschreibt das **Kontaktformular**: Name, E-Mail und Nachricht werden in der Datenbank gespeichert und per E-Mail weitergeleitet, nur um die Anfrage zu beantworten, und bleiben gespeichert, bis ich sie lösche.
- [ ] 🔴 Die Datenschutzerklärung beschreibt **Aufrufe und Applaus**: Gezählt wird anonym, ohne Cookies. Zum Schutz vor Missbrauch wird die IP-Adresse höchstens 24 Stunden als nicht umkehrbarer Hash gespeichert.
- [ ] 🟠 Die Datenschutzerklärung erwähnt den **lokalen Speicher im Browser** (`localStorage`): gewählter Dark Mode und die Projekte mit Applaus. Diese Angaben verlassen den Browser nicht.
- [ ] 🟠 Die Datenschutzerklärung hält fest, dass Schriften lokal geladen werden und die GitHub-Repos über den Server abgerufen werden, also keine Daten der Besucher:innen an Google oder GitHub gehen.
- [ ] 🔴 Die Datenschutzseite ist unter Einstellungen > Datenschutz ausgewählt. Der Hinweis im Kontaktformular verlinkt genau diese Seite.

### Inhalte und Einstellungen
- [ ] 🟠 Unter «Globales» sind E-Mail-Adresse, GitHub-Benutzername, Standard-Beschreibung und Vorschaubild ausgefüllt.
- [ ] 🟠 Alle verwendeten Bilder haben einen Alternativtext (Medien > Mediathek).
- [ ] 🟠 Das Plugin «Safe SVG» ist installiert, falls SVG-Dateien hochgeladen werden.

### Nach dem Go-Live prüfen
- [ ] 🟠 Lighthouse mit der Live-URL ausgeführt und die Tabelle «Prüfung» im README aktualisiert.
- [ ] 🟢 Die Vorschau beim Teilen geprüft, z. B. mit dem [LinkedIn Post Inspector](https://www.linkedin.com/post-inspector/).
- [ ] 🟢 Die strukturierten Daten mit dem [Rich Results Test von Google](https://search.google.com/test/rich-results) geprüft.
- [ ] 🟢 Applaus auf einer Projektseite in einem privaten Fenster getestet, danach die Statistik nochmals zurückgesetzt.

## Checkliste pro Tools

### Yoast SEO
> [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/)

- [ ] Installieren (Suchbegriff: Yoast SEO)
- [ ] Yoast AI deaktivieren (Einstellungen)
- [ ] Website-Grundlagen ausfüllen (v.a. Bild)
- [ ] Umarbeitung der Titel erzwingen > aktivieren
- [ ] Zeige Projektkategorien in den Suchergebnissen > deaktivieren, weil keine Archiv-Pages
- [ ] autoren-Archive, Datums-Archive & Format-Archive deaktivieren
- [ ] Seiten: SEO-Titel & Beschreibung
- [ ] Projekte: SEO-Titel & Beschreibung
- [ ] robots.txt checken
- [ ] sitemap.xml checken
- [ ] llms.txt aktivieren & checken

### All in One WP Security
> [All-In-One Security (AIOS)](https://wordpress.org/plugins/all-in-one-wp-security-and-firewall/)

- [ ] Plugin Installieren (Suchbegriff: All-In-One Security)
- [ ] Erst-Konfiguration überspringen
- [ ] Settings > .htaccess file > `Backup .htaccess file`
- [ ] Settings > wp-config.php file > `Backup wp-config.php file`
- [ ] Settings > WP version info > **Aktivieren**
- [ ] User Security > User Accounts > **User `admin` umbenennen wenn der existiert**
- [ ] User Security > User Accounts > **Display-Name soll nicht Login-Name sein**
- [ ] User Security > User Accounts > Prevent User enumeration > **Aktivieren**
- [ ] User Security > User Accounts > Enforce strong password > **Aktivieren**
- [ ] User Security > Login Lockout > **Aktivieren**
- [ ] User Security > Force Logout > **Aktivieren**
- [ ] Database-Security > Database Prefix > **Change database prefix**. Danach prüfen, ob auch `portfolio_stats` und `portfolio_messages` das neue Präfix haben (z. B. in phpMyAdmin). Falls nicht, beide Tabellen von Hand umbenennen. Die Option `portfolio_db_version` nur löschen, wenn die Tabellen leer sein dürfen: Dann legt das Theme sie neu und leer an.
- [ ] File Security > File Permissions > **WP-Config Permission setzen**
- [ ] File Security > File Protections > Delete WP default files > **Aktivieren**
- [ ] File Security > File Protections > Prevent hotlinking > **Aktivieren**
- [ ] File Security > File Protections > Disable PHP file editing > **Aktivieren**
- [ ] Firewall > PHP Rules > Security enhancements > **Aktivieren**
- [ ] Firewall > PHP Rules > Comment Protection > **Aktivieren**
- [ ] Firewall > PHP Rules > URL security > **Aktivieren**
- [ ] Firewall > PHP Rules > String filtering > **Aktivieren**
- [ ] Firewall > PHP Rules > nG firewall rules > **Aktivieren**
- [ ] Firewall > PHP Rules > Internet Bot Settings > **Aktivieren**
- [ ] Firewall > .htaccess rules > Basic Firewall settings > **Aktivieren**
- [ ] Firewall > .htaccess rules > Block debug log > **Aktivieren**
- [ ] Firewall > .htaccess rules > Listing directory content > **Aktivieren**
- [ ] Brute Force > Rename Login Page > **Aktivieren und neuer Pfad notieren**
- [ ] Brute Force > Honey Pot > **Beide aktivieren**
- [ ] 🚨 Die REST-API **nicht** für nicht angemeldete Besucher:innen sperren (Option «Disallow unauthorized REST requests» o. ä.). Applaus, Aufrufe und Kontaktformular laufen über `/wp-json/portfolio/v1/`. Falls die Option nötig ist, `portfolio/v1` als Ausnahme eintragen.

### Updraft-Plus
> [Updraft-Plus](https://wordpress.org/plugins/updraftplus/)

- [ ] Installieren (Suchbegriff: UpdraftPlus)
- [ ] Settings > Definieren, wie oft gebackupt werden soll
- [ ] FTP als `Storage Location` auswählen
- [ ] Folder für die Backups mit FileZilla erstellen
- [ ] Angaben zum FTP-Account machen
- [ ] Alle Einstellungen so lassen
- [ ] Backup Now durchführen
- [ ] Mit FileZilla den Server prüfen

### Uptime Robot
> [Uptime Robot](https://uptimerobot.com/)

- [ ] Account über GitHub erstellen
- [ ] Monitoring für die Webseite einrichten (nachdem das Basic Auth entfernt wurde)

### Kansleri Cookie Consent
> [Kansleri Cookie Consent](https://wordpress.org/plugins/kansleri-cookie-consent/)

🚨 Nur installieren, wenn wirklich nötig.

- [ ] Installieren (Suchbegriff: Kansleri Cookie Consent)
- [ ] Einstellungen > Cookie Consent
- [ ] Style selber definieren
- [ ] Texte übersetzen
- [ ] Empty Cookie Categories > **Hide... auswählen**
- [ ] Floating settings button > **Deaktivieren**
- [ ] Damit der Shortcode in einem Text-Modul funktioniert: In `backend/helpers/index.php` in der Funktion `the_rich_text()` den Aufruf `wpautop($text)` durch `do_shortcode(wpautop($text))` ersetzen. `wp_kses_post()` bleibt aussen herum, damit weiterhin nur erlaubtes HTML ausgegeben wird.
- [ ] Shortcode auf der Datenschutz-Page einfügen
- [ ] Cookies > **Scann cookies**
- [ ] Cookies **Save cookies**
- [ ] Policy Page > **Datenschutz-Seite verlinken**
