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
            <div className="authPageSignup">
                <h1>Signup</h1>
                <form className="authPageSignupForm">
                    <div className="authPageSignupFirstDiv">
                        <div className="authPageSignupUserName">
                            <label htmlFor="username">Username :</label>
                            <input
                                id="name"
                                placeholder="Pseudo"
                                name="name"
                                type="text"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupDate">
                            <label htmlFor="date">Born date :</label>
                            <input
                                id="date"
                                name="date"
                                type="date"
                                onChange={this._handleChange}
                            />
                        </div>
                    </div>
                    <div className="authPageSignupSecondDiv">
                        <div className="authPageSignupEmail">
                            <label htmlFor="email">Email :</label><br></br>
                            <input
                                id="email"
                                placeholder="Email"
                                name="email"
                                type="email"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupConfEmail">
                            <label htmlFor="email2">Confirm Email :</label><br></br>
                            <input
                                id="email2"
                                placeholder="Email"
                                name="email"
                                type="email"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupPwd">
                            <label htmlFor="pwd">Password :</label><br></br>
                            <input
                                id="pwd"
                                placeholder="Password"
                                name="password"
                                type="password"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupPwd2">
                            <label htmlFor="pwd2">Confirm Password :</label><br></br>
                            <input
                                id="pwd2"
                                placeholder="Confirm Password"
                                name="password"
                                type="password"
                                onChange={this._handleChange}
                            />
                        </div>
                    </div>
                    <button onClick={this.handleFormSubmit}>Sign up</button>
                </form>
            </div>
        );
    }
}
