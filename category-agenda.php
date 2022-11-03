<?php

namespace VisitMarche\ThemeTail;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use VisitMarche\ThemeTail\Lib\RouterPivot;
use VisitMarche\ThemeTail\Lib\LocaleHelper;
use VisitMarche\ThemeTail\Lib\Twig;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);

$language = LocaleHelper::getSelectedLanguage();
$pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);

try {
    $events = $pivotRepository->getEvents(true);
    array_map(
        function ($event) use ($cat_ID, $language) {
            $event->url = RouterPivot::getUrlOffre($event, $cat_ID);
        },
        $events
    );
} catch (\Exception $e) {
    Twig::rendPage(
        '@VisitTail/errors/500.html.twig',
        [
            'title' => 'Page non chargée',
            'message' => 'Impossible de charger les évènements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}
foreach ($events as $event) {
    $event->locality = $event->getAdresse()->localite[0]->get('fr');
    $event->dateEvent = [
        'year' => $event->dateEnd->format('Y'),
        'month' => $event->dateEnd->format('m'),
        'day' => $event->dateEnd->format('d'),
    ];
}
Twig::rendPage(
    '@VisitTail/agenda.html.twig',
    [
        'events' => $events,
        'category' => $category,
        'title' => $category->name,
        'image' => 'https://visitmarche.be/wp-content/themes/visitmarche/assets/tartine/rsc/img/bg_events.png',
    ]
);

get_footer();
