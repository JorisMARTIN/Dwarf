import { Component } from "react";
import Auth from '../components/AuthHelperMethods';
import './index.css'
import { Redirect } from 'react-router-dom';

//adapted from https://github.com/jinolacson/login-jwt-react-php/

export default class Signup extends Component {
    state = {
        name: "",
        date_day: 1,
        date_month: 1,
        date_year: 2000,
        email: "",
        emailConfirm: "",
        password: "",
        passwordConfirm: "",
        cgu: "off",
        messageError: "",
        redirectToUser: false
    };

    _handleChange = (e) => {
        if (e.target.name === "cgu") this.setState({ [e.target.name]: e.target.checked });
        else this.setState({ [e.target.name]: e.target.value });
    }

    handleFormSubmit = (e) => {
        e.preventDefault();

        const currentYear = new Date().getFullYear();

        let day = this.state.date_day >= 1 && this.state.date_day <= 31 ? '' + this.state.date_day : '01';
        let month = this.state.date_month >= 1 && this.state.date_month <= 12 ? '' + this.state.date_month : '01';
        let year = this.state.date_year > currentYear - 123 && this.state.date_year <= currentYear ? '' + this.state.date_year : '2000';

        if (day.length < 2) day = '0' + day;
        if (month.length < 2) month = '0' + month;
        if (year.length !== 4) year = year.slice(-4).padStart(4, '0');

        Auth.fetch("signup.php", {
            method: 'POST',
            body: JSON.stringify({
                name: this.state.name,
                date: year + '-' + month + '-' + day,
                email: this.state.email,
                emailConfirm: this.state.emailConfirm,
                password: this.state.password,
                passwordConfirm: this.state.passwordConfirm,
                cgu: this.state.cgu
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
                this.setState({ messageError: res.messageError });
            }
        })
    }

    refresh = () => {
        setTimeout(() => { window.location.reload(); }, 400);
    }

    render() {
        if (this.state.redirectToUser) {
            return <Redirect to='/user' />;
        } else {
            const months = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "septembre", "août", "octobre", "novembre", "décembre"].map((month, i) => <option key={i} value={i+1}>{month}</option>);

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
                                <label htmlFor="date_month">Date de naissance :</label>
                                <div>
                                    <input
                                        required
                                        id="date_day"
                                        name="date_day"
                                        type="number"
                                        placeholder="jour"
                                        onChange={this._handleChange}
                                        min="1"
                                        max="31"
                                    />
                                    <select required id="date_month" name="date_month" onChange={this._handleChange}>
                                        {months}
                                    </select>
                                    <input
                                        required
                                        id="date_year"
                                        name="date_year"
                                        type="number"
                                        placeholder="année"
                                        onChange={this._handleChange}
                                        min="1900"
                                        max={new Date().getFullYear()}
                                    />
                                </div>
                            </div>
                            <div className="authPageSignupEmail">
                                <label htmlFor="email">E-mail :</label><br></br>
                                <input
                                    required
                                    id="email"
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
                                <label htmlFor="password">Mot de passe :</label><br></br>
                                <input
                                    required
                                    id="password"
                                    placeholder="Mot de passe"
                                    name="password"
                                    type="password"
                                    onChange={this._handleChange}
                                />
                            </div>
                            <div className="authPageSignupPwd2">
                                <label htmlFor="passwordConfirm">Confirmer mot de passe :</label><br></br>
                                <input
                                    required
                                    id="passwordConfirm"
                                    placeholder="Confirmer mot de passe"
                                    name="passwordConfirm"
                                    type="password"
                                    onChange={this._handleChange}
                                />
                            </div>
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
                            <label htmlFor="cgu">J'ai lu et accepté les <a href={Auth.url + "/docs/CGU.pdf"} >Conditions Générales d'Utilisations</a></label>
                        </div>
                        <button onClick={this.handleFormSubmit}>Créer un compte</button>
                    </form>
                </div>
            );
        }
    }
}
