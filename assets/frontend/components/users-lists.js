/**
 * Users table component
 *
 * @param {int} userId Current user ID
 * @param {function} setUserId Callback for set new user id
 * @returns {Component} Render UserList component
 */
const UserList = ({ userId, setUserId }) => {

    return (
        <table className="users-table">
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Name</td>
                    <td>Username</td>
                </tr>
            </thead>
            <tbody>
                <tr onClick={() => setUserId(1)}>
                    <td>1</td>
                    <td>One</td>
                    <td>one</td>
                </tr>
                <tr onClick={() => setUserId(2)}>
                    <td>2</td>
                    <td>Two</td>
                    <td>two</td>
                </tr>
                <tr onClick={() => setUserId(3)}>
                    <td>3</td>
                    <td>Three</td>
                    <td>three</td>
                </tr>
            </tbody>
        </table>
    )
}

export default UserList;
