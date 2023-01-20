import { useState, useEffect } from '@wordpress/element';
import { Spinner } from '@wordpress/components';
import DataTable from 'react-data-table-component';

const columns = window.UsersTable.tableColumns.map( column => ({...column, selector: row => row[column.id] }) );
const paginationOptions = {
    noRowsPerPage: true
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
                pagination
                paginationComponentOptions={paginationOptions}
                onRowClicked={(row) => setUserId(row.id)}
            />
        </>
    )
}

export default UserList;
