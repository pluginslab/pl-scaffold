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
 *
 * @package pl-scaffold-plugin
 */

// Ensure the plugin is being run from the correct folder.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PL_Scaffold_Plugin' ) ) {

	/**
	 * Class PL_Scaffold_Plugin
	 */
	class PL_Scaffold_Plugin {

		/**
		 * Constructor.
		 */
		public function __construct() {
			// Register blocks that lack a registration php script.
			add_action( 'init', array( $this, 'register_blocks' ) );

			// Run blocks registration php scripts.
			add_action( 'plugins_loaded', array( $this, 'include_block_files' ) );
		}

		/**
		 * Searches for block.php files in the blocks directory and includes them.
		 *
		 * @return void
		 */
		public function include_block_files() {
			$blocks_directory = plugin_dir_path( __FILE__ ) . 'src/blocks/';

			if ( ! is_dir( $blocks_directory ) ) {
				return;
			}

			$iterator    = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $blocks_directory ) );
			$block_files = array();

			foreach ( $iterator as $file ) {
				if ( $file->isFile() && $file->getExtension() === 'php' ) {
					$block_files[] = $file->getPathname();
				}
			}

			foreach ( $block_files as $block_file ) {
				include_once $block_file;
				error_log( $block_file );
			}
		}

		/**
		 * Searches for block.json files in the blocks directory and registers them.
		 *
		 * @return void
		 */
		public function register_blocks() {
			$blocks_directory = plugin_dir_path( __FILE__ ) . 'src/blocks/';

			if ( ! is_dir( $blocks_directory ) ) {
				return;
			}

			$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $blocks_directory ) );
			$blocks   = array();

			foreach ( $iterator as $file ) {

				$path = $file->getPath();
				if ( "$path/" !== $blocks_directory ) {
					if ( ! isset( $blocks[ $path ] ) ) {
						$blocks[ $path ] = '';
					}

					if ( $file->isFile() && $file->getExtension() === 'php' ) {
						$blocks[ $path ] = $file->getPathname();
					}
				}
			}

			foreach ( $blocks as $block_path => $block_file ) {
				if ( ! $block_file ) {
					$block_path = str_replace( 'src', 'build', $block_path );
					if ( file_exists( "$block_path/block.json" ) ) {
						register_block_type( $block_path );
					}
				}
			}
		}
	}
}

if ( class_exists( 'PL_Scaffold_Plugin' ) ) {

	// Define constants.
	define( 'PL_SCAFFOLD_PLUGIN_VERSION', '1.0' );
	define( 'PL_SCAFFOLD_PLUGIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

	new PL_Scaffold_Plugin();
}
