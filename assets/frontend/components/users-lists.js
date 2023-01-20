/**
 * External dependencies
 */
import DataTable from 'react-data-table-component';

/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

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
const UserList = ({ userId, setUserId }) => {

    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if ( users && users.length ) {
            return;
        }

        const params = new URLSearchParams(
            {
                action: 'ut_get_users',
                _ajax_nonce: window.UsersTable.nonce
            }
        );

        fetch(`${window.UsersTable.ajaxUrl}?` + params)
            .then((response) => response.json())
            .then(response => {
                setUsers(response.data);
                setLoading(false);
            })
            .catch(error => {
                setUsers([]);
                setLoading(false);
            });
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
