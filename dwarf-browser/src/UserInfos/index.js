import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import withAuth from '../components/withAuth';

class UserInfo extends Component {

    state = {
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
                this.setState({isAdmin: res.isadmin})
            } else {
                alert(res.message);
            }
        });

    }

    render(){
        const {isAdmin, userInfos} = this.state;
        return(
            <div className="userPage">
                <div className="userPageInfos">
                    <h1 className="userPageInfosName">Name : {userInfos?.nickname}</h1>
                    <ul className="userPageInfosOthers">
                        <li>Id : {userInfos?.id}</li>
                        <li>Email : {userInfos?.email}</li>
                        <li>Account creation date : {userInfos?.creationDate}</li>
                        <li>Birthdate : {userInfos?.birthdate}</li>
                        <li>Account type : {isAdmin ? "Admin" : "normal"}</li>
                    </ul>
                </div>
                <div className="userPageCreations">
                    {/*A imaginer : l'affichage des différentees planches de l'utilisateur*/}
                </div>
            </div>
        );
    }
}

export default withAuth(UserInfo);