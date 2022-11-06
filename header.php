<?php

namespace VisitMarche\ThemeTail;

use VisitMarche\ThemeTail\Inc\Menu;
use VisitMarche\ThemeTail\Lib\LocaleHelper;
use VisitMarche\ThemeTail\Lib\Twig;

$locale = LocaleHelper::getSelectedLanguage();
?>
    <!doctype html>
<html lang="<?php echo $locale; ?>">
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="https://gmpg.org/xfn/11">

        <script type="module" crossorigin src="/assets/agenda.df3fe75a.js"></script>
        <script type="module" crossorigin src="/assets/article.87ed2af1.js"></script>
        <link rel="modulepreload" crossorigin href="/assets/Cadre.e341a59e.js">
        <link rel="modulepreload" crossorigin href="/assets/folder.bd6a1526.js">
        <link rel="modulepreload" crossorigin href="/assets/Footer.2daf29a2.js">
        <link rel="stylesheet" href="/assets/Footer.3d49acf8.css">
        <link rel="stylesheet" href="/assets/home.9a649143.css">
        <link rel="modulepreload" crossorigin href="/assets/home.64b52e82.js">
        <link rel="stylesheet" href="/assets/input.07fc8010.css">
        <link rel="stylesheet" href="/assets/offre.431ac77a.css">
        <link rel="modulepreload" crossorigin href="/assets/offre.431ac77a.css">
        <link rel="modulepreload" crossorigin href="/assets/offres.a47128ff.js">
        <link rel="modulepreload" crossorigin href="/assets/offres.a47128ff.js">
        <link rel="modulepreload" crossorigin href="/assets/posts.afb79d4b.js">
        <link rel="modulepreload" crossorigin href="/assets/SeeAlso.b7d944b9.js">

        <script src="https://visit.marche.be/wp-content/themes/visittail/assets/js/searchXl.js" defer></script>
        <script src="https://visit.marche.be/wp-content/themes/visittail/assets/js/menuMobile.js" defer></script>
        <script src="https://visit.marche.be/wp-content/themes/visittail/assets/js/refreshOffres.js" defer></script>
        <!--
        <script type="module" src="https://visit.marche.be/wp-content/themes/visittail/assets/js/map.js" defer></script>
        -->
        <script src="//unpkg.com/alpinejs" defer></script>
        <?php wp_head(); ?>
    </head>

<body <?php body_class(); ?>>
    <?php wp_body_open();
$menu = new Menu();
$items = $menu->getMenuTop();

Twig::rendPage(
    '@VisitTail/header/_header.html.twig',
    [
        'items' => $items,
    ]
);
