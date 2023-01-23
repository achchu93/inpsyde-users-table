/**
 * WordPress dependencies
 */
import { Placeholder, Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Edit component.
 * See https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
 *
 * @returns {Component} Render the edit component
 */
const UsersTableBlockEdit = () => {

    const previewUrl = window.UsersTable.pluginUrl + 'assets/blocks/users-table/preview.png';

    return (
        <Placeholder
            icon="editor-table"
            label={ __( 'Users Table', 'users-table' ) }

        >
            <p>{__('This is a placeholder for Users Table block. Real table will be rendered in frontned.', 'users-table')}</p>
            <img src={previewUrl} />
        </Placeholder>
    );
}
export default UsersTableBlockEdit;
