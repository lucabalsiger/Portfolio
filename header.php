<!DOCTYPE html>
<html lang="<?php bloginfo('language') ?>">
<head>
    <meta charset="<?php bloginfo('charset') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Big+Shoulders+Display:ital,wght@0,700;0,900;1,700;1,900&family=Barlow+Condensed:ital,wght@0,700;0,800;1,700;1,800&display=swap">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/frontend/styles/main.css">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title><?php bloginfo('name') ?></title>
    <?php wp_head(); ?>
</head>
<body>
