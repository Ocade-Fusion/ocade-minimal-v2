<?php

if (!defined('ABSPATH')) exit;

// Chargement du système de mise à jour
define("ORGANISATION_GITHUB", 'Ocade-Fusion'); // Nom de l'organisation GitHub
define("DEPOT_GITHUB", 'ocade-minimal'); // Nom du dépôt GitHub
define('OCADE_IS_THEME', true); // True = Thème / False = Plugin
define('OCADE_IS_CHILD_THEME', false); // Est le thème enfant ?


// Partie dynamique
define('OCADE_THEME_REPO', 'https://github.com/' . ORGANISATION_GITHUB . '/' . DEPOT_GITHUB);
define('OCADE_VERSION_URL', OCADE_THEME_REPO . '/releases/latest/download/version.txt');
define('OCADE_ZIP_URL', OCADE_THEME_REPO . '/releases/latest/download/'.DEPOT_GITHUB.'.zip');
define('OCADE_REMOTE_VERSION', DEPOT_GITHUB . '_remote_version');
define('OCADE_ICON_SVG_URL', 'https://raw.githubusercontent.com/' . ORGANISATION_GITHUB . '/' . DEPOT_GITHUB .'/master/assets/icons/icon.svg');
define('OCADE_ICON_1X_URL',  'https://raw.githubusercontent.com/' . ORGANISATION_GITHUB . '/' . DEPOT_GITHUB .'/master/assets/icons/icon-1x.png');
define('OCADE_ICON_2X_URL',  'https://raw.githubusercontent.com/' . ORGANISATION_GITHUB . '/' . DEPOT_GITHUB .'/master/assets/icons/icon-2x.png');
define('OCADE_ICON_3X_URL',  'https://raw.githubusercontent.com/' . ORGANISATION_GITHUB . '/' . DEPOT_GITHUB .'/master/assets/icons/icon-3x.png');
define('OCADE_ICON_4X_URL',  'https://raw.githubusercontent.com/' . ORGANISATION_GITHUB . '/' . DEPOT_GITHUB .'/master/assets/icons/icon-4x.png');
define('OCADE_ICON_5X_URL',  'https://raw.githubusercontent.com/' . ORGANISATION_GITHUB . '/' . DEPOT_GITHUB .'/master/assets/icons/icon-5x.png');2

switch (true) {
  case OCADE_IS_THEME:
    require_once get_template_directory() . '/inc/theme-updater.php';
    break;
  case OCADE_IS_CHILD_THEME:
    require_once get_stylesheet_directory() . '/inc/theme-updater.php';
    break;
  default:
    require_once plugin_dir_path( __FILE__ ) . 'inc/theme-updater.php';
    break;
}