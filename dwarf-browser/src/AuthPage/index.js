import { Component } from "react";
import Auth from '../components/AuthHelperMethods';
import './index.css'
import Signup from "./Signup";
import Login from "./Login";

export default class AuthPage extends Component {

    componentWillMount() {
        if (Auth.loggedIn())
            this.props.history.push('/');
    }

    render() {
        return (
            <div>
                <Login />
                <Signup />
            </div>
        );
    }
}
