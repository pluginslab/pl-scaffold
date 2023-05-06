<?php
/**
 * Registers the block so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @return void
 * @package pl-scaffold-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PL_Block_1' ) ) {

	/**
	 * Class pl_block_1
	 */
	class PL_Block_1 {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_block' ) );
		}

		/**
		 * Registers the block so that they can be enqueued through Gutenberg in
		 * the corresponding context.
		 *
		 * @return void
		 */
		public function register_block() {
			register_block_type( PL_SCAFFOLD_PLUGIN_PLUGIN_DIR . 'build/blocks/example' );
		}
	}
}

new PL_Block_1();
