import React from 'react';
import './index.css';
import Scroller from '../components/Scroller';

export default class Home extends React.Component {

    render() {
        return (
            <div className="homeAll">
                <h1 className="homeTitle">Accueil</h1>
                <div className="homeMain">
                    <Scroller />
                </div>
            </div>
        );
    }
}