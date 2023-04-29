<?php

// Ensure the plugin is being run from the correct folder.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the block so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @return void
 */
function pl_scaffold_plugin_enqueue_example_block_register_block() {
	register_block_type( PL_SCAFFOLD_PLUGIN_PLUGIN_DIR . 'build/blocks/example' );
}
add_action( 'init', 'pl_scaffold_plugin_enqueue_example_block_register_block' );
