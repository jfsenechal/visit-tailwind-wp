<?php

namespace VisitMarche\ThemeTail\Lib;

use AcMarche\Pivot\Entities\Category;
use AcMarche\Pivot\Entities\Event\Event;
use AcMarche\Pivot\Entities\Offre\Offre;
use AcMarche\Pivot\Entity\TypeOffre;

/**
 * Ajouts des routes pour les offres
 * https://roots.io/routing-wp-requests/
 * https://developer.wordpress.org/reference/functions/add_rewrite_rule/#user-contributed-notes
 * Class Router.
 */
class RouterPivot
{
    public const PARAM_EVENT = 'codecgt';
    public const EVENT_URL = 'manifestation';
    public const PARAM_OFFRE = 'codeoffre';
    public const OFFRE_URL = 'offre';
    public const PARAM_FILTRE = 'filtre';

    public function __construct()
    {
        $this->addRouteEvent();
        $this->addRouteOffre();
    }

    public static function getCurrentUrl(): string
    {
        global $wp;

        return home_url($wp->request);
    }

    public static function getUrlEventCategory(Category $categorie): string
    {
        return '/'.self::EVENT_URL.$categorie->id;
    }

    public static function getUrlEvent(Event $offre, int $categoryId): string
    {
        return get_category_link($categoryId).self::EVENT_URL.'/'.$offre->codeCgt;
    }

    public static function setRouteEvents(array $events, int $categoryId)
    {
        array_map(
            function ($event) use ($categoryId) {
                $event->url = self::getUrlEvent($event, $categoryId);
            },
            $events
        );
    }

    public static function getUrlOffre(Offre $offre, int $categoryId): string
    {
        return get_category_link($categoryId).self::OFFRE_URL.'/'.$offre->codeCgt;
    }

    public static function getUrlFiltre(int $categoryId): string
    {
        return get_category_link(get_category($categoryId)).'?filtre=';
    }

    /**
     * @param TypeOffre[] $filtres
     * @return TypeOffre[]
     */
    public static function setRoutesToFilters(array $filtres, int $categoryId): array
    {
        $urlfiltre = self::getUrlFiltre($categoryId);
        foreach ($filtres as $filtre) {
            $key = $filtre->urn;
            $filtre->url = $urlfiltre.$key;
        }

        return $filtres;
    }

    public function addRouteEvent(): void
    {
        add_action(
            'init',
            function () {
                $taxonomy = get_taxonomy('category');
                $categoryBase = $taxonomy->rewrite['slug'];
                //^= depart, $ fin string, + one or more, * zero or more, ? zero or one, () capture
                // [^/]* => veut dire tout sauf /
                //url parser: /category/agenda/event/866/
                //attention si pas sous categorie
                //https://regex101.com/r/guhLuX/1
                //https://regex101.com/r/H8lm1w/1
                add_rewrite_rule(
                //'^[a-z][a-z]/'.$categoryBase.'/([\w-]+)/manifestation/(\d+)/?$',
                    '^'.$categoryBase.'/([\w-]+)/manifestation/([a-zA-Z0-9-]+)[/]?$',
                    'index.php?category_name=$matches[1]&'.self::PARAM_EVENT.'=$matches[2]',
                    'top'
                );
            }
        );
        add_filter(
            'query_vars',
            function ($query_vars) {
                $query_vars[] = self::PARAM_EVENT;

                return $query_vars;
            }
        );
        add_action(
            'template_include',
            function ($template) {
                global $wp_query;
                if (is_admin() || !$wp_query->is_main_query()) {
                    return $template;
                }

                if (false === get_query_var(self::PARAM_EVENT) ||
                    '' === get_query_var(self::PARAM_EVENT)) {
                    return $template;
                }

                return get_template_directory().'/single-event.php';
            }
        );
    }

    public function addRouteOffre(): void
    {
        //Setup a rule
        add_action(
            'init',
            function () {
                $taxonomy = get_taxonomy('category');
                $categoryBase = $taxonomy->rewrite['slug'];
                //^= depart, $ fin string, + one or more, * zero or more, ? zero or one, () capture
                // [^/]* => veut dire tout sauf /
                //https://regex101.com/r/pnR7x3/1
                //https://stackoverflow.com/questions/67060063/im-trying-to-capture-data-in-a-web-url-with-regex
                add_rewrite_rule(
                    '^'.$categoryBase.'/(?:([a-zA-Z0-9_-]+)/){1,3}offre/([a-zA-Z0-9-]+)[/]?$',
                    //'^'.$categoryBase.'/(?:([a-zA-Z0-9_-]+)/){1,3}offre/(\d+)/?$',
                    'index.php?category_name=$matches[1]&'.self::PARAM_OFFRE.'=$matches[2]',
                    'top'
                );
            }
        );
        //Whitelist the query param
        add_filter(
            'query_vars',
            function ($query_vars) {
                $query_vars[] = self::PARAM_OFFRE;

                return $query_vars;
            }
        );
        //Add a handler to send it off to a template file
        add_action(
            'template_include',
            function ($template) {
                global $wp_query;
                if (is_admin() || !$wp_query->is_main_query()) {
                    return $template;
                }
                if (false === get_query_var(self::PARAM_OFFRE) ||
                    '' === get_query_var(self::PARAM_OFFRE)) {
                    return $template;
                }

                return get_template_directory().'/single-offre.php';
            }
        );
    }

    public function custom_rewrite_tag(): void
    {
        add_rewrite_tag('%offre%', '([^&]+)'); //utilite?
    }
}
