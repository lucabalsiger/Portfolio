# Wordpress & Entwicklungsumgebung `aufsetzen`
> In dieser Anleitung wird schriftlich beschrieben, wie man Wordpress über Hostpoint installiert und sich eine professionelle Entwicklungsumgebung aufsetzt.

## 📽️ Video-Anleitung
[https://youtu.be/hf16bk_F_zs](https://youtu.be/hf16bk_F_zs)

## ⚒️ Schritt-für-Schritt-Anleitung
1. Logge dich in Hostpoint ein und bereite dir eine Domain oder Subdomain vor, die du für dein Projekt verwenden möchtest. Das kannst du in Wordpress im Tab `Domains` machen.
   1. **Subdomain**: Klicke auf eine schon bestehende Domain und füge dann mit `Subdomain konfigurieren` eine neue Subdomain hinzu.
   2. **Neue Domain**: Kaufe dir über den Shop die entsprechende Domain und füge sie deinem Hosting hinzu.
2. Gehe im Hostpoint auf die Server-Übersicht und klicke dort in der linken Leiste auf `Websites>One-Klick Apps`.
3. Wähle `Wordpress` aus und klicke auf `Installieren`.
4. Wähle deinen Domainnamen aus und klicke bei `Login-Daten` auf  `Eigene Login-Daten` und vergib diese. Die restlichen Einstellungen kannst du so belassen. Klicke am Schluss auf `Installieren`.
5. Warte nun wenn möglich mind. 15 Minuten, bis du weiterfährst. So kann die Installation komplett abgeschlossen werden.
6. Logge dich im Backend ein unter `deinedomain.ch/wp-login.php` und schaue, ob das funktioniert.
7. Öffne FileZilla und verbinde dich dort mit deinem Server (vermutlich hast du das schon mal gemacht). Gehe nun auf deiner neu erstellten Webseite zu folgendem Ordner: `deineurl.ch/wp-content/themes/`
8. Erstelle dort einen neuen Ordner mit dem Namen `portfolio`
9. Gehe auf github.com, logge dich ein und erstelle ein neues Repository. Das nennst du am besten `portfolio`.
10. Öffne nun die GitHub Desktop App auf deinem PC und gehe zu `File>Clone Repository`. Wähle dort im Reiter oben `Github.com` aus und wähle dein neu erstelltes Repository aus. Definiere unter `Local Path` wo dein Portfolio-Code auf deinem PC gespeichert werden soll.
11. Öffne diesen Ordner nun mit PHP-Storm (oder einer IDE deiner Wahl).
12. Erstelle eine `.gitignore` Datei mit folgendem Inhalt: 
    ```gitignore
    .idea
    ```
13. Erstelle eine `index.php` Datei mit folgendem Inhalt:
    ```php
    <h1>Portfolio von [Dein Name]</h1>
    ```
14. Erstelle eine `style.css` Datei mit folgendem Inhalt:
    ```css
    /**
    * Theme Name: Portfolio von [Dein Name]
    */
    ```
15. Gehe nun auf Hsostpoint und wähle in der linken Leiste `Advanced>FTP` aus. Klicke dort auf `FTP-Account hinzufügen`.
16. Vergib einen Usernamen und ein Passwort. Beim Pfad gibst du Folgendes ein: `www/deineurl.ch/wp-content/themes/portfolio`. Klicke auf `Erstellen`. Lasse dieses Browserfenster offen.
17. Wechsle nun zu PHP-Storm und öffne `Tools>Deployment>Configuration`. Klicke links oben auf `+` und wähle `FTP` aus. Gib einen Namen für die Verbindung ein und fülle die Felder `Host`, `Username` und `Password` aus. Klicke zuerst auf `Text Connection` und schaue, ob das funktioniert.
18. Wechsle oben nun in den Tab `Mappings` und gibt bei `Deployment path` einen `/` ein. Wenn du dann unten einen Fehler bekommst, klicke auf `Fix` und lösche in der erscheinenden Liste einfach den untersten Eintrag. Klicke abschliessend auf `Ok`.
19. Gehe nun in PHP-Storm zu `Tools>Deployment>Options` und wähle bei `Upload changed files automatically to default server` die Option `On explicit save action` aus. 
20. Wähle nun die zwei von dir erstellten Dateien `index.php` und `style.css` an und mache einen Rechtsklick. Fast zuunterst findest du einen Punkt der heisst `Upload to [Servername]`. Wähle das aus. 
21. Schau nun in FileZilla nach, ob die Dateien hochgeladen wurden.
22. Aktiviere im Wordpress-Backend dein neues Theme.

## 🚀 Fertige Seite von Local zu Infomaniak umziehen
> So wurde dieses Portfolio auf `lucabalsiger.ch` veröffentlicht. Der Weg eignet sich, wenn die Seite lokal in Local fertig gebaut ist und mit allen Inhalten (Seiten, Projekte, Bilder, eigene Tabellen) auf einen Server soll.

**Vorbereiten (lokal)**
1. Code committen und auf GitHub pushen.
2. In Local ein Backup machen: Rechtsklick auf die Seite > **Export**.
3. Im Dashboard-Widget «Projekte: Aufrufe und Applaus» die Statistik zurücksetzen und unter **Nachrichten** Testeinträge löschen.
4. Im lokalen Benutzerprofil eine **echte E-Mail-Adresse** eintragen. Local setzt `dev-email@wpengine.local`, damit funktioniert «Passwort vergessen» auf dem Server nicht.
5. Plugin **«All-in-One WP Migration»** installieren und unter **Exportieren > Datei** eine `.wpress`-Datei erstellen.

**Server einrichten (Infomaniak Manager, Web & Domains)**
1. Bei der Website **Anwendung hinzufügen > WordPress** wählen. Das Admin-Konto ist nur vorübergehend, der Import ersetzt es. Als Theme «Twenty Twenty-Four» wählen (wird danach gelöscht), keinen Page Builder.
2. **PHP-Version** auf 8.3 oder 8.4 stellen, **SSL-Zertifikat** (Let's Encrypt) für die Domain mit und ohne `www` aktivieren.
3. Während des Umzugs den **Wartungsmodus** aktivieren und unter «IP-Adressen ignoriert» die eigene IPv4- und IPv6-Adresse freigeben.

**Importieren (Backend auf dem Server)**
1. «All-in-One WP Migration» installieren, **Importieren > Datei** und die `.wpress`-Datei hochladen. Die Hinweise zur PHP-Version und zum Überschreiben bestätigen.
2. Danach gilt das **lokale Login**. Sofort ein starkes Passwort setzen.
3. **Einstellungen > Permalinks** zweimal speichern. Erst dann funktionieren `/projekt/…` und `/wp-json/…`.
4. Prüfen: `https://domain/wp-json/portfolio/v1/` muss JSON zeigen. Die eigenen Tabellen heissen auf dem Server mit dem Präfix von Infomaniak, z. B. `wp_1247494_portfolio_stats`. Das Theme verwendet automatisch das richtige Präfix.

**Aufräumen**
1. Nur **Carbon Fields** als Plugin behalten. «All-in-One WP Migration», «WP Super Cache» und «VOD Infomaniak» löschen. WP Super Cache vor dem Löschen **deaktivieren**, sonst bleibt `wp-content/advanced-cache.php` liegen und verursacht Fehlermeldungen.
2. Das Theme «Twenty Twenty-Four» löschen.
3. Mailversand einrichten und testen, siehe [mail.md](mail.md).
4. Datenschutzerklärung und Impressum auf der Live-Seite ergänzen. Inhalte ab jetzt nur noch auf der Live-Seite pflegen, lokale Änderungen an Inhalten gehen nicht automatisch auf den Server.
5. Die restlichen Punkte der [Go-Live-Checkliste](go-live-checkliste.md) abarbeiten, dann den Wartungsmodus beenden.

**Theme-Code später aktualisieren**
Geänderte Dateien über **WebFTP** im Infomaniak Manager (Schnellaktionen > «Sich bei WebFTP anmelden») oder mit FileZilla nach `sites/[domain]/wp-content/themes/portfolio/` hochladen. Neue Ordner zuerst anlegen und Dateien hochladen, erst danach `functions.php`, die sie lädt. Sonst zeigt die Seite kurz einen Fehler.
