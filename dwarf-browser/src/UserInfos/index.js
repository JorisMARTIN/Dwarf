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
            method: 'GET'
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
            <div className="userInformations">
                <h1>Name : {userInfos?.nickname}</h1>
                <hr/>
                <ul>
                    <li>Id : {userInfos?.id}</li>
                    <li>Email : {userInfos?.email}</li>
                    <li>Account creation date : {userInfos?.creationDate}</li>
                    <li>Birthdate : {userInfos?.birthdate}</li>
                    <li>Account type : {isAdmin ? "Admin" : "normal"}</li>
                </ul>
            </div>
        );
    }
}

export default withAuth(UserInfo);