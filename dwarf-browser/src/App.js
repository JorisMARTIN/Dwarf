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
import Canvas from './Canvas';
import Auth from './components/AuthHelperMethods';

export default class App extends Component {

  state = {
    logged: Auth.loggedIn,
  };

  logout = (e) => {
    e.preventDefault();
    this.setState({logged: false});
    Auth.logout();
  };

  render() {
    return (

      <Router>
        <div className="appMenu">
          <ul>
            <li>
              <Link className="link" to="/">Home</Link>
            </li>
            <li>
              <Link className="link" to="/draw">Draw !</Link>
            </li>
            <li>
              <Link className="link" to="/init">Initiate a drawing</Link>
            </li>
            <li>
              <Link className="link" to="/auth">Log in / sign up</Link>
            </li>
          </ul>

          {this.state.logged && <button className="logout" onClick={this.logout}>Log out</button>}
        </div>

        <Switch>
          <Route exact path="/">
            <Home />
          </Route>

          <Route path="/draw">
            <Draw />
          </Route>

          <Route path="/auth">
            <AuthPage />
          </Route>

          <Route path="/init">
            <InitDrawing />
          </Route>

          <Route path={"/canvas/:id"} component={Canvas}/>

          <Route path="*">
            <E404 />
          </Route>
        </Switch>
      </Router>
    )
  }
}