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
          <Link className="dwarf" to="/">Dwarf</Link>
          
          <div className="componentsMenu">
            <Link className="link" to="/">News</Link>
            <Link className="link" to="/draw">Draw</Link>
            <Link className="link" to="/init">New Page</Link>
            <Link className="link" to="/user">User</Link>
          </div>

          <div className="logComponent">
            {this.state.logged ? <button className="log" onClick={this.logout}>Log out</button> 
            : <Link className="log" to="/auth">Log in / Sign up</Link>}
          </div>
        </div>

        <Switch>
          <Route exact path="/" component={Home} />

          <Route path="/draw" component={Draw} />

          <Route path="/auth" component={AuthPage} />

          <Route path="/init" component={InitDrawing} />

          <Route path="/user" component={UserInfos} />

          <Route path="*">
            <E404 />
          </Route>
        </Switch>
        <div className="footer">
          <p>© DWARF. Content is available under these licenses.</p>
          <div>
            <h3>Infos :</h3>
            <a href="CGU.pdf">Terms of Service</a>
            <a href="Confidentialite.pdf">Confidentiality</a>
          </div>
        </div>
      </Router>
    )
  }
}