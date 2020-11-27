import { Component } from "react";
import { Redirect, Link } from 'react-router-dom';
import Auth from '../components/AuthHelperMethods';
import './index.css'

//adapted from https://github.com/jinolacson/login-jwt-react-php/

export default class Login extends Component {
    state = {
        redirectToHome: false,
        email: "",
        password: ""
    };

    /* Fired off every time the use enters something into the input fields */
    _handleChange = (e) => {
        this.setState({ [e.target.name]: e.target.value });
    }

    handleFormSubmit = (e) => {
        e.preventDefault();

        Auth.fetch("login.php", {
            method: 'POST',
            body: JSON.stringify({
                'email': this.state.email,
                'password': this.state.password
            })
        }).then(res => {
            if (res.token !== undefined) {
                Auth.setToken(res.token);
                this.setState({ redirectToHome: true });
            } else {
                alert("Log in failed. Try again.");
            }
        })
    }

    render() {
        if (this.state.redirectToHome) {
            return <Redirect to='/' />
        } else {
            return (
                <div className="authPageLogin">
                    <h1>Login</h1>
                    <form className="authPageLoginForm">
                        <div className="authPageLoginEmail">
                            <label htmlFor="email">Email :</label>
                            <input
                                required
                                id="email"
                                placeholder="Email"
                                name="email"
                                type="email"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageLoginPwd">
                            <label htmlFor="pwd">Password :</label>
                            <input
                                required
                                id="pwd"
                                placeholder="Password"
                                name="password"
                                type="password"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageLoginBottom">
                            <Link className="authPageLoginForgotPwd" to="/">Forgot password</Link>
                            <button onClick={this.handleFormSubmit}>Log in</button>
                        </div>
                    </form>
                </div>
            );
        }
    }
}
