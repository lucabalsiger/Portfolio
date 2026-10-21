# Portfolio von Luca Balsiger
> Persönliches WordPress-Theme. Entwickelt im Modul IM5, HS26, FH Graubünden, auf Basis des IM5-Starter-Themes.

Das Portfolio zeigt ausgewählte Projekte von Luca Balsiger in einem eigenen WordPress-Theme ohne Page Builder und ohne CSS-Framework. Seiten werden im Backend aus Modulen zusammengesetzt, Projekte über einen eigenen Post Type gepflegt. Prägend sind die typografischen Intro-Zeilen auf der Startseite, die sich automatisch an die Breite anpassen, sowie ein Dark Mode und lokal eingebundene Schriften ohne Anfragen an Dritte.

<!-- ANPASSEN: Live-URL eintragen, z. B. https://deinedomain.ch -->
Live: _URL folgt_

---

## Live-Version

<!-- ANPASSEN: Link zur veröffentlichten Webseite eintragen. -->
- Webseite: _Link folgt_

---

## Tech-Stack

- **WordPress** mit eigenem Theme (klassisches PHP-Theme, kein Block-Theme)
- **PHP 8.0+** (Union Types, `match`, `str_contains`)
- **Carbon Fields** für alle Inhaltsfelder, eingebunden als **WordPress-Plugin** (`wp-content/plugins/carbon-fields`), nicht über Composer. Das Theme lädt Carbon Fields nicht selbst, sondern registriert seine Felder über den Hook `carbon_fields_register_fields`.
- **HTML** und **CSS** ohne Framework, mit CSS-Variablen und verschachtelten Selektoren
- **Vanilla JavaScript** als ES-Modul (`type="module"`), ohne Bibliotheken und ohne Build-Schritt
- **Lokale Webfonts** im Format woff2: Archivo, Space Grotesk, Big Shoulders Display, Barlow Condensed

---

## Voraussetzungen und Installation

**Voraussetzungen**
- WordPress 6.5 oder neuer (entwickelt mit WordPress 7.1). Ältere Versionen funktionieren dank Fallback beim Laden von `main.js` ebenfalls.
- PHP 8.0 oder neuer
- Plugin **Carbon Fields**, installiert und aktiviert
- Empfohlen: Plugin **«Safe SVG»**, das hochgeladene SVG-Dateien bereinigt

**Installation**
1. Den Theme-Ordner `portfolio` nach `wp-content/themes/` legen.
2. Im Backend unter **Design > Themes** das Theme «Portfolio von Luca Balsiger» aktivieren.
3. Unter **Design > Menüs** zwei Menüs anlegen und den Positionen «Hauptnavigation» und «Rechtliches» zuweisen.
4. Im Menüpunkt **Globales** Logo, E-Mail-Adresse, Footer-Text und Socials ausfüllen.
5. Unter **Einstellungen > Lesen** eine statische Seite als Startseite festlegen.
6. Einmal **Einstellungen > Permalinks** öffnen und speichern, damit die URLs der Projekte (`/projekt/...`) aktiv sind.

Eine ausführliche Anleitung für Hosting und Entwicklungsumgebung steht in [`manuals/installation.md`](manuals/installation.md).

---

## Aufbau des Themes

### 📄 Templates im Hauptordner
WordPress wählt das passende Template anhand der Template-Hierarchie. Alle Templates haben den gleichen Aufbau: Header, Layout-Header, `<main>`, Layout-Footer, Footer. Der eigentliche Inhalt kommt aus `frontend/views/pages/`.

| Datei | Was drin ist |
|---|---|
| `page.php` | Alle Seiten (Pages), lädt die Module |
| `single-project.php` | Projektdetailseite |
| `404.php` | Seite nicht gefunden, mit Link zur Startseite |
| `index.php` | Fallback, wenn kein anderes Template passt |
| `header.php` / `footer.php` | HTML-Gerüst mit `wp_head()` und `wp_footer()` |

### 🔧 Ordner `backend`
Alle Dateien für Funktionalität und CMS-Konfiguration. Hier wird gearbeitet, wenn neue Felder, Post Types oder Logik gebraucht wird.

| Ordner | Was drin ist |
|---|---|
| `carbonfields/` | CMS-Felder für Seiten, Projekte und globale Einstellungen (Carbon Fields) |
| `cpt/` | Custom Post Type «Projekte» |
| `enqueue/` | Einbinden von `main.css` und `main.js` über WordPress, mit Cache-Busting |
| `getter/` | Funktionen, die Daten aus der Datenbank holen und für Templates aufbereiten |
| `helpers/` | Allgemeine Hilfsfunktionen, die überall genutzt werden können |
| `init/` | WordPress-Basis-Setup: Bildgrössen, Seitentitel, Bereinigung, SVG-Upload (nur Admins) |
| `nav/` | Navigationsmenüs registrieren |
| `tax/` | Taxonomie «Projektkategorien» |

### 🏠 Ordner `frontend`
Alle Dateien für die Darstellung der Webseite.

| Ordner | Was drin ist |
|---|---|
| `assets/fonts/` | Lokale Schriftdateien (Archivo, Space Grotesk, Big Shoulders Display, Barlow Condensed) und Lizenz `OFL.txt` |
| `scripts/main.js` | Alle JavaScript-Funktionen |
| `styles/main.css` | Alle Stile des Themes |
| `views/layout/` | Header und Footer der Webseite |
| `views/modules/` | Ein PHP-Template pro Modul |
| `views/pages/` | Templates für Seiten-, Projekt- und 404-Ansicht |
| `views/partials/` | Wiederverwendbare Teilkomponenten (Badge, Galerie) |
| `views/teaser/` | Kachelansicht für Projekte in der Übersicht |

### 📚 Ordner `manuals`
Anleitungen für häufige Erweiterungen und Einrichtungsschritte. Die Anleitungen sind so geschrieben, dass auch eine KI sie lesen und bei der Umsetzung unterstützen kann.

---

## Module

Module sind die Bausteine einer Seite. Im Backend unter «Module» hinzufügen und konfigurieren.

### Hero Startseite (`hero_front`)
Der Einstiegsbereich der Startseite. Links: animierte Schriftzüge. Rechts: Portrait-Foto oder rotierendes Signet.

**Felder im Backend:**

| Feld | Beschreibung |
|---|---|
| Titel | Grosser Seitentitel oben |
| Intro Zeilen | Gestaltbare Schriftzüge — pro Zeile: Text, Farbe, Füllstil, Strichstärke, Abstand, Schriftgrösse, Breite/Stretch, Kursiv, Schrift |
| Portrait-Foto anzeigen | Checkbox: an = Foto, aus = Signet vergrössert |
| Bild | Portrait-Foto |
| Text im Signet | Umlaufender Text im roten Kreis |

**Füllstile der Intro-Zeilen:**
- `Gefüllt`: Fläche, hover wechselt auf Kontur
- `Outline`: nur Kontur, hover füllt auf
- `Grain`: körnige Textur, hover blendet Korn aus

### Hero Standard (`hero_default`)
Einfacher Hero für Unterseiten: Titel und optionaler Untertitel.

### Text (`text`)
Zweispaltiger Text-Bereich: optionaler Titel links, Fliesstext rechts.

### Text mit Bild (`text_image`)
Wie Text, aber mit Bild. Bildposition (links oder rechts) einstellbar.

### Übersicht Projekte (`overview_projects`)
Zeigt alle oder ausgewählte Projekte als Kacheln an. Ohne Auswahl erscheinen alle Projekte in ihrer Reihenfolge.

### Bilder (`images`)
Einfache Bildergalerie.

### Statement (`statement`)
Grosse, auffällige Textzeile für kurze Aussagen.

### Kontakt (`contact`)
Kontaktbereich mit Titel, Text, optionalem Bild und E-Mail-Signet.

### Rechtstext (`legal_text`)
Für Impressum und Datenschutzerklärung.

---

## Globale Einstellungen

Im Backend unter dem Menüpunkt **Globales** (Carbon Fields):

| Feld | Beschreibung |
|---|---|
| Logo / Wortmarke | Optional. Ohne Logo wird der Seitenname als Text angezeigt |
| E-Mail-Adresse | Für das Kontaktmodul |
| Footer-Text | z. B. «Luca Balsiger / 2026» |
| Socials | Mehrere Einträge mit Titel, Link und optionalem Icon |

---

## Projekte

Eigener Post Type unter **Projekte** im Backend. Felder pro Projekt:

| Feld | Beschreibung |
|---|---|
| Herobild | Wird im Hero und als Teaser in der Übersicht verwendet |
| Jahr | z. B. «2026» |
| Untertitel | z. B. «Fotografie · Adobe Photoshop · Modul IM5» |
| Beschrieb | Fliesstext mit Titel |
| Galerie | Bildergalerie (drag-and-drop sortierbar) |
| Link | Externer Link mit eigenem Linktext |

Reihenfolge der Projekte über das Feld **Reihenfolge** (Seitenattribute) steuern.

Projektkategorien werden im Backend gepflegt und in den Teasern als Tags angezeigt. Eigene Archivseiten für Kategorien gibt es bewusst nicht.

---

## Entwicklungshinweise

### Schriften
Alle Schriften sind lokal eingebunden (`frontend/assets/fonts/`, `@font-face` in `frontend/styles/_fonts.css`), es gehen keine Anfragen an Google oder andere Dritte. Archivo ist die Hauptschrift. Space Grotesk, Big Shoulders Display und Barlow Condensed stehen für die Intro-Zeilen zur Auswahl. Eingebunden sind nur die Schnitte, die tatsächlich verwendet werden (Subset latin): Space Grotesk 700, Big Shoulders Display 900, Barlow Condensed 800 normal und kursiv. Die `@font-face`-Regeln laden eine Datei erst, wenn eine Intro-Zeile diese Schrift auf der Seite nutzt. Alle Schriften stehen unter der SIL Open Font License, die Copyright-Angaben stehen in `OFL.txt`.

### Styles und Scripts
`main.css` und `main.js` werden in `backend/enqueue/index.php` über `wp_enqueue_style()` und `wp_enqueue_script_module()` eingebunden. Als Version dient `filemtime()`, damit Browser nach einer Änderung die neue Datei laden. `main.js` ist ein ES-Modul und wird im Footer geladen.

### Dark Mode
Über den runden Button im Header umschaltbar. Gewählter Modus wird im `localStorage` gespeichert, Systemeinstellung (`prefers-color-scheme`) wird als Standard übernommen. Ein kleines Inline-Script direkt nach `<meta charset>` in `header.php` setzt `data-theme` auf `<html>`, bevor die Seite gezeichnet wird. So blitzt beim Laden nicht kurz der helle Modus auf. `main.js` kümmert sich nur noch um das Umschalten und setzt am Button `aria-pressed` (`true` im Dark Mode).

### Reduzierte Bewegung
Bei `prefers-reduced-motion: reduce` stoppt das CSS die Dauer-Animationen und das Scroll Reveal. In `main.js` werden zusätzlich der Text-Scramble auf dem Markennamen und die magnetischen Navigationslinks nicht ausgeführt.

### SVG-Grain-Effekt
Der Grain-Effekt auf Intro-Zeilen wird via SVG-Filter (`feTurbulence`) erzeugt. Der Filter ist als unsichtbares `<svg>` im HTML eingebettet. Der Hover-Übergang läuft über ein `::after`-Pseudo-Element statt am Text selbst, weil `filter: url()` in CSS nicht animierbar ist.

### Auto-Skalierung der Intro-Zeilen
JavaScript berechnet die Schriftgrösse jeder Intro-Zeile so, dass sie genau die Container-Breite füllt. Die CSS-Variablen `--size-factor` und `--stretch-x` steuern Grösse und Breite. Auf Mobile überspringt JS diese Berechnung; `clamp()` übernimmt.

### Scroll Reveal
Module erscheinen beim Scrollen animiert (fade + translateY). Nur aktiv wenn JavaScript geladen ist (Klasse `.js` auf `<body>`).

### Benutzerdefinierter Cursor
Auf Desktop-Geräten mit Maus: roter Punkt mit leichtem Lag. Wächst über Links und Buttons.

### Bilder
`get_media_by_id()` liefert neben `src` und `alt` auch `width` und `height`. Teaser und Galerie geben diese Attribute aus, damit der Browser den Platz reserviert und das Layout beim Laden nicht springt.

### Responsives Layout
- Desktop: 12-Spalten-Grid
- Tablet/Mobile (≤ 768 px): einspaltiges Layout
- Footer: zusätzliche Breakpoints bei 1024 px und 640 px

### Sicherheit
- **Escaping:** Alle Ausgaben in den Templates laufen durch `esc_html()`, `esc_url()` oder `esc_attr()`. Rich-Text wird mit `wp_kses_post()` gefiltert (Hilfsfunktion `the_rich_text()`).
- **E-Mail:** Die Adresse im Kontaktmodul wird mit `antispambot()` verschleiert, damit Crawler sie nicht einfach auslesen.
- **Externe Links:** öffnen in einem neuen Tab mit `rel="noopener noreferrer"` (Hilfsfunktion `external_link_attrs()`).
- **SVG-Upload:** nur für Benutzer:innen mit dem Recht `manage_options` (Administrator:innen), weil SVG-Dateien JavaScript enthalten können. Empfohlen ist zusätzlich das Plugin «Safe SVG», das SVG-Dateien beim Upload bereinigt.
- **Backend:** Theme-Editor, Website-Editor und Schriftbibliothek sind aus dem Menü entfernt und auch über die direkte URL gesperrt.

---

## Theme anpassen

Für häufige Erweiterungen gibt es Schritt-für-Schritt-Anleitungen im Ordner `manuals/`:

| Aufgabe | Anleitung |
|---|---|
| Neues Modul erstellen | [`manuals/module.md`](manuals/module.md) |
| Neuer Custom Post Type | [`manuals/cpt.md`](manuals/cpt.md) |
| Neue Taxonomie | [`manuals/taxonomy.md`](manuals/taxonomy.md) |
| Menüs registrieren und ausgeben | [`manuals/menu.md`](manuals/menu.md) |
| Globale Felder ergänzen | [`manuals/globals.md`](manuals/globals.md) |
| Webseite veröffentlichen | [`manuals/go-live-checkliste.md`](manuals/go-live-checkliste.md) |

**Farben, Abstände und Schriften zentral anpassen:** Alle Grundwerte stehen als CSS-Variablen im `:root`-Block am Anfang von `frontend/styles/main.css`.

- Farben: `--color-paper` (Hintergrund), `--color-ink` (Text), `--color-muted`, `--color-line`, `--color-accent` (Akzentrot)
- Abstände und Breiten: `--gap-around` (Seitenrand), `--gap-section` (Abstand zwischen Modulen), `--width-text` (maximale Textbreite)
- Schriften: `--font-family`, `--font-display`, `--font-h1`, `--font-h2`, `--font-h3`, `--font-lead`, `--font-body`, `--font-small`

Die Farben für den Dark Mode werden im Block `[data-theme="dark"]` direkt darunter überschrieben.

---

## Learnings

<!-- PRÜFEN: Entwurf. Lies jeden Punkt durch und formuliere ihn so um, dass er deine eigene Erfahrung wiedergibt. Streiche, was nicht stimmt. -->

- **Template-Hierarchie:** WordPress wählt das Template anhand fester Regeln. Für eine Seite nimmt es `page.php`, für ein Projekt `single-project.php`, für eine nicht gefundene Seite `404.php`. Erst wenn nichts passt, greift `index.php`. Fehlt ein Template, landet man auf einer leeren Seite, wie es bei der 404-Seite anfangs der Fall war.
- **Daten und Darstellung trennen:** Die Getter in `backend/getter/` holen die Daten aus Carbon Fields und bereiten sie auf. Die Templates bekommen über `get_template_part()` und `$args` nur noch fertige Werte und bleiben dadurch kurz und lesbar. Änderungen an Feldern betreffen so meist nur eine Stelle.
- **Modulbaukasten:** Mit einem Complex Field und `render_modules()` lassen sich Seiten im Backend frei aus Bausteinen zusammensetzen, ohne Page Builder. Ein neues Modul braucht nur eine Feldkonfiguration und ein Template.
- **Post Types und Taxonomien:** `public`, `publicly_queryable` und `rewrite` steuern, ob und unter welcher URL Inhalte im Frontend erreichbar sind. Lässt man `rewrite` weg, gilt der Standard `true` und WordPress erzeugt trotzdem URLs.
- **Sicherheit:** Jede Ausgabe wird passend escaped (`esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`). SVG-Dateien können JavaScript enthalten und dürfen deshalb nur von Admins hochgeladen werden.
- **CSS ohne Framework:** Mit Custom Properties lassen sich Farben, Abstände und der Dark Mode zentral steuern. Bei den Mobile-Regeln habe ich gemerkt, wie wichtig die Spezifität von Selektoren ist.

<!-- PRÜFEN: Ergänze hier, was du beim nächsten Projekt anders machen würdest. Das kann nur von dir kommen. -->

---

## Schwierigkeiten und Lösungen

<!--
ANPASSEN: Beschreibe 3 bis 5 Probleme, die dich wirklich beschäftigt haben. Format pro Punkt:
**Problem:** Was hat nicht funktioniert oder wie hat es sich gezeigt?
**Ursache:** Warum war das so?
**Lösung:** Wie hast du es gelöst, und was hast du dabei gelernt?

Denkanstösse aus diesem Theme:
- Auto-Skalierung der Intro-Zeilen: Jede Zeile soll exakt die Breite füllen. Messung über scrollWidth bei 100px, Umrechnung mit --size-factor und --stretch-x, Warten auf document.fonts.ready, auf Mobile clamp() statt JS.
- SVG-Grain-Filter: filter: url(#intro-grain) lässt sich nicht animieren. Lösung über ein ::after-Overlay mit content: attr(data-text), das nur opacity animiert.
- Lightbox mit <dialog>: Der eigene Cursor verschwand hinter dem Dialog, weil showModal() den Dialog in den Top-Layer legt. Lösung: Cursor beim Öffnen in den Dialog verschieben und beim Schliessen zurück.
- Dark Mode ohne Aufblitzen: Theme wurde erst in main.js gesetzt, daher kurz heller Modus. Lösung: Inline-Script im <head>.
- Doppeltes <main>: page.php und die Views gaben beide ein <main> aus. Lösung: View mit <div data-page>, CSS nutzt nur [data-page].
- Mobile-Layout: Desktop-Grid-Platzierungen überschreiben (Spezifität [data-module][data-module][data-module]).
-->

- **Problem:** 
  **Ursache:** 
  **Lösung:** 
- **Problem:** 
  **Ursache:** 
  **Lösung:** 
- **Problem:** 
  **Ursache:** 
  **Lösung:** 

---

## Einsatz von KI

<!--
ANPASSEN: Dokumentiere offen, wofür du KI-Werkzeuge (z. B. Claude, Claude Code) eingesetzt hast.
Denkanstösse:
- Wofür: z. B. Erklärungen, Fehlersuche, Vorschläge für CSS- oder JS-Techniken, Code-Review, Aufräumen, README-Struktur
- Was ich selbst gemacht habe: Konzept, Gestaltung, Aufbau der Module, eigene Umsetzung, Entscheidungen
- Wie ich die Ergebnisse geprüft habe: im Browser getestet (hell/dunkel, Mobile), im Backend getestet, Code gelesen und verstanden, mit Dokumentation abgeglichen, Vorschläge angepasst oder verworfen
- Wo hat KI nicht geholfen oder falsche Vorschläge gemacht?
-->

**Wofür**
- 
- 

**Was ich selbst gemacht habe**
- 
- 

**Wie ich die Ergebnisse geprüft habe**
- 
- 

---

## Ressourcen

<!--
ANPASSEN: Weitere Tutorials, Videos, Kursunterlagen und Inspirationsquellen selbst nachtragen.
-->

- IM5-Starter-Theme und Kursunterlagen, Modul IM5, FH Graubünden
- [Carbon Fields Dokumentation](https://docs.carbonfields.net/)
- [WordPress Developer Reference](https://developer.wordpress.org/reference/), u. a. `register_post_type()`, `register_taxonomy()`, `register_nav_menus()`, `wp_enqueue_style()`, `wp_enqueue_script_module()`
- [WordPress Theme Handbook: Template-Hierarchie](https://developer.wordpress.org/themes/basics/template-hierarchy/)
- [MDN: `<dialog>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/dialog)
- [MDN: Intersection Observer API](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
- [MDN: `<feTurbulence>`](https://developer.mozilla.org/en-US/docs/Web/SVG/Element/feTurbulence)
- [MDN: `prefers-reduced-motion`](https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-reduced-motion)
- [Fontsource](https://fontsource.org/) und [Google Fonts](https://fonts.google.com/) als Quelle der Schriftdateien. Alle Schriften unter der [SIL Open Font License 1.1](https://openfontlicense.org/).
- [Plugin «Safe SVG»](https://wordpress.org/plugins/safe-svg/)
- 

---

## Autor

Luca Balsiger, MMP, FH Graubünden, Klasse mmp24b.
