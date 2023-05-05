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

if ( !class_exists( 'pl_block_1' ) ) {

	class pl_block_1 {
		
		public function __construct() {
			add_action( 'init', array( $this, 'register_block' ) );
		}
		
		public function register_block() {
			register_block_type( PL_SCAFFOLD_PLUGIN_PLUGIN_DIR . 'build/blocks/example' );
		}
	}
}

new pl_block_1();