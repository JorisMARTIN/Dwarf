import React from 'react';
import Auth from '../AuthHelperMethods';
import './index.css';

export default class ComicFrame extends React.Component {
    render() {
        return (
            <div className="homePlancheWrapper">
                <div className="homePlancheNone">
                    <div className="homePlancheTop">
                        <img className="homePlancheImg" src={Auth.url+this.props.images}/>
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