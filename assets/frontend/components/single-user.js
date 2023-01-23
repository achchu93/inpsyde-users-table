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
const SingleUser = ({ userId, setUserId }) => {

    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [unmounted, setUnmounted] = useState(false); // prevent memory leak

    useEffect(() => {

        if ( user || unmounted ) {
            return;
        }

        const params = new URLSearchParams(
            {
                action: 'ut_get_user',
                user_id: userId,
                _ajax_nonce: window.UsersTable.nonce
            }
        );

        const abort = new AbortController();

        fetch( `${window.UsersTable.ajaxUrl}?` + params, { signal: abort.signal } )
            .then((response) => response.json())
            .then(response => {
                setUser(response.data);
                setLoading(false);
            })
            .catch(error => {
                setUser(null);
                setLoading(false);
            });

        return () => {
            setUnmounted(true); // prevent memory leak
            abort.abort(); // prevent unwanted api calls
        };
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

    const onBackToUsers = (e) => {
        e.preventDefault();

        window.history.replaceState({}, '', window.location.href.replace(`#${userId}`, ''));
        setUserId('');
    }

    return (
        <>
            <DataTable
                columns={columns}
                data={data}
                progressPending={loading}
                noHeader={true}
                customStyles={tableStyles}
            />
            {
                user && (
                    <a
                        role="button"
                        onClick={onBackToUsers}
                        style={{marginTop: '10px', marginBottom: '10px', display: 'inline-block'}}
                    >
                        {window.UsersTable.tableActions.backToAllUsers}
                    </a>
                )
            }
        </>
    );
}
export default SingleUser;
