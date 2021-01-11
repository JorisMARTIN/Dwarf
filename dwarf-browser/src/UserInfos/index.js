import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import withAuth from '../components/withAuth';
import ComicPage from '../components/ComicPage.js';
import ComicFrame from '../components/ComicFrame.js';

class UserInfo extends Component {

    state = {
        pages: [],
        frames: [],
        isAdmin: false,
        userInfos: null,
    }

    // Get user informations
    componentDidMount(){

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
                    }
                });
                this.setState({pages: res.pages});
                this.setState({ isAdmin: res.isadmin})
            } else {
                alert(res.message);
            }
        });

    }

    render(){
        const {pages, frames, isAdmin, userInfos} = this.state;
        return(
            <div className="userPage">
                <div className="userPageInfos">
                    <h1 className="userPageInfosName">Informations :</h1>
                    {/* C'est une idée ... <form>
                        <div className="">
                            <label htmlFor="name">Nom :</label>
                            <input
                                required
                                id="name"
                                placeholder={userInfos?.nickname}
                                name="name"
                                type="text"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="">
                            <label htmlFor="email">E-mail :</label>
                            <input
                                required
                                id="email"
                                placeholder={userInfos?.email}
                                name="email"
                                type="email"
                                onChange={this._handleChange}
                            />
                        </div>
                        <div className="">
                            <button >Sauvegarder</button>
                            {/* <button onClick={this.handleFormSubmit}>Sauvegarder</button> /}
                        </div>
                    </form> */}
                    <ul className="userPageInfosOthers">
                        <li>Nom : {userInfos?.nickname}</li>
                        <li>Id : {userInfos?.id}</li>
                        <li>Email : {userInfos?.email}</li>
                        <li>Date de création : {userInfos?.creationDate}</li>
                        <li>Anniversaire : {userInfos?.birthdate}</li>
                        <li>Type de compte : {isAdmin ? "Admin" : "Normal"}</li>
                    </ul>
                </div>
                <div className="userPageCreationsFinish">
                    <h1 className="userPageInfosPagesDone">BD déjà réalisé :</h1>
                    {/* Afffichage des planches fini où l'utilisateur à participé */}
                    <div className="userPageCreationsFinishContainer">
                        {pages.map((page, i) => (
                            <ComicPage key={i} {...page} userIsAdmin={isAdmin} />
                        ))}
                    </div>
                </div>
                <div className="userPageCreationsCurrent">
                    <h1 className="userPageInfosPagesNotDone">BD en cours de réalisation :</h1>
                    {/* Affichage des planches en cours où l'utilisateur à participé */}
                    <div className="userPageCreationsFinishContainer">
                        {console.log(frames)}
                        {frames.map((frame, i) => (
                            // J'arrive pas à savoir pq ça s'affiche pas ... À continuer 
                            <ComicFrame key={i} {...frame}/>
                        ))}
                    </div>
                </div>
            </div>
        );
    }
}

export default withAuth(UserInfo);