<?php

namespace OcadeMinimal;

add_filter('site_transient_update_themes', function ($transient) {
    $ORGANISATION_GITHUB = 'Ocade-Fusion';
    $DEPOT_GITHUB = 'ocade-minimal-v2';

    $OCADE_THEME_REPO = 'https://github.com/' . $ORGANISATION_GITHUB . '/' . $DEPOT_GITHUB;
    $OCADE_VERSION_URL = 'https://raw.githubusercontent.com/' . $ORGANISATION_GITHUB . '/' . $DEPOT_GITHUB . '/master/version.txt';
    $OCADE_ZIP_URL = $OCADE_THEME_REPO . '/releases/latest/download/' . $DEPOT_GITHUB . '.zip';
    $OCADE_REMOTE_VERSION = $DEPOT_GITHUB . '_remote_version';
    $OCADE_ICON_SVG_URL = 'https://raw.githubusercontent.com/' . $ORGANISATION_GITHUB . '/' . $DEPOT_GITHUB . '/master/assets/icons/icon.svg';
    $OCADE_ICONS = [
        '1x' => 'https://raw.githubusercontent.com/' . $ORGANISATION_GITHUB . '/' . $DEPOT_GITHUB . '/master/assets/icons/icon-1x.png',
        '2x' => 'https://raw.githubusercontent.com/' . $ORGANISATION_GITHUB . '/' . $DEPOT_GITHUB . '/master/assets/icons/icon-2x.png',
        '3x' => 'https://raw.githubusercontent.com/' . $ORGANISATION_GITHUB . '/' . $DEPOT_GITHUB . '/master/assets/icons/icon-3x.png',
        '4x' => 'https://raw.githubusercontent.com/' . $ORGANISATION_GITHUB . '/' . $DEPOT_GITHUB . '/master/assets/icons/icon-4x.png',
        '5x' => 'https://raw.githubusercontent.com/' . $ORGANISATION_GITHUB . '/' . $DEPOT_GITHUB . '/master/assets/icons/icon-5x.png'
    ];

    if (!is_object($transient)) $transient = new \stdClass();

    $theme = wp_get_theme();
    if ($theme->parent()) $theme = $theme->parent(); 
    $theme_slug = $theme->get_stylesheet();
    $current_version = $theme->get('Version');

    // Récupérer la version distante
    $remote_version = get_transient($OCADE_REMOTE_VERSION);
    if (!$remote_version) { // Évite d'appeler GitHub à chaque chargement
        $response = wp_remote_get($OCADE_VERSION_URL);

        if (is_wp_error($response)) {
            error_log('Erreur lors de la récupération de la version distante : ' . $response->get_error_message());
            return $transient;
        }

        $remote_version = trim(wp_remote_retrieve_body($response));
        $remote_version = preg_replace('/[^0-9.]/', '', $remote_version);

        if (empty($remote_version)) {
            error_log('La version distante est vide.');
            return $transient;
        }

        set_transient($OCADE_REMOTE_VERSION, $remote_version, 6 * HOUR_IN_SECONDS);
    }

    // Comparaison des versions
    if (version_compare($remote_version, $current_version, '>')) {
        if (!isset($transient->response)) $transient->response = []; // S'assurer que c'est un tableau

        $transient->response[$theme_slug] = [
            'theme'       => $theme_slug,
            'new_version' => $remote_version,
            'url'         => $OCADE_THEME_REPO,
            'package'     => $OCADE_ZIP_URL,
            'icons'       => array_merge(['svg' => $OCADE_ICON_SVG_URL], $OCADE_ICONS),
        ];
    }

    return $transient;
});

add_action('upgrader_process_complete', function ($upgrader_object, $options) {
    if ($options['action'] === 'update' && $options['type'] === 'theme') delete_transient('ocade-minimal-v2_remote_version');
}, 10, 2);
