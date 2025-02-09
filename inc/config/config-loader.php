<?php
if (!defined('ABSPATH')) exit; // Sécurité

// Détection automatique du contexte (Thème parent, Thème enfant, Plugin)
if (defined('TEMPLATEPATH') && get_template_directory() === get_stylesheet_directory()) require_once __DIR__ . '/config-parent.php'; // Thème parent