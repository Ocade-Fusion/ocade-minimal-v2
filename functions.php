<?php

add_action('after_setup_theme', function() { add_theme_support('post-thumbnails'); }); // Active les images mises en avant

require_once get_template_directory() . '/ocade-updater.php';
