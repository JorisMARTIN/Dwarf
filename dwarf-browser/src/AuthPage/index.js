import { Component } from "react";
import { Redirect } from 'react-router-dom';
import Auth from '../components/AuthHelperMethods';
import './index.css'
import Signup from "./Signup";
import Login from "./Login";

export default class AuthPage extends Component {

    state = {
        loggedIn: Auth.loggedIn()
    }

    render() {
        if (this.state.loggedIn) {
            return <Redirect to='/' />
        } else {
            return (
                <div className="authPageMain">
                    <h1 className="authPageTitle">Authentification</h1>
                    <p className="authPageDescri">Connecte-toi ou inscrit-toi pour dessiner</p>
                    <div className="authPage">
                        <Login />
                        <Signup />
                    </div>
                </div>
            );
        }
    }
}
