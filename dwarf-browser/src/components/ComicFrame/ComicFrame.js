import React from 'react';
import Auth from '../AuthHelperMethods';
import './index.css';

export default class ComicFrame extends React.Component {
    render() {
        return (
            <div className="framePlanche">
                <img className="framePlancheImg" alt={this.props.name} src={Auth.url+this.props.images}/>
                <div className="framePlancheInfos">
                    <textarea readOnly disabled className="frameInfosName" value={this.props.name} />
                    <textarea readOnly disabled className="frameInfosDescri" value={this.props.description} />
                    <p className="frameInfosMode">{this.props.gamemode}</p>
                </div>
            </div>
        );
    }
}