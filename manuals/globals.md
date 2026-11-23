# Die `Globals` erweitern
> In dieser Anleitung wird erklärt, wie man neue Inhalte in den globalen Webseiten-Einstellungen erfassbar macht und in einem Template nutzen kann.

## 📽️ Video-Anleitung
[https://youtu.be/viFmiJODZIU](https://youtu.be/viFmiJODZIU)

## 📑 Beispieldaten
In dieser Anleitung wird beispielhaft ein globales Inhaltsfeld mit folgenden Angaben hinzugefügt:

- **Feld-Name**: Email-Adresse Kontakt
- **Feld-Slug**: email
- **Feld-Typ**: text

## ⚒️ Schritt-für-Schritt-Anleitung
1. Die Datei [backend/carbonfields/_globals.php](/backend/carbonfields/_globals.php) öffnen.
2. Das gewünschte Feld mit der Carbon-Fields Make-Schreibweise ergänzen.
   ```php
   Field::make('text', 'email', 'Email-Adresse Kontakt'),
   ```
   Die verschiedenen Feldtypen, die benutzt werden können, sind in der [Dokumentation](https://docs.carbonfields.net/) zu finden.
3. Nun kann das neue Feld im Backend befüllt werden.
4. Um die Daten aus dem Backend in ein View-Template laden zu können, wird in der Datei [/backend/getter/_globals.php](/backend/getter/_globals.php) ein Getter geschrieben. Dieser soll ca. so aussehen:
   ```php
   // -> E-Mail-Adresse, false wenn keine gültige erfasst ist
   function get_globals_email(): string|false {
       $email = sanitize_email(carbon_get_theme_option('email') ?: '');
       if (!$email || !is_email($email)) {
           return false;
       }
       return $email;
   }
   ```
   Diese Getter-Funktion soll grundsätzlich einfach mit `carbon_get_theme_option([Feld-Slug])` die globalen Daten laden, diese gegebenenfalls prüfen und verarbeiten und ein Fallback zurückgeben, wenn im Backend nichts erfasst ist. Der Rückgabetyp muss zum Inhalt passen: `string|false` für Text, `array|false` für Bilder aus `get_media_by_id()`.
5. Diese Getter-Funktion kann nun im View-Template, in dem die globale Information angezeigt werden soll, verwendet werden, um die globalen Informationen zu laden.
   ```php
   $email = get_globals_email();
   ?>
   <?php if ($email): ?>
       <a href="mailto:<?= esc_attr(antispambot($email)) ?>"><?= esc_html(antispambot($email)) ?></a>
   <?php endif; ?>
   ```
   `antispambot()` verschleiert die Adresse für Crawler, `esc_html()` und `esc_attr()` sorgen für eine sichere Ausgabe.
6. Ergänze das neue Feld im [README](/README.md) in der Tabelle «Globale Einstellungen».

## ✨ Umsetzung mit KI
Schicke der KI diese Anleitung und gebe ihr folgende Angaben mit:

- **Feld-Name** der globalen Information
- **Feld-Slug** der globalen Information
- **Feld-Typ** der globalen Information
- Pfad zum **View-Template**, wo die Information angezeigt/verwendet werden soll


## 📚 Ressourcen
- [Carbon Fields Theme Options](https://docs.carbonfields.net/learn/containers/theme-options.html)
- [Carbon Fields Dokumentation](https://docs.carbonfields.net/)
