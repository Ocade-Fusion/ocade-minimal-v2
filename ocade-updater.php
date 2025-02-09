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
        if (!empty($remote_version)) set_transient($OCADE_REMOTE_VERSION, $remote_version, 6 * HOUR_IN_SECONDS);
        else error_log('Impossible de stocker la version distante car elle est vide.');
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

// Supprimer le transient après mise à jour
add_action('upgrader_process_complete', function ($upgrader_object, $options) use ($OCADE_REMOTE_VERSION) {
    if ($options['action'] === 'update' && $options['type'] === 'theme') delete_transient($OCADE_REMOTE_VERSION);
}, 10, 2);







////////////////////// AJOUTER UN BOUTON MISE A JOUR BO /////////////////////////////////////
function ocade_add_update_refresh_button() {
    $screen = get_current_screen();

    // Vérifier si on est sur la page des mises à jour
    if ($screen->id === 'update-core') {
        echo '<div class="notice notice-info" style="margin-bottom: 15px; padding:0; width:max-content; border-radius:4px; border-left-color:#2271b1;">
            <form method="post" action="">
                <input type="hidden" name="ocade_clear_transients" value="1">
                <button type="submit" class="button" style="padding: 5px; background-color: #2271b1; border-color: transparent; border-radius:0; outline-color: none; color: white; padding-right:1rem; padding-left: 1rem; font-size:1rem;">
                    Rechercher les mises à jour Ocade
                </button>
            </form>
        </div>';
    }
}
add_action('admin_notices', __NAMESPACE__ . '\ocade_add_update_refresh_button');

// Suppression des transients liés aux mises à jour du thème
function ocade_process_clear_transients() {
    if (isset($_POST['ocade_clear_transients'])) {
        global $wpdb;

        // Récupérer tous les transients liés aux mises à jour des thèmes
        $transient_names = $wpdb->get_col("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient%_remote_version'");

        // Supprimer chaque transient trouvé
        foreach ($transient_names as $transient_name) {
            $transient_key = str_replace('_transient_', '', $transient_name);
            delete_transient($transient_key);
        }

        // Recharger la page après suppression
        wp_redirect(admin_url('update-core.php'));
        exit;
    }
}
add_action('admin_init',  __NAMESPACE__ . '\ocade_process_clear_transients');
