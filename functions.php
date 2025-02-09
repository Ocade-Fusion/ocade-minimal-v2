<?php
if (!defined('ABSPATH')) exit; // Sécurité

// Charger la bonne configuration en fonction du contexte
require_once __DIR__ . '/inc/config/config-loader.php';

// Charger les constantes générales (évite les redéfinitions)
require_once __DIR__ . '/inc/constants.php';

// Charger le système de mise à jour
if (defined('OCADE_IS_THEME') && OCADE_IS_THEME) require_once __DIR__ . '/inc/theme-updater.php';

if (defined('OCADE_IS_PLUGIN') && OCADE_IS_PLUGIN) require_once __DIR__ . '/inc/plugin-updater.php';
