<?php

namespace VisitMarche\ThemeTail\Inc;

class AssetsLoad
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', fn() => $this->visitmarcheAssets());
        add_filter('script_loader_tag', fn($tag, $handle, $src) => $this->addAsModule($tag, $handle, $src), 10, 3);
    }

    public function visitmarcheAssets(): void
    {
        wp_enqueue_script(
            'oljf-js',
            get_template_directory_uri().'/assets/js/dist/js/oljf.js',
            [],
            false,
            false
        );
     /*   wp_enqueue_script(
            'titi-js',
            get_template_directory_uri().'/assets/js/titi.js',
            [],
            false,
            false
        );*/

     /*   wp_enqueue_style(
            'visitmarche-jf-style',
            get_template_directory_uri().'/assets/js/dist/css/oljf.css',
            [],
            wp_get_theme()->get('Version')
        );*/

    }

    /**
     * Pour vue
     * @param $tag
     * @param $handle
     * @param $src
     * @return mixed|string
     */
    function addAsModule($tag, $handle, $src)
    {
        if (!in_array($handle, ['oljf-js','titi-js'])) {
            return $tag;
        }

        return '<script type="module" src="'.esc_url($src).'"></script>';
    }
}
