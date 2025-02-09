<?php
if (!defined('ABSPATH')) exit; // Sécurité

// Vérifier que les constantes essentielles sont bien définies
if (!defined('ORGANISATION_GITHUB') || !defined('DEPOT_GITHUB')) die('Les constantes ORGANISATION_GITHUB et DEPOT_GITHUB doivent être définies.');

// Définition des constantes dynamiques (communes à tous)
if (!defined('OCADE_THEME_REPO')) define('OCADE_THEME_REPO', 'https://github.com/' . ORGANISATION_GITHUB . '/' . DEPOT_GITHUB);

if (!defined('OCADE_VERSION_URL')) define('OCADE_VERSION_URL', OCADE_THEME_REPO . '/releases/latest/download/version.txt');

if (!defined('OCADE_ZIP_URL')) define('OCADE_ZIP_URL', OCADE_THEME_REPO . '/releases/latest/download/'.DEPOT_GITHUB.'.zip');

if (!defined('OCADE_REMOTE_VERSION')) define('OCADE_REMOTE_VERSION', DEPOT_GITHUB . '_remote_version');

if (!defined('OCADE_ICON_SVG_URL')) define('OCADE_ICON_SVG_URL', OCADE_THEME_REPO . '/assets/icons/icon.svg');
if (!defined('OCADE_ICON_1X_URL')) define('OCADE_ICON_1X_URL', OCADE_THEME_REPO . '/assets/icons/icon-1x.png');
if (!defined('OCADE_ICON_2X_URL')) define('OCADE_ICON_2X_URL', OCADE_THEME_REPO . '/assets/icons/icon-2x.png');
if (!defined('OCADE_ICON_3X_URL')) define('OCADE_ICON_3X_URL', OCADE_THEME_REPO . '/assets/icons/icon-3x.png');
if (!defined('OCADE_ICON_4X_URL')) define('OCADE_ICON_4X_URL', OCADE_THEME_REPO . '/assets/icons/icon-4x.png');
if (!defined('OCADE_ICON_5X_URL')) define('OCADE_ICON_5X_URL', OCADE_THEME_REPO . '/assets/icons/icon-5x.png');