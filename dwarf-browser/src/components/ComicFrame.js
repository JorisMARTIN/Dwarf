import React from 'react';
import { Redirect } from 'react-router-dom';
import Auth from './AuthHelperMethods';

export default class ComicFrame extends React.Component {
    constructor(props) {
        super(props);
    }

    state = {
        fullscreen: false
    }

    async componentDidMount() {
        const image = new Image();
        image.src = 'https://dev-dwarf.jorismartin.fr' + this.props.image;
        image.onload = () => {
            ctx.drawImage(image, 0, 0);
        }
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
                        <canvas onClick={this.toggleFullscreen} className="homePlancheImg"/>
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