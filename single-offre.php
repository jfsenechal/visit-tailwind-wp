<?php

namespace VisitMarche\ThemeTail;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcMarche\Pivot\Entities\Offre\Offre;
use Exception;
use VisitMarche\Theme\Lib\Elasticsearch\Searcher;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\PostUtils;
use VisitMarche\Theme\Lib\RouterPivot;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

global $post;

$codeCgt = get_query_var(RouterPivot::PARAM_OFFRE);

$language = LocaleHelper::getSelectedLanguage();
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
            'errors/500.html.twig',
            [
                'title' => 'Error',
                'message' => 'Impossible de charger l\'offre: '.$e->getMessage(),
            ]
        );
        get_footer();

        return;
    }
}

$wpRepository = new WpRepository();

$slugs = explode('/', get_query_var('category_name'));
$image = PostUtils::getImage($post);
$currentCategory = get_category_by_slug($slugs[array_key_last($slugs)]);
$urlBack = get_category_link($currentCategory);

$tags = $wpRepository->getTags($post->ID);
$recommandations = $wpRepository->getSamePosts($post->ID);
$next = null;
if (0 === \count($recommandations)) {
    $searcher = new Searcher();
    global $wp_query;
    $recommandations = $searcher->searchRecommandations($wp_query);
}
if ([] !== $recommandations) {
    $next = $recommandations[0];
}

$recommandations = array_slice($recommandations, 0, 3);
$content = get_the_content(null, null, $post);
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

Twig::rendPage(
    '@VisitTail/article.html.twig',
    [
        'title' => $post->post_title,
        'post' => $post,
        'excerpt' => $post->post_excerpt,
        'tags' => $tags,
        'image' => $image,
        'recommandations' => $recommandations,
        'urlBack' => $urlBack,
        'currentCategory' => $currentCategory,
        'content' => $content,
    ]
);
get_footer();