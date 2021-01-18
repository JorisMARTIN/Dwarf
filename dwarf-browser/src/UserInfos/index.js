import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import withAuth from '../components/withAuth';
import ComicPage from '../components/ComicPage/ComicPage.js';
import ComicFrame from '../components/ComicFrame/ComicFrame.js';
import Moderation from '../Moderation';

const UserInfoComponent = ({userInfos, toDelete, toTrue, messageError}) => (
    <div className="userForm">
        {messageError && <p className="authPageMessage">Erreur : {messageError}</p>}
        <div className="userPageInfosOthers">
            <p><span>Email : </span>{userInfos?.email}</p>
            <p><span>Date de naissance : </span>{userInfos?.birthdate}</p>
            <p><span>Date de création : </span>{userInfos?.creationDate}</p>
        </div>
        <div className="userPageInfosBottom">
            <button onClick={toTrue} className="userPageButton">Modifier</button>
            {/* <button onClick={toDelete} className="userPageButton userPageRedButton">Supprimer le compte</button> */}
        </div>
    </div>
)

const UserFormComponent = ({save,toDelete,toFalse,unChange, messageError})=> (
    <div className="userForm">
        {messageError && <p className="authPageMessage">Erreur : {messageError}</p>}
        <p id="obli"><span>*</span> : Champ obligatoire</p>
        <div className="userFormEmail">
            <label htmlFor="pseudoUser">Pseudo :</label>
            <input
                id="pseudoUser"
                placeholder="Pseudo"
                name="pseudoUser"
                type="text"
                onChange={unChange}
            />
        </div>
        <div className="userFormEmail">
            <label htmlFor="emailUser">E-mail :</label>
            <input
                id="emailUser"
                placeholder="Email"
                name="emailUser"
                type="email"
                onChange={unChange}
            />
        </div>
        <div className="userFormBirthdate">
            <label htmlFor="dateUser">Date de naissance :</label>
            <input
                id="dateUser"
                placeholder="dd-mm-yyyy"
                name="dateUser"
                type="text"
                onChange={unChange}
            />
        </div>
        <div className="userFormPwd">
            <label htmlFor="pwdUser">Mot de passe <span>*</span> :</label>
            <input
                required
                id="pwdUser"
                placeholder="Mot de passe"
                name="passwordUser"
                type="password"
                onChange={unChange}
            />
        </div>
        <div className="userFormPwdConfirm">
            <label htmlFor="pwdConfirmUser">Confirmation du mot de passe <span>*</span> :</label>
            <input
                required
                id="pwdConfirmUser"
                placeholder="Confirmation du mot de passe"
                name="passwordConfirmUser"
                type="password"
                onChange={unChange}
            />
        </div>
        <div className="userPageInfosBottom">
            <div>
                <button onClick={toFalse} className="userPageButton userPageRedButton">Annuler</button>
                <button onClick={save} className="userPageButton">Enregistrer</button>
            </div>
            <button onClick={toDelete} className="userPageButton">Supprimer le compte</button>
        </div>
    </div>
)

const UserPagesComponent = ({pages, isAdmin}) => (
    <article className="userPageCreationsFinish">
        <div className="userPageTop">
            <h1 className="userPageInfosPagesDone">BD publiées</h1>
        </div>
        {/* Afffichage des planches fini où l'utilisateur à participé */}
        <div className="userPageCreationsFinishContainer">
            {pages.length > 0 ?
                pages.map((page, i) => (
                    <ComicPage key={i} {...page} userIsAdmin={isAdmin} />
                ))
                :
                <h2 className="userPageCreationsFinishMessage">Il n'y a aucune BD terminée à laquelle vous avez participé</h2>
            }
        </div>
    </article>
)

const UserFramesComponent = ({frames}) => (
    <article className="userPageCreationsCurrent">
        <div className="userPageTop">
            <h1 className="userPageInfosPagesNotDone">BD en cours de réalisation</h1>
        </div>
        {/* Affichage des planches en cours où l'utilisateur à participé */}
        <div className="userPageCreationsFinishContainer">
            {frames.length > 0 ?
                frames.map((frame, i) => (
                    <ComicFrame key={i} {...frame} />
                ))
                :
                <h2 className="userPageCreationsFinishMessage">Vous n'avez aucun dessin dans une BD en cours de réalisation</h2>
            }
        </div>
    </article>
)

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
        pseudoUser: "",
        modify : false,
        messageError : "",
        section : 0 //section 0 => User information || 1 => Pages done || 2 => Frames done
    }

    _handleChange = (e) => {
        this.setState({ [e.target.name]: e.target.value });
    }
    
    handleAccount = (action) => {
        this.setState({ messageError: "" });
        Auth.fetch("account.php", {
            method: "POST",
            body: JSON.stringify({
                pseudo: this.state.pseudoUser,
                email: this.state.emailUser,
                date: this.state.dateUser,
                password: this.state.passwordUser,
                passwordConfirm: this.state.passwordConfirmUser,
                action: action
            })
        }).then(res => {
            if (!res.success) {
                this.setState({messageError: res.messageError});
            } else {
                this.callUser();
                this.setState({ modify: false });
            }
        })
    }
    

    // Get user informations
    callUser() {
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
                    isAdmin: res.isadmin
                })
            } else {
                alert(res.message);
            }
        });
    }

    componentDidMount() {
        this.callUser();
    }

    render() {
        return (
            <div className="userPage">
                <section className="userPageMenu">
                    <button className="userPageMenuLink" onClick={() => this.setState({section : 0})}>Compte</button>
                    <button className="userPageMenuLink" onClick={() => this.setState({section : 1})}>BD publiées</button>
                    <button className="userPageMenuLink" onClick={() => this.setState({ section: 2})}>Cases dessinées</button>
                    {this.state.isAdmin && <button className="userPageMenuLink" onClick={() => this.setState({ section: 3})}>Modération</button>}
                </section>
                <section className="userPageDisplay">
                    {/* Comment on gère les fonction dans les composant ??? A FAIRE ! */}
                    {this.state.section === 0 &&
                        <article className="userPageInfos">
                            <div className="userPageTop">
                                <h1 className="userPageInfosTopName">{this.state.userInfos?.nickname}</h1>
                                {this.state.isAdmin && <p className="userPageInfosTopIsadmin">Modérateur</p>}
                            </div>
                            {!this.state.modify ?
                                <UserInfoComponent
                                    userInfos={this.state.userInfos}
                                    toDelete={() => this.handleAccount("delete")}
                                    toTrue={()=>this.setState({modify: true})}
                                    messageError={this.state.messageError}
                                />
                            :
                                <UserFormComponent
                                    save={() => this.handleAccount("save")}
                                    toDelete={() => this.handleAccount("delete")}
                                    toFalse={()=>this.setState({modify: false})}
                                    unChange={this._handleChange}
                                    messageError={this.state.messageError}
                                />
                            }
                        </article>
                    }
                    {this.state.section === 1 && <UserPagesComponent isAdmin={this.state.isAdmin} pages={this.state.pages}/>}
                    {this.state.section === 2 && <UserFramesComponent frames={this.state.frames} />}
                    {this.state.section === 3 && <Moderation />}
                </section>
            </div>
        );
    }
}

export default withAuth(UserInfo);