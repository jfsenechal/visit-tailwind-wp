<?php

namespace VisitMarche\ThemeTail;

use VisitMarche\ThemeTail\Inc\ApiRoutes;
use VisitMarche\ThemeTail\Inc\AssetsLoad;
use VisitMarche\ThemeTail\Inc\PivotMetaBox;
use VisitMarche\ThemeTail\Inc\SecurityConfig;
use VisitMarche\ThemeTail\Inc\SetupTheme;
use VisitMarche\ThemeTail\Lib\RouterPivot;

/**
 * Initialisation du thème
 */
//new SetupTheme();
/**
 * Chargement css, js
 */
new AssetsLoad();
/**
 * Un peu de sécurité
 */
new SecurityConfig();
/**
 * Enregistrement des routes api
 */
new ApiRoutes();
/*
 * Ajout de routage pour pivot
 */
new RouterPivot();
/*
 * Pour hades
 */
new PivotMetaBox();

