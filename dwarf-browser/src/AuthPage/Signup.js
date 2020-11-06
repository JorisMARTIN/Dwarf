import { Component } from "react";
import Auth from '../components/AuthHelperMethods';
import './index.css'

//adapted from https://github.com/jinolacson/login-jwt-react-php/

export default class Signup extends Component {
    state = {
        username: "",
        password: ""
    };

    _handleChange = (e) => {
        this.setState({ [e.target.name]: e.target.value });
    }

    handleFormSubmit = (e) => {
        e.preventDefault();

        Auth.signup(this.state.username, this.state.password)
            .then(res => {
                if (res === true) {
                    alert("Sign up failed");
                } else {
                    alert("Sign up successful, you may now log in.");
                }
            })
            .catch(err => {
                alert(err);
            })
    }

    render() {
        return (
            <div>
                <h1>Signup</h1>
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
                    <button onClick={this.handleFormSubmit}>Sign up</button>
                </form>
            </div>
        );
    }
}
