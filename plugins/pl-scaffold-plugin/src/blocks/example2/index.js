/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './editor.scss';
import metadata from './block.json';

// Register the block
registerBlockType( metadata, {
    edit() {
        return <p> Hello world (from the editor)</p>;
    },
    save() {
        return <p> Hola mundo (from the frontend) </p>;
    },
});
