<?php
namespace VisitMarche\ThemeTail;

use VisitMarche\ThemeTail\Inc\AssetsLoad;
use VisitMarche\ThemeTail\Inc\SecurityConfig;
use VisitMarche\ThemeTail\Inc\SetupTheme;

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

