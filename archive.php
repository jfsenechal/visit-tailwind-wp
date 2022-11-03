<?php

namespace VisitMarche\ThemeTail;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcSort;
use Psr\Cache\InvalidArgumentException;
use SortLink;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\PostUtils;
use VisitMarche\Theme\Lib\RouterPivot;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

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
$header = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_HEADER, true);
$icone = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_ICONE, true);
$bgcat = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_COLOR, true);
if ($header) {
    $header = '/wp-content/themes/visitmarche/assets/tartine/rsc/img/'.$header;
}

if ($icone) {
    $icone = '/wp-content/themes/visitmarche/assets/images/'.$icone;
}

$children = $wpRepository->getChildrenOfCategory($category->cat_ID);
$filtres = $wpRepository->getCategoryFilters($cat_ID);

if ([] !== $filtres) {
    $filtres = RouterPivot::setRoutesToFilters($filtres, $cat_ID);
    $pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);
    $offres = [];

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
    $posts = $postUtils->convertPostsToArray($posts);
    $offres = $postUtils->convertOffresToArray($offres, $cat_ID, $language);
    $offres = array_merge($posts, $offres);
}
$image = 'https://visitmarche.be/wp-content/themes/visitmarche/assets/tartine/rsc/img/bg_inspirations.png';
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