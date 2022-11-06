<?php

namespace VisitMarche\ThemeTail;

use VisitMarche\ThemeTail\Lib\LocaleHelper;
use VisitMarche\ThemeTail\Lib\PostUtils;
use VisitMarche\ThemeTail\Lib\Twig;
use VisitMarche\ThemeTail\Lib\WpRepository;

get_header();

global $post;

$language = LocaleHelper::getSelectedLanguage();
$wpRepository = new WpRepository();

$slugs = explode('/', get_query_var('category_name'));
$image = PostUtils::getImage($post);
$currentCategory = get_category_by_slug($slugs[array_key_last($slugs)]);
$urlBack = get_category_link($currentCategory);

$bgcat = $wpRepository->categoryBgColor($currentCategory);
$tags = $wpRepository->getTags($post->ID);

$recommandations = $wpRepository->recommandationsByPost($post);
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
        'bgCat' => $bgcat,
        'nameBack' => $currentCategory->name,
        'content' => $content,
    ]
);
get_footer();