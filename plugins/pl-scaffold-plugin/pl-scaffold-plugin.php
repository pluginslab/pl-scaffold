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

// Let's include all blocks here.

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @return void
 */
function pl_scaffold_plugin_register_blocks() {
	// Define the blocks directory path
	$blocks_directory = plugin_dir_path( __FILE__ ) . 'src/blocks/';

	// Ensure the directory exists
	if ( ! is_dir( $blocks_directory ) ) {
		return;
	}

	// Find all PHP files recursively in the blocks directory
	$iterator    = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $blocks_directory ) );
	$block_files = array();

	foreach ( $iterator as $file ) {
		if ( $file->isFile() && $file->getExtension() === 'php' ) {
			$block_files[] = $file->getPathname();
		}
	}

	// Include each PHP file found
	foreach ( $block_files as $block_file ) {
		include_once $block_file;
	}
}
add_action( 'plugins_loaded', 'pl_scaffold_plugin_register_blocks' );
