import React from 'react';
import { Redirect } from 'react-router-dom';
import Auth from './AuthHelperMethods';

export default class ComicFrame extends React.Component {
    state = {
        fullscreen: false
    }
    /* Agrandissement Page */

    toggleFullscreen = () => {
        if (this.state.fullscreen) {
            this.setState({ fullscreen: false });
        } else {
            this.setState({ fullscreen: true });
        }
    }

    render() {
        if (this.state.redirectVote) return <Redirect to="/auth" />
        else return (
            <div className="homePlancheWrapper">
                <div className="homePlancheNone">
                    <div className="homePlancheTop">
                        <img className="homePlancheImg" src={Auth.url+this.props.images}> </img>
                        <div className="homePlancheTopInfos">
                            <textarea readOnly disabled className="homeName" value={this.props.name} />
                            <textarea readOnly disabled className="homeDescri" value={this.props.description} />
                            <p className="homeMode">{this.props.gamemode}</p>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}