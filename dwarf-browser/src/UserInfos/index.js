import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import withAuth from '../components/withAuth';
import ComicPage from '../components/ComicPage.js';
import ComicFrame from '../components/ComicFrame.js';

class UserInfo extends Component {

    state = {
        pages : [],
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
        const {pages, isAdmin, userInfos, frames} = this.state;
        return(
            <div className="userPage">
                <div className="userPageInfos">
                    <h1 className="userPageInfosName">Name : {userInfos?.nickname}</h1>
                    <ul className="userPageInfosOthers">
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
                        {frames.map((frame, i) => (
                            <ComicFrame key={i} {...frame}/>
                        ))}
                    </div>
                </div>
            </div>
        );
    }
}

export default withAuth(UserInfo);