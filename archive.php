<?php

namespace VisitMarche\ThemeTail;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcMarche\Pivot\Entity\TypeOffre;
use AcSort;
use Psr\Cache\InvalidArgumentException;
use SortLink;
use VisitMarche\ThemeTail\Inc\CategoryMetaBox;
use VisitMarche\ThemeTail\Lib\IconeEnum;
use VisitMarche\ThemeTail\Lib\LocaleHelper;
use VisitMarche\ThemeTail\Lib\PostUtils;
use VisitMarche\ThemeTail\Lib\RouterPivot;
use VisitMarche\ThemeTail\Lib\Twig;
use VisitMarche\ThemeTail\Lib\WpRepository;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);
$categoryName = single_cat_title('', false);
$permalink = get_category_link($cat_ID);

$wpRepository = new WpRepository();
$translator = LocaleHelper::iniTranslator();
$language = LocaleHelper::getSelectedLanguage();

$parent = $wpRepository->getParentCategory($cat_ID);

$urlBack = '/'.$language;
$nameBack = $translator->trans('menu.home');

if ($parent) {
    $urlBack = get_category_link($parent->term_id);
    $nameBack = $parent->name;
}

$posts = $wpRepository->getPostsByCatId($cat_ID);
$category_order = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_ORDER, true);
if ('manual' === $category_order) {
    $posts = AcSort::getSortedItems($cat_ID, $posts);
}
$image = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_HEADER, true);
$icone = IconeEnum::icone($category->slug);
$bgcat = IconeEnum::bgColor($category->slug);

if ($image) {
    $image = get_template_directory_uri().'/assets/tartine/'.$image;
} else {
    $image = get_template_directory_uri().'/assets/tartine/bg_inspirations.png';
}

if ($icone) {
    $icone = get_template_directory_uri().'/assets/tartine/'.$icone;
}

$children = $wpRepository->getChildrenOfCategory($category->cat_ID);
$filtres = $wpRepository->getCategoryFilters($cat_ID);
$offres = [];

if ([] !== $filtres) {
    if (count($filtres) > 1) {
        $filtreTout = new TypeOffre("Tout", 0, 0, "ALL", "", "Type", null);
        $filtreTout->id = 0;
        $filtres[] = $filtreTout;
    }
    $filtres = RouterPivot::setRoutesToFilters($filtres, $cat_ID);
    $pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);

    try {
        $offres = $pivotRepository->getOffres($filtres);
        array_map(
            function ($offre) use ($cat_ID, $language) {
                $offre->url = RouterPivot::getUrlOffre($offre, $cat_ID);
            },
            $offres
        );
    } catch (InvalidArgumentException $e) {
        dump($e->getMessage());
    }
    //fusion offres et articles
    $postUtils = new PostUtils();
    //  $posts = $postUtils->convertPostsToArray($posts);
    $offres = $postUtils->convertOffresToArray($offres, $cat_ID, $language);
    // $offres = array_merge($posts, $offres);
}
$sortLink = SortLink::linkSortArticles($cat_ID);
Twig::rendPage(
    '@VisitTail/category.html.twig',
    [
        'title' => $categoryName,
        'excerpt' => $category->description,
        'image' => $image,
        'category' => $category,
        'urlBack' => $urlBack,
        'children' => $children,
        'filtres' => $filtres,
        'nameBack' => $nameBack,
        'posts' => $posts,
        'offres' => $offres,
        'sortLink' => $sortLink,
        'icone' => $icone,
        'bgcat' => $bgcat,
    ]
);
get_footer();