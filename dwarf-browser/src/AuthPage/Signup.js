import { Component } from "react";
import Auth from '../components/AuthHelperMethods';
import './index.css'

//adapted from https://github.com/jinolacson/login-jwt-react-php/

export default class Signup extends Component {
    state = {
        email: "",
        password: ""
    };

    _handleChange = (e) => {
        this.setState({ [e.target.name]: e.target.value });
    }

    handleFormSubmit = (e) => {
        e.preventDefault();

        Auth.fetch("signup.php", {
            method: 'POST',
            body: JSON.stringify({
                "email": this.state.email,
                "password": this.state.password
            })
        }).then(res => {
            if (res.success) {
                alert("Sign up successful, you may now log in.");
            } else {
                alert("Sign up failed");
            }
        })
    }

    render() {
        return (
            <div>
                <h1>Signup</h1>
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
                    <button onClick={this.handleFormSubmit}>Sign up</button>
                </form>
            </div>
        );
    }
}
