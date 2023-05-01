/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './editor.scss';

// Register the block
registerBlockType('pl-scaffold/example', {
    edit() {
        return <p> Hello world (from the editor)</p>;
    },
    save() {
        return <p> Hola mundo (from the frontend) </p>;
    },
});
