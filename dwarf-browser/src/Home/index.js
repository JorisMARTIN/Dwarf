import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';

function ComicPage(props) {
    return (
        <div className="homePlanche">
            <div className="homePlancheTop">
                <img className="homePlancheImg" src={props.name} alt={props.name}></img>
                <div>
                    <h3 className="homeName">Titre : {props.name}</h3>
                    <p className="homeDescri">Description :<br></br>
                        {props.desc}</p>
                    <p className="homeMode">Mode :<br></br>
                        {props.mode}</p>
                </div>
            </div>
            <div className="homePlancheBottom">
                <button type="button">Like</button> 
                <button type="button">Dislike</button> 
            </div>
        </div>
    );
}

export default class Home extends Component {
    state = {
        pages: null,
    }

    componentDidMount() {
        Auth.fetch("home.php", { method: "GET" })
            .then(res => {
                this.setState({
                    pages: res.pages.map((page, i) =>
                        <ComicPage key={i} name={page.name} desc={page.description} mode={page.gamemode}/>
                    )
                });
            })
    }

    render() {
        return (
            <div className="homeAll">
                <h1 className="homeTitle">Home</h1>
                <div className="homeMain">
                    <div className="homeDivPlanche">
                        {this.state.pages}
                    </div>
                </div>
            </div>
        );
    }
}