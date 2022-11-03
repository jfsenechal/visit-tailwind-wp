<?php

namespace VisitMarche\ThemeTail;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcMarche\Pivot\Entities\Offre\Offre;
use Exception;
use VisitMarche\Theme\Lib\GpxViewer;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\RouterPivot;
use VisitMarche\Theme\Lib\Twig;

get_header();

$codeCgt = get_query_var(RouterPivot::PARAM_OFFRE);

$currentCategory = get_category_by_slug(get_query_var('category_name'));
$urlBack = get_category_link($currentCategory);
$nameBack = $currentCategory->name;

$pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);

$offre = null;

if (!str_contains($codeCgt, "-")) {
    $offre = $pivotRepository->getOffreByIdHades($codeCgt);
}

if (!$offre) {
    try {
        $offre = $pivotRepository->getOffreByCgtAndParse($codeCgt, Offre::class);
    } catch (Exception $e) {
        Twig::rendPage(
            '@VisitTail/errors/500.html.twig',
            [
                'title' => 'Error',
                'message' => 'Impossible de charger l\'offre: '.$e->getMessage(),
            ]
        );
        get_footer();

        return;
    }
}

if (null === $offre) {
    Twig::rendPage(
        '@VisitTail/errors/404.html.twig',
        [
            'url' => '',
            'title' => 'Page non trouvée',
        ]
    );

    get_footer();

    return;
}

$language = LocaleHelper::getSelectedLanguage();
$categoryOffres = get_category_by_slug('offres');
$urlCat = get_category_link($categoryOffres);
$tags = [];
foreach ($offre->categories as $category) {
    $tags[] = [
        'name' => $category->labelByLanguage($language),
        'url' => $urlCat.'?'.RouterPivot::PARAM_FILTRE.'='.$category->urn,
    ];
}
$recommandations = $offres = [];
if (count($offre->voir_aussis)) {
    $offres = $offre->voir_aussis;
} else {
    $offres = $pivotRepository->getSameOffres($offre);
}
foreach ($offres as $item) {
    $url = RouterPivot::getUrlOffre($item, $currentCategory->cat_ID);
    $tags2 = [$item->typeOffre->labelByLanguage($language)];

    $recommandations[] = [
        'title' => $item->nomByLanguage($language),
        'url' => $url,
        'image' => $item->firstImage(),
        'categories' => $tags2,
    ];
}
foreach ($offre->pois as $poi) {
    $poi->url = RouterPivot::getUrlOffre($poi, $currentCategory->cat_ID);
}

$gpxMap = null;
if (count($offre->gpxs) > 0) {
    $gpxViewer = new GpxViewer();
    $gpxMap = $gpxViewer->gpxViewer($offre->gpxs[0]);
}

Twig::rendPage(
    '@VisitTail/offre.html.twig',
    [
        'title' => $offre->nomByLanguage($language),
        'offre' => $offre,
        'excerpt' => 'ici',
        'tags' => $tags,
        'image' => $offre->firstImage(),
        'recommandations' => $recommandations,
        'urlBack' => $urlBack,
        'currentCategory' => $currentCategory,
    ]
);
get_footer();