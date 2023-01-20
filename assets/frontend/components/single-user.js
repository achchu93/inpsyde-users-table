/**
 * External dependencies
 */
import DataTable from 'react-data-table-component';

/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

// Table columns
const columns = [
    { id: 'key', selector: row => row.key, width: '30%', style: { fontWeight: 'bold', textTransform: 'uppercase', fontSize: '12px' } },
    { id: 'value', selector: row => row.value }
];
// Table custom stlyes
const tableStyles = {
    headRow: {
        style: {
            borderBottom: 'none'
        }
    }
};

/**
 * Single User Component
 *
 * @param {int} userId Current user ID
 * @param {function} setUserId Callback for set new user id
 * @returns {Component} Render SingleUser component
 *
 * @since 1.0.0
 */
const SingleUser = ({ userId }) => {

    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if ( user ) {
            return;
        }

        const params = new URLSearchParams(
            {
                action: 'ut_get_user',
                user_id: userId,
                _ajax_nonce: window.UsersTable.nonce
            }
        );

        fetch(`${window.UsersTable.ajaxUrl}?` + params)
            .then((response) => response.json())
            .then(response => {
                setUser(response.data);
                setLoading(false);
            })
            .catch(error => {
                setUser(null);
                setLoading(false);
            });
    });

    const data = user ? Object.entries(user).map(([key, value]) => {

        let filteredValue = value;

        if ( key === 'company' ) {
            filteredValue = value.name;
        }

        if ( key === 'address' ) {
            filteredValue = [ value.suite , value.street.concat(','), value.city.concat('.'), value.zipcode ];
            filteredValue = filteredValue.join( ' ' );
        }

        return {
            key,
            value: filteredValue
        };
    }) : [];

    return (
        <DataTable
            columns={columns}
            data={data}
            progressPending={loading}
            noHeader={true}
            customStyles={tableStyles}
        />
    );
}
export default SingleUser;
