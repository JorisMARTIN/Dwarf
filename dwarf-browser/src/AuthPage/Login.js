import { Component } from "react";
import { Redirect } from 'react-router-dom';
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
        /* Here is where all the login logic will go. Upon clicking the login button,
        we would like to utilize a login method that will send our entered credentials
        over to the server for verification. Once verified, it should store your token
        and send you to the protected route. */
        Auth.login(this.state.email, this.state.password)
            .then(res => {
                if (res === false) {
                    return alert("Log in failed. Try again.");
                }
                this.setState({redirectToHome: true});
            })
    }

    render() {
        if(this.state.redirectToHome) {
            return <Redirect to='/' />
        } else {
            return (
                <div>
                    <h1>Login</h1>
                    <form>
                        <input
                            placeholder="Email"
                            name="email"
                            type="email"
                            onChange={this._handleChange}
                        />
                        <input
                            placeholder="Password"
                            name="password"
                            type="password"
                            onChange={this._handleChange}
                        />
                        <button onClick={this.handleFormSubmit}>Log in</button>
                    </form>
                </div>
            );
        }
    }
}
