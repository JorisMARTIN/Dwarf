import './App.css';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from 'react-router-dom';
import Home from './Home';
import Browse from './Browse';
import Draw from './Draw';
import InitDrawing from './InitDrawing';
import AuthPage from './AuthPage';
import E404 from './E404';

export default function App() {
  return (
    <Router>
      <div>
        <ul>
          <li>
            <Link className="link" to="/">Home</Link>
          </li>
          <li>
            <Link className="link" to="/browse">Browse Drawings</Link>
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
      </div>


      <hr />

      <Switch>
        <Route exact path="/">
          <Home />
        </Route>

        <Route path="/browse">
          <Browse />
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

        <Route path="*">
          <E404 />
        </Route>
      </Switch>
    </Router>
  );
}
