<?php

namespace VisitMarche\ThemeTail;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcMarche\Pivot\Entities\Offre\Offre;
use Exception;
use VisitMarche\ThemeTail\Lib\LocaleHelper;
use VisitMarche\ThemeTail\Lib\RouterPivot;
use VisitMarche\ThemeTail\Lib\Twig;

get_header();

$codeCgt = get_query_var(RouterPivot::PARAM_OFFRE);

$currentCategory = get_category_by_slug('agenda');
$urlBack = get_category_link($currentCategory);
$pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);

try {
    $event = $pivotRepository->getOffreByCgtAndParse($codeCgt, Offre::class);
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

if (null === $event) {
    Twig::rendPage(
        '@VisitTail/errors/404.html.twig',
        [
            'url' => '',
            'title' => 'Evènement non trouvée',
        ]
    );

    get_footer();

    return;
}
$event->url = RouterPivot::getUrlEvent($event, $currentCategory->cat_ID);
$offres = $pivotRepository->getSameEvents($event);
RouterPivot::setRouteEvents($offres, $currentCategory->cat_ID);

$language = LocaleHelper::getSelectedLanguage();
$categoryOffres = get_category_by_slug('offres');
$urlCat = get_category_link($categoryOffres);
$tags = [];
foreach ($event->categories as $category) {
    $tags[] = [
        'name' => $category->labelByLanguage($language),
        'url' => $urlCat.'?'.RouterPivot::PARAM_FILTRE.'='.$category->urn,
    ];
}
$recommandations = $offres = [];
if (count($event->voir_aussis)) {
    $offres = $event->voir_aussis;
} else {
    $offres = $pivotRepository->getSameOffres($event);
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

Twig::rendPage(
    '@VisitTail/offre.html.twig',
    [
        'title' => $event->nomByLanguage($language),
        'offre' => $event,
        'excerpt' => 'ici',
        'tags' => $tags,
        'image' => $event->firstImage(),
        'recommandations' => $recommandations,
        'urlBack' => $urlBack,
        'nameBack' => $currentCategory->name,
    ]
);
get_footer();