<?php
namespace VisitMarche\ThemeTail;

use VisitMarche\Theme\Lib\Twig;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <script type="module" crossorigin src="/assets/agenda.4cb13ffc.js"></script>
    <link rel="modulepreload" crossorigin href="/assets/Footer.82351317.js">
    <link rel="modulepreload" crossorigin href="/assets/Cadre.acc3c6eb.js">
    <link rel="stylesheet" href="/assets/Footer.3d49acf8.css">
    <link rel="stylesheet" href="/assets/input.c3741591.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://visit.marche.be/wp-content/themes/visittail/js/searchXl.js"></script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open();
Twig::rendPage(
    '@VisitTail/header/_header.html.twig',
    [

    ]
);
?>
