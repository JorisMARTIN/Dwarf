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
                this.setState({ pages: res.pages });
                this.setState({ frames: res.frames });
                this.setState({ isAdmin: res.isadmin })
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
                        {pages.length > 0 ?           
                            pages.map((page, i) => (
                                <ComicPage key={i} {...page} userIsAdmin={isAdmin} />
                            ))
                        :
                            <h2>Il n'y a aucune BD fini où vous avez participé ...</h2>
                        }
                    </div>
                </div>
                <div className="userPageCreationsCurrent">
                    <h1 className="userPageInfosPagesNotDone">BD en cours de réalisation :</h1>
                    {/* Affichage des planches en cours où l'utilisateur à participé */}
                    <div className="userPageCreationsFinishContainer">
                        {frames.length > 0 ?
                            frames.map((frame, i) => (
                                <ComicFrame key={i} {...frame}/>
                            ))
                        :
                            <h2>Vous n'avez aucun dessin dans une BD non finie ....</h2>
                        }
                    </div>
                </div>
            </div>
        );
    }
}

export default withAuth(UserInfo);