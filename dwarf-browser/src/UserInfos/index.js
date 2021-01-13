import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import withAuth from '../components/withAuth';
import ComicPage from '../components/ComicPage/ComicPage.js';
import ComicFrame from '../components/ComicFrame/ComicFrame.js';

class UserInfo extends Component {

    state = {
        pages: [],
        frames: [],
        isAdmin: false,
        userInfos: null,
        emailUser: "",
        passwordUser: "",
        passwordConfirmUser: "",
        dateUser: "",
        modify : false,
        messageError : "",
        section : 0 //section 0 => User information || 1 => Pages done || 2 => Frames done
    }

    _handleChange = (e) => {
        this.setState({ [e.target.name]: e.target.value });
    }
    
//le save marche avec apitester mais pas en real pq ????
    handleAccount = (action) => {
        this.setState({ messageError: "" });
        Auth.fetch("account.php", {
            method: "POST",
            body: JSON.stringify({
                email: this.state.emailUser,
                date: this.state.dateUser,
                password: this.state.passwordUser,
                passwordConfirm: this.state.passwordConfirmUser,
                action: action
            })
        }).then(res => {
            if (!res.success) {
                this.setState({ messageError: res.messageError});
            } else {
                this.setState({section:0});
            }
        })
    }

    // Get user informations
    componentDidMount() {

        Auth.fetch("userinfos.php", {
            method: 'POST'
        }).then(res => {
            if (res.userid) {
                this.setState({
                    userInfos: {
                        id: res.userid,
                        email: res.email,
                        nickname: res.nickname,
                        creationDate: res.creationdate,
                        birthdate: res.birthdate
                    },
                    pages: res.pages,
                    frames: res.frames,
                    isAdmin: res.isadmin })
            } else {
                alert(res.message);
            }
        });
    }

    render() {
        const { pages, frames, isAdmin, userInfos } = this.state;
        return (
            <div className="userPage">
                <section className="userPageMenu">
                    <button className="userPageMenuLink" onClick={() => this.setState({section : 0})}>Compte</button>
                    <button className="userPageMenuLink" onClick={() => this.setState({section : 1})}>BD publié.s</button>
                    <button className="userPageMenuLink" onClick={() => this.setState({section : 2})}>Case déssiné.s</button>
                </section>
                <section className="userPageDisplay">
                    {this.state.section === 0 &&
                        <article className="userPageInfos">
                            <div className="userPageTop">
                                <h1 className="userPageInfosTopName">{userInfos?.nickname}</h1>
                                {isAdmin && <p className="userPageInfosTopIsadmin">Admin</p>}
                            </div>
                            {!this.state.modify ?
                            <div className="userForm">
                                {this.state.messageError && <p className="authPageMessage">Erreur : {this.state.messageError}</p>}
                                <div className="userPageInfosOthers">
                                    <p>Email : {userInfos?.email}</p>
                                    <p>Date de naissance : {userInfos?.birthdate}</p>
                                    <p>Date de création : {userInfos?.creationDate}</p>
                                </div>
                                <div className="userPageInfosBottom">
                                    {!this.state.modify ?
                                        <button onClick={() => this.setState({modify : true}) } className="userPageDeleteAccount">Modifier</button>
                                    : 
                                        <button onClick={() => this.handleAccount("save")} className="userPageDeleteAccount">Enregistrer</button>
                                    }
                                    <button onClick={() => this.handleAccount("delete")} className="userPageDeleteAccount">Supprimer le compte</button>
                                </div>
                            </div>
                            :
                            <div className="userForm">
                                {this.state.messageError && <p className="authPageMessage">Erreur : {this.state.messageError}</p>}
                                <div className="userFormEmail">
                                    <label htmlFor="emailUser">E-mail :</label>
                                    <input
                                        required
                                        id="emailUser"
                                        placeholder="Email"
                                        name="emailUser"
                                        type="email"
                                        onChange={this._handleChange}
                                    />
                                </div>
                                <div className="userFormBirthdate">
                                    <label htmlFor="dateUser">Date de naissance :</label>
                                    <input
                                        required
                                        id="dateUser"
                                        placeholder="dd-mm-yyyy"
                                        name="dateUser"
                                        type="text"
                                        onChange={this._handleChange}
                                    />
                                </div>
                                <div className="userFormPwd">
                                    <label htmlFor="pwdUser">Mot de passe :</label>
                                    <input
                                        required
                                        id="pwdUser"
                                        placeholder="Mot de passe"
                                        name="passwordUser"
                                        type="password"
                                        onChange={this._handleChange}
                                    />
                                </div>
                                <div className="userFormPwdConfirm">
                                    <label htmlFor="pwdConfirmUser">Confirmation du mot de passe :</label>
                                    <input
                                        required
                                        id="pwdConfirmUser"
                                        placeholder="Confirmation du mot de passe"
                                        name="passwordConfirmUser"
                                        type="password"
                                        onChange={this._handleChange}
                                    />
                                </div>
                                <div className="userPageInfosBottom">
                                    {!this.state.modify ?
                                        <button onClick={() => this.setState({ modify: true })} className="userPageButton">Modifier</button>
                                        :
                                        <div>
                                            <button onClick={() => this.setState({ modify: false })} className="userPageButton">Annuler</button>
                                            <button onClick={() => this.handleAccount("save")} className="userPageButton">Enregistrer</button>
                                        </div>
                                    }
                                    <button onClick={() => this.handleAccount("delete")} className="userPageButton">Supprimer le compte</button>
                                </div>
                            </div>
                            }
                        </article>
                    }
                    {this.state.section === 1 &&
                        <article className="userPageCreationsFinish">
                            <div className="userPageTop">
                                <h1 className="userPageInfosPagesDone">BD déjà réalisé :</h1>
                            </div>
                            {/* Afffichage des planches fini où l'utilisateur à participé */}
                            <div className="userPageCreationsFinishContainer">
                                {pages.length > 0 ?
                                    pages.map((page, i) => (
                                        <ComicPage key={i} {...page} userIsAdmin={isAdmin} />
                                    ))
                                    :
                                    <h2>Il n'y a aucune BD fini où vous avez participé ...</h2>
                                }
                            </div>
                        </article>
                    }
                    {this.state.section === 2 &&
                        <article className="userPageCreationsCurrent">
                            <div className="userPageTop">
                                <h1 className="userPageInfosPagesNotDone">BD en cours de réalisation :</h1>
                            </div>
                            {/* Affichage des planches en cours où l'utilisateur à participé */}
                            <div className="userPageCreationsFinishContainer">
                                {frames.length > 0 ?
                                    frames.map((frame, i) => (
                                        <ComicFrame key={i} {...frame} />
                                    ))
                                    :
                                    <h2>Vous n'avez aucun dessin dans une BD non finie ....</h2>
                                }
                            </div>
                        </article>
                    }
                </section>
            </div>
        );
    }
}

export default withAuth(UserInfo);