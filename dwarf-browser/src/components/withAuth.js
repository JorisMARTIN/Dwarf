import { Component } from 'react';
import { Redirect } from 'react-router-dom';
import Auth from './AuthHelperMethods';

//adapted from https://github.com/jinolacson/login-jwt-react-php/

/* A higher order component is frequently written as a function that returns a class. */
export default function withAuth(AuthComponent) {
    return class AuthWrapped extends Component {

        state = {
            loggedIn: Auth.loggedIn()
        }

        render() {
            if (this.state.loggedIn) {
                return <AuthComponent {...this.props} />
            } else {
                return <Redirect to='/auth' />
            }
        }
    }
}
