<?php
if (!defined('ABSPATH')) exit; // Sécurité

add_filter('site_transient_update_themes', function ($transient) {
    if (!is_object($transient)) $transient = new stdClass();

    // Déterminer le thème actif en fonction des constantes
    if (defined('OCADE_IS_CHILD_THEME') && OCADE_IS_CHILD_THEME) {
        $theme = wp_get_theme()->parent();
        $theme_slug = $theme->get_template();
    } elseif (defined('OCADE_IS_THEME') && OCADE_IS_THEME) {
        $theme = wp_get_theme();
        $theme_slug = $theme->get_stylesheet();
    } else return $transient; // Ce n'est pas un thème, on ne fait rien

    $current_version = $theme->get('Version');

    // Récupérer la version distante (version.txt)
    $remote_version = get_transient(OCADE_REMOTE_VERSION);
    if (false === $remote_version) {
        $response = wp_remote_get(OCADE_VERSION_URL);
        if (is_wp_error($response)) return $transient;

        // Nettoyer la version pour supprimer les espaces ou caractères invisibles
        $remote_version = trim(wp_remote_retrieve_body($response));
        $remote_version = preg_replace('/[^0-9.]/', '', $remote_version);
        set_transient(OCADE_REMOTE_VERSION, $remote_version, 6 * HOUR_IN_SECONDS);
    }

    // Comparaison des versions
    if (version_compare($remote_version, $current_version, '>')) {
        if (!isset($transient->response)) $transient->response = [];

        $transient->response[$theme_slug] = [
            'theme'       => $theme_slug,
            'new_version' => $remote_version,
            'url'         => OCADE_THEME_REPO,
            'package'     => OCADE_ZIP_URL,
            'icons'       => [
                'svg'  => OCADE_ICON_SVG_URL,
                '1x'   => OCADE_ICON_1X_URL,
                '2x'   => OCADE_ICON_2X_URL,
                '3x'   => OCADE_ICON_3X_URL,
                '4x'   => OCADE_ICON_4X_URL,
                '5x'   => OCADE_ICON_5X_URL,
            ],
        ];
    }

    return $transient;
});

// Supprimer le cache de version après mise à jour
add_action('upgrader_process_complete', function ($upgrader_object, $options) {
    if ($options['action'] === 'update' && $options['type'] === 'theme') delete_transient(OCADE_REMOTE_VERSION);
}, 10, 2);
