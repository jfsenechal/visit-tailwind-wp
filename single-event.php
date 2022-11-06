<?php

namespace VisitMarche\ThemeTail;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use Exception;
use VisitMarche\ThemeTail\Lib\LocaleHelper;
use VisitMarche\ThemeTail\Lib\RouterPivot;
use VisitMarche\ThemeTail\Lib\Twig;
use VisitMarche\ThemeTail\Lib\WpRepository;

get_header();

$codeCgt = get_query_var(RouterPivot::PARAM_OFFRE);

$pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);

try {
    $event = $pivotRepository->getEvent($codeCgt);
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
$wpRepository = new WpRepository();

$currentCategory = get_category_by_slug('agenda');
$urlBack = get_category_link($currentCategory);
$bgcat = $wpRepository->categoryBgColor($currentCategory);

$event->url = RouterPivot::getUrlEvent($event, $currentCategory->cat_ID);

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

$recommandations = $wpRepository->recommandationsByOffre($event);

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
        'bgCat' => $bgcat,
    ]
);
get_footer();