/**
 * External dependencies
 */
import DataTable from 'react-data-table-component';

/**
 * WordPress dependencies
 */
import { useState, useEffect, useRef } from '@wordpress/element';

// Table columns
const columns = window.UsersTable.tableColumns.map( column => ({...column, selector: row => row[column.id] }) );
// Table pagination options
const paginationOptions = {
    noRowsPerPage: true
}
// Table custom stlyes
const tableStyles = {
    head: {
        style: {
            fontWeight: 'bold',
            textTransform: 'uppercase'
        }
    }
}

/**
 * Users table component
 *
 * @param {int} userId Current user ID
 * @param {function} setUserId Callback for set new user id
 * @returns {Component} Render UserList component
 */
const UserList = ({ setUserId }) => {

    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [unmounted, setUnmounted] = useState(false); // prevent memory leak

    useEffect(() => {

        if ( ( users && users.length ) || unmounted ) {
            return;
        }

        const params = new URLSearchParams(
            {
                action: 'ut_get_users',
                _ajax_nonce: window.UsersTable.nonce
            }
        );

        const abort = new AbortController();

        fetch(`${window.UsersTable.ajaxUrl}?` + params, { signal: abort.signal })
            .then((response) => response.json())
            .then(response => {
                setUsers(response.data);
                setLoading(false);
            })
            .catch(error => {
                setUsers([]);
                setLoading(false);
            });

        return () => {
            setUnmounted(true); // prevent memory leak
            abort.abort(); // prevent unwanted api calls
        };
    });

    return (
        <>
            <DataTable
                columns={columns}
                data={users}
                progressPending={loading}
                onRowClicked={(row) => setUserId(row.id)}
                customStyles={tableStyles}
            />
        </>
    )
}

export default UserList;
