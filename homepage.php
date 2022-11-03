<?php

namespace VisitMarche\ThemeTail;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcSort;
use Exception;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Lib\RouterPivot;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$wpRepository = new WpRepository();

$pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);

$intro = $wpRepository->getIntro();
$inspirationCat = $wpRepository->getCategoryBySlug('inspirations');
$inspirations = $wpRepository->getPostsByCatId($inspirationCat->cat_ID);
$category_order = get_term_meta($inspirationCat->cat_ID, CategoryMetaBox::KEY_NAME_ORDER, true);
if ('manual' === $category_order) {
    $inspirations = AcSort::getSortedItems($inspirationCat->cat_ID, $inspirations);
}
$categoryAgenda = get_category_by_slug('agenda');
$urlAgenda = '/';

try {
    $events = $pivotRepository->getEvents(true);
    if ($categoryAgenda) {
        $urlAgenda = get_category_link($categoryAgenda);
        array_map(
            function ($event) use ($categoryAgenda) {
                $event->url = RouterPivot::getUrlEvent($event, $categoryAgenda->cat_ID);
            },
            $events
        );
    }
} catch (Exception) {
    $events = [];
}

$inspirations = array_slice($inspirations, 0, 4);
$events = array_slice($events, 0, 4);
foreach ($events as $event) {
    $event->locality = $event->getAdresse()->localite[0]->get('fr');
    $event->dateEvent = [
        'year' => $event->dateEnd->format('Y'),
        'month' => $event->dateEnd->format('m'),
        'day' => $event->dateEnd->format('d'),
    ];
}
Twig::rendPage(
    '@VisitTail/homepage.html.twig',
    [
        'events' => $events,
        'inspirations' => $inspirations,
        'urlAgenda' => $urlAgenda,
        'intro' => $intro,
    ]
);