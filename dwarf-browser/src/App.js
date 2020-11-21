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

export default class App extends Component {

  state = {
    logged: Auth.loggedIn(),
  };

  logout = (e) => {
    e.preventDefault();
    Auth.logout();
    this.setState({ logged: Auth.loggedIn() });
  };

  render() {
    return (
      <Router>
        <div className="menu">
          <Link className="dwarf" to="/">Draw With Amazing Random Friends</Link>
          <ul className="menuNav">
            <li>
              <Link className="link" to="/draw">Draw</Link>
            </li>
            <li>
              <Link className="link" to="/init">Play</Link>
            </li>
          </ul>

          {this.state.logged && <button
            className="logout"
            onClick={this.logout}>Log out
          </button>}
        </div>

        <Switch>
          <Route exact path="/" component={Home} />

          <Route path="/draw" component={Draw} />

          <Route path="/auth" component={AuthPage} />

          <Route path="/init" component={InitDrawing} />

          <Route path="*">
            <E404 />
          </Route>
        </Switch>
      </Router>
    )
  }
}