<?php /* Öffnender HTML-Teil: doctype, head, body-start. wp_head() fügt Styles, Scripts und Meta-Tags ein. */ ?>
<!DOCTYPE html>
<html lang="<?php bloginfo('language') ?>">
<head>
    <meta charset="<?php bloginfo('charset') ?>">
    <script>
        // Dark Mode sofort setzen, bevor die Seite gezeichnet wird: verhindert das Aufblitzen des hellen Modus
        (function () {
            var theme = null;
            try {
                theme = localStorage.getItem('theme');
            } catch (e) {}
            if (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                theme = 'dark';
            }
            if (theme) {
                document.documentElement.setAttribute('data-theme', theme);
            }
        })();
    </script>
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <?php wp_head(); ?>
</head>
<body>
