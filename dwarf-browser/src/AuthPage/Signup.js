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
                "name": this.state.name,
                "date": this.state.date,
                "email": this.state.email,
                "emailConfirm": this.state.email,
                "password": this.state.password,
                "passwordConfirm": this.state.password
            })
        }).then(res => {
            alert(res.message);
        })
    }

    render() {
        return (
            <div className="authPageSignup">
                <h1>Create a account</h1>
                <form className="authPageSignupForm">
                    <div className="authPageSignupDiv">
                        <div className="authPageSignupUserName">
                            <label htmlFor="username">Username :</label>
                            <input
                                required
                                id="username"
                                placeholder="Pseudo"
                                name="name"
                                type="text"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupDate">
                            <label htmlFor="date">Birthdate :</label>
                            <input
                                required
                                id="date"
                                name="date"
                                type="date"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupEmail">
                            <label htmlFor="emailS">Email :</label><br></br>
                            <input
                                required
                                id="emailS"
                                placeholder="Email"
                                name="email"
                                type="email"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupConfEmail">
                            <label htmlFor="email2">Confirm Email :</label><br></br>
                            <input
                                required
                                id="email2"
                                placeholder="Confirm Email"
                                name="emailConfirm"
                                type="email"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupPwd">
                            <label htmlFor="pwdS">Password :</label><br></br>
                            <input
                                required
                                id="pwdS"
                                placeholder="Password"
                                name="password"
                                type="password"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupPwd2">
                            <label htmlFor="pwd2">Confirm Password :</label><br></br>
                            <input
                                required
                                id="pwd2"
                                placeholder="Confirm Password"
                                name="passwordConfirm"
                                type="password"
                                onChange={this._handleChange}
                            />
                        </div>
                    </div>
                    <button onClick={this.handleFormSubmit}>Create account</button>
                </form>
            </div>
        );
    }
}
