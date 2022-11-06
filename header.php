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
