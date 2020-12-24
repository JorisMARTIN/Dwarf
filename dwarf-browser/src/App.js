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
//import UserInfos from './UserInfos';

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
            <img className="dwarfLogo" src="https://dev-dwarf.jorismartin.fr/icons/dwarf.svg"></img>
            <Link className="dwarf" to="/">Dwarf</Link>
          </div>
          
          <div className="componentsMenu">
            <Link className="link" to="/">Accueil</Link>
            <Link className="link" to="/draw">Dessiner !</Link>
            <Link className="link" to="/init">Nouvelle BD</Link>
            <Link className="link" to="/help">Aide</Link>
            {/* this.state.logged ? <Link className="link" to="/user">User</Link> : ""*/}
          </div>
          <a className="link" target="_blank" href="https://www.frama.link/dwarfExperience">Ton avis nous intéresse !</a>
          <div className="logComponent">
            {this.state.logged ? <button className="log" onClick={this.logout}>Déconnexion</button> 
            : <Link className="log" to="/auth">Connexion / Inscription</Link>}
          </div>
        </div>

        <Switch>
          <Route exact path="/" component={Home} />

          <Route path="/draw" component={Draw} />

          <Route path="/auth" component={AuthPage} />

          <Route path="/init" component={InitDrawing} />

          <Route path="/help" component={Help} />

          <Route path="/user" component={UserInfos} />

          <Route path="*">
            <E404 />
          </Route>
        </Switch>

        <div className="footer">
          <p>Dwarf</p>
          <p>{new Date().getFullYear()} &#169; Tous droits réservés.</p>
          {/* <div>
            <h3>Information :</h3>
            <a href="CGU.pdf">Condition d'utilisation</a>
            [<a href="Confidentialite.pdf">Confidentialité</a>
          </div> */}
        </div>
      </Router>
    )
  }
}