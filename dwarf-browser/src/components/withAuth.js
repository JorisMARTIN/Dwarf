import { Component } from 'react';
import { Redirect, withRouter } from 'react-router-dom';
import Auth from './AuthHelperMethods';

//adapted from https://github.com/jinolacson/login-jwt-react-php/

/* A higher order component is frequently written as a function that returns a class. */
export default function withAuth(AuthComponent) {
    class AuthWrapped extends Component {

        state = {
            loggedIn: Auth.loggedIn()
        }

        render() {
            if (this.state.loggedIn) {
                return <AuthComponent {...this.props} />
            } else {
                console.log(this.props);
                return <Redirect to={'/auth?' + this.props.location.pathname.substring(1)} />
            }
        }
    }

    return withRouter(AuthWrapped);
}
