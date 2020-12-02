import './index.css';
import withAuth from '../components/withAuth';
import { Component } from 'react';
import Auth from '../components/AuthHelperMethods';
import Canvas from '../Canvas';

class Draw extends Component {
    state = {
        img: null,
        name: "",
        description: "",
        gamemode: "",
        goToCanvas: false,
        loadedIds: [],
        frameId: null,
        frameWidth: null,
        frameHeight: null,
    }


    requestFrame = () => {
        Auth.fetch("selector.php", {
            method: 'POST',
            body: JSON.stringify({
                loadedIds: this.state.loadedIds,
            })
        }).then(res => {
            console.log(res);
            if(res && res.page != null) {
                this.setState({
                    loadedIds: res.loadedIds,
                    name: res.page.name,
                    description: res.page.description,
                    gamemode: res.page.gamemode,
                    img: res.page.imagePtr,
                    frameId: res.page.frameId,
                    frameWidth: res.page.frameWidth,
                    frameHeight: res.page.frameHeight,
                });
            }
        });
    }

    drawThis = () => {
        this.setState({goToCanvas: true});
    }

    componentDidMount() {
        this.requestFrame();
    }

    render() {
        if(this.state.goToCanvas) return (
            <Canvas 
                frameId={this.state.frameId}
                frameWidth={this.state.frameWidth}
                frameHeight={this.state.frameHeight}
                pageName={this.state.name}
                pageGM={this.state.gamemode}
                pageDesc={this.state.description}
                refereeImage={this.state.img}
            />
        ); else if(this.state.img) return (
            <div className="drawSelector">
                <h1 className="drawTitle">Select a comic to continue based on the latest frame drawn</h1>
                <img className="drawImage" src={"http://dwarf.jorismartin.fr" + this.state.img} alt={this.state.name} />
                <div className="drawInfos">
                    <textarea readOnly disabled className="drawName" value={this.state.name} />
                    <textarea readOnly disabled className="drawDesc" value={this.state.description} />
                    <p className="drawGM">{this.state.gamemode}</p>
                </div>
                <button className="drawNext" onClick={this.requestFrame}>Give me another</button>
                <button className="drawDraw" onClick={this.drawThis}>Draw !</button>
            </div>
        ); else return (
            <div>
                <p className="drawNothing">There is nothing to draw ! :(</p>
            </div>
        );
    }
}

export default withAuth(Draw);