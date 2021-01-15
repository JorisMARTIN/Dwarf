import { Component } from "react";
import Auth from '../components/AuthHelperMethods';
import './index.css'
import { Redirect } from 'react-router-dom';

//adapted from https://github.com/jinolacson/login-jwt-react-php/

export default class Signup extends Component {
    state = {
        name : "",
        date: "",
        email: "",
        emailConfirm: "",
        password: "",
        passwordConfirm: "",
        messageError : "",
        redirectToUser : false
    };

    _handleChange = (e) => {
        this.setState({ [e.target.name]: e.target.value });
    }

    handleFormSubmit = (e) => {
        e.preventDefault();

        Auth.fetch("signup.php", {
            method: 'POST',
            body: JSON.stringify({
                name : this.state.name,
                date : this.state.date,
                email : this.state.email,
                emailConfirm : this.state.emailConfirm,
                password : this.state.password,
                passwordConfirm : this.state.passwordConfirm
            })
        }).then(res => {
            if (res.success) {
                Auth.fetch("login.php", {
                    method: 'POST',
                    body: JSON.stringify({
                        'email': this.state.email,
                        'password': this.state.password
                    })
                }).then(res => {
                    if (res.token !== undefined) {
                        Auth.setToken(res.token);
                        this.setState({ redirectToUser: true });
                        // Refresh page afer login for update App component
                        this.refresh();
                    } else {
                        this.setState({ messageError: res.messageError });
                    }
                })
            } else {
                this.setState({ messageError : res.messageError});
            }
        })
    }

    refresh = () =>{
        setTimeout(() => { window.location.reload(); }, 400);
    }

    render() {
        if (this.state.redirectToUser) {
            return <Redirect to='/' />;
        } else {
            return (
            <div className="authPageSignup">
                <h1>Créer un compte</h1>
                {this.state.messageError && <p className="authPageMessage">Erreur : {this.state.messageError}</p>}
                <form className="authPageSignupForm">
                    <div className="authPageSignupDiv">
                        <div className="authPageSignupUserName">
                            <label htmlFor="username">Pseudo :</label>
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
                            <label htmlFor="date">Date de naissance :</label>
                            <input
                                required
                                id="date"
                                name="date"
                                type="date"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupEmail">
                            <label htmlFor="emailS">E-mail :</label><br></br>
                            <input
                                required
                                id="emailS"
                                placeholder="E-mail"
                                name="email"
                                type="email"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupConfEmail">
                            <label htmlFor="email2">Confirmer e-mail :</label><br></br>
                            <input
                                required
                                id="email2"
                                placeholder="Confirmer e-mail"
                                name="emailConfirm"
                                type="email"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupPwd">
                            <label htmlFor="pwdS">Mot de passe :</label><br></br>
                            <input
                                required
                                id="pwdS"
                                placeholder="Mot de passe"
                                name="password"
                                type="password"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupPwd2">
                            <label htmlFor="pwd2">Confirmer mot de passe :</label><br></br>
                            <input
                                required
                                id="pwd2"
                                placeholder="Confirmer mot de passe"
                                name="passwordConfirm"
                                type="password"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="authPageSignupCGU">
                            <input
                                required
                                id="cgu"
                                placeholder="Confirmer mot de passe"
                                name="cgu"
                                type="checkbox"
                                onChange={this._handleChange}
                            />
                            <label htmlFor="cgu">J'ai lu et accépté les <a href="/docs/Redaction/CGU.pdf" >Conditions Générale d'Utilisation</a></label>
                        </div>
                    </div>
                    <button onClick={this.handleFormSubmit}>Créer un compte</button>
                </form>
            </div>
            );
        }
    }
}
