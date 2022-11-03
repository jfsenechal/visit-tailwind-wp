<?php

namespace VisitMarche\ThemeTail\Inc;

class AssetsLoad
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', fn() => $this->visitmarcheAssets());
    }

    public function visitmarcheAssets(): void
    {

    }
}
