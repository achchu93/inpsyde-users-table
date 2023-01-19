/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Edit component.
 * See https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
 *
 * @param {array} attributes Attributes for the edit compoent
 * @param {string} name Name of the block
 * @returns {Component} Render the edit component
 */
const UsersTableBlockEdit = ({ attributes, name }) => {

    const blockProps = useBlockProps();

    return (
        <>
            <div {...blockProps}>
				<ServerSideRender attributes={attributes} block={name} />
			</div>
        </>
    );
}
export default UsersTableBlockEdit;
