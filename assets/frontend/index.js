/**
 * WordPress dependencies
 */
import { render, useEffect, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import SingleUser from './components/single-user';
import UserList from './components/users-lists';

/**
 * Frontend app
 *
 * @returns {Component} Main app component
 */
const App = () => {

    const [ userId, setUserId ] = useState('');

    const getUserIdByUrl = () => {
        const id = parseInt(window.location.hash.replace('#', ''));
        return ! isNaN( id ) ? id : '';
    }

    useEffect(() => {

        const id = getUserIdByUrl();
        if ( userId !== id ) {
            setUserId( id );
        }

        const updateUserId = (e) => {
            setUserId( getUserIdByUrl() );
        };

        window.addEventListener( 'popstate', updateUserId );
        return () => {
            window.removeEventListener( 'popstate', updateUserId );
        }
    });

    useEffect( () => {

        if ( !userId || getUserIdByUrl() === userId ) {
            return;
        }

        const url = new URL( window.location.href );
        const newHash = !!userId ? `#${userId}` : '';

        if ( newHash !== window.location.hash ) {
            url.hash = newHash;
        }

        if ( window.location.href !== url.href ) {
            window.history.pushState( {type: 'manual'}, '', url );
        }

    }, [ userId ] );

    const childProps = { userId, setUserId };

    return (
        <div>
            {!!userId ? <SingleUser {...childProps}/> : <UserList {...childProps}/>}
        </div>
    )
}
render(<App />, document.getElementById('app'));
