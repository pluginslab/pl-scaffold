<?php
/**
 * Plugin Name: Pluginslab Scaffold Plugin
 * Plugin URI: http://pluginslab.com/
 * Description: This is a scaffold plugin that goes together with the scaffold theme.
 * Version: 1.0
 * Author: Marcel Schmitz
 * Author URI: http://marcelschmitz.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pl-scaffold-plugin
 * Domain Path: /languages
 */

// Ensure the plugin is being run from the correct folder.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
define( 'PL_SCAFFOLD_PLUGIN_VERSION', '1.0' );
define( 'PL_SCAFFOLD_PLUGIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
