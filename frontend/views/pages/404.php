<?php
// 404-Ansicht: kurze Meldung und Link zur Startseite. Nutzt die Stile des Moduls hero-default.
?>
<div data-page="404">
    <section data-module="hero-default">
        <h1 class="title">seite nicht gefunden.</h1>
        <p class="subline">diese seite gibt es nicht oder nicht mehr.</p>
        <p class="subline"><a href="<?= esc_url(home_url('/')) ?>">zur startseite</a></p>
    </section>
</div>
