import './index.css';
import withAuth from '../components/withAuth';
import { Component } from 'react';
import Auth from '../components/AuthHelperMethods';
import Canvas from '../Canvas';
import { Link } from 'react-router-dom';

class Draw extends Component {
    state = {
        img: null,
        name: null,
        description: null,
        gamemode: null,
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
                    user: res.page.user,
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
        ); else if(this.state.name !== null) return (
            <div className="drawSelector">
                <h1 className="drawTitle">Choisi une BD à continuer</h1>
                {this.state.img ? <img className="drawImage" src={"https://dwarf.jorismartin.fr" + this.state.img} alt={this.state.name} />
                : <p className="drawImage">L'auteur de cette BD n'as pas dessiné la première case, à toi de la réaliser ...<br></br><i>Tout en respectant les contraintes imposés par l'auteur</i></p>}
                <div className="drawInfos">
                    <div className="drawInfosPackage">
                        <label>Titre :</label>
                        <textarea readOnly disabled className="drawName" value={this.state.name} />
                    </div>
                    <div className="drawInfosPackage">
                        <label>Descritpion :</label>
                        <textarea readOnly disabled className="drawDesc" value={this.state.description} />
                    </div>
                    <p className="drawGM">{this.state.gamemode}</p>
                    <p className="drawUser">Auteur : {this.state.user}</p>
                </div>
                <button className="drawNext" onClick={this.requestFrame}>Une autre !</button>
                <button className="drawDraw" onClick={this.drawThis}>Dessiner !</button>
            </div>
        ); else return (
            <div className="drawNothingToDraw">
                <p className="drawNothing">Il n'y a pas de dessin à continuer.</p>
                <Link className="drawStartNewPage" to="/init">Commence une nouvelle BD !</Link>
            </div>
        );
    }
}

export default withAuth(Draw);