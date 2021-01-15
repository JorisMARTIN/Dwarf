import './App.css';
import { Component } from 'react';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from 'react-router-dom';
import Home from './Home';
import Draw from './Draw';
import InitDrawing from './InitDrawing';
import AuthPage from './AuthPage';
import E404 from './E404';
import Auth from './components/AuthHelperMethods';
import Help from './Help';
import UserInfos from './UserInfos';

export default class App extends Component {

  state = {
    logged: Auth.loggedIn(),
  };

  logout = (e) => {
    e.preventDefault();
    Auth.logout();
    this.setState({ logged: Auth.loggedIn() });
    window.location.reload();
  };

  render() {
    return (
      <Router>
        <div className="menu">
          <div className="dwarfDiv">
            <img className="dwarfLogo" src={Auth.url + "/icons/dwarf.svg"} alt="logo" />
            <Link className="dwarf" to="/">Dwarf</Link>
          </div>

          <div className="componentsMenu">
            <Link className="link" to="/"><img src={Auth.url + "/icons/Accueil.svg"} alt="" /><span>Accueil</span></Link>
            <Link className="link" to="/draw"><img src={Auth.url + "/icons/Dessiner.svg"} alt="" /><span>Dessiner !</span></Link>
            <Link className="link" to="/init"><img src={Auth.url + "/icons/NouvelleBD.svg"} alt="" /><span>Nouvelle BD</span></Link>
            <Link className="link" to="/help"><img src={Auth.url + "/icons/Aide.svg"} alt="" /><span>Aide</span></Link>
          </div>

          {this.state.logged ? 
          <div className="logComponent">
            <img className="logButton" src={Auth.url + "/icons/Compte.svg"} alt="" />
            <div className="logCoponentDropDown">
              <div>
                <Link className="logLink" to="/user">Compte</Link>
                <button className="logLink" onClick={this.logout}>Déconnexion</button>
              </div>
            </div>
          </div>

            : <Link className="log" to="/auth"><img src={Auth.url + "/icons/Compte.svg"} alt="" /><span>Connexion / Inscription</span></Link>
          }
        </div>

        <Switch>
          <Route exact path="/" component={Home} />

          <Route path="/draw" component={Draw} />

          <Route path="/auth" component={AuthPage} />

          <Route path="/init" component={InitDrawing} />

          <Route path="/help" component={Help} />

          <Route path="/user" component={UserInfos} />

          <Route path="*" component={E404} />
        </Switch>

        <div className="footer">
          <p>Dwarf</p>
          <p>{new Date().getFullYear()} &#169; Tous droits réservés.</p>
          <a href={Auth.url + "/cgu.pdf"}>Conditions d'utilisations</a>
        </div>
      </Router>
    )
  }
}