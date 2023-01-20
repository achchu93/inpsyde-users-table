/**
 * Single User Component
 *
 * @param {int} userId Current user ID
 * @param {function} setUserId Callback for set new user id
 * @returns {Component} Render SingleUser component
 */
const SingleUser = ({ userId, setUserId }) => {

    return (
        <div>I am a single user with id #{userId}</div>
    )
}
export default SingleUser;
