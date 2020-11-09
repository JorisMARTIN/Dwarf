import { Component } from "react";
import Auth from '../components/AuthHelperMethods';
import './index.css'

//adapted from https://github.com/jinolacson/login-jwt-react-php/

export default class Login extends Component {
    state = {
        username: "",
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
        Auth.login(this.state.username, this.state.password)
            .then(res => {
                if (res === false) {
                    return alert("Log in failed. Try again.");
                }
                this.props.history.replace('/');
            })
            .catch(err => {
                alert(err);
            })
    }

    render() {
        return (
            <div>
                <h1>Login</h1>
                <form>
                    <input
                        placeholder="Username"
                        name="username"
                        type="text"
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