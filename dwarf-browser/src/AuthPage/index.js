import { Component } from "react";
import { Redirect } from 'react-router-dom';
import Auth from '../components/AuthHelperMethods';
import './index.css'
import Signup from "./Signup";
import Login from "./Login";

export default class AuthPage extends Component {

    state = {
        redirectToHome: Auth.loggedIn()
    }

    render() {
        if(this.state.redirectToHome) {
            return <Redirect to='/' />
        } else {
            return (
                <div>
                    <Login />
                    <Signup />
                </div>
            );
        }
    }
}
