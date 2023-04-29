<?php
/**
 * Theme functions and definitions
 *
 * @package pl-scaffold-theme
 */

// Useful global constants .
define( 'PL_SCAFFOLD_THEME_VERSION', '1.0.0' );
define( 'PL_SCAFFOLD_THEME_TEMPLATE_URL', get_template_directory_uri() );
define( 'PL_SCAFFOLD_THEME_PATH', get_template_directory() . '/' );
define( 'PL_SCAFFOLD_THEME_DIST_PATH', PL_SCAFFOLD_THEME_PATH . 'dist/' );
define( 'PL_SCAFFOLD_THEME_DIST_URL', PL_SCAFFOLD_THEME_TEMPLATE_URL . '/dist/' );

// Include files.
require_once PL_SCAFFOLD_THEME_PATH . 'includes/core.php';
PLScaffoldTheme\Core\setup();
