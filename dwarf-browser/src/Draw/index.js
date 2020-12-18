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
                    frameAuthor: res.page.frameAuthor,
                });
            }
        });
    }

    drawThis = () => {
        if (this.state.frameId) {
            Auth.fetch("claimdrawing.php", {
                method: 'POST',
                body: JSON.stringify({
                    frameid: this.state.frameId,
                })
            }).then(res => {
                if(res.status === 200) {
                    this.setState({ goToCanvas: true });
                } else {
                    alert(res.message);
                }
            });
        }
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
                {this.state.img && this.state.frameId
                ? <img className="drawImage" src={"https://dwarf.jorismartin.fr" + this.state.img} alt={this.state.name} />
                : (this.state.frameId
                  ? <p className="drawImage">L'auteur de cette BD n'a pas dessiné la première case, à toi de la réaliser !</p>
                  : <p className="drawImage">Quelqu'un est déjà en train de dessiner cette case !</p>)}
                <div className="drawInfos">
                    <div className="drawInfosText">
                        <div className="drawInfosTextPackage">
                            <label>Titre :</label>
                            <textarea readOnly disabled className="drawName" value={this.state.name} />
                        </div>
                        <div className="drawInfosTextPackage">
                            <label>Description :</label>
                            <textarea readOnly disabled className="drawDesc" value={this.state.description} />
                        </div>
                    </div>

                    <p className="drawGM">{this.state.gamemode}</p>
                    <p className="drawUser"><span>Auteur de la BD :</span> {this.state.user}</p>
                    {this.state.frameAuthor && <p className="drawUser"><span>Auteur de la case :</span> {this.state.frameAuthor}</p>}
                </div>
                <button className="drawNext" onClick={this.requestFrame}>Une autre !</button>
                {this.state.frameId && <button className="drawDraw" onClick={this.drawThis}>Dessiner !</button>}
            </div>
        ); else return (
            <div>
                <h1 className="drawNothingToDrawTitle">Rien à dessiner pour le moment</h1>
                <div className="drawNothingToDraw">
                    <p className="drawNothing">Desolé, il n'y a <b>aucune planche de commencé</b>, tu ne peux donc pas continuer de dessin.</p>
                    <p><b>À toi de commencer</b> une nouvelle BD !<Link className="drawStartNewPage" to="/init">Nouvelle BD</Link></p>
                    <p>Ou retourne voir les BD déjà réalisées   <Link className="drawStartNewPage" to="/">Accueil</Link></p>
                </div>
            </div>
        );
    }
}

export default withAuth(Draw);