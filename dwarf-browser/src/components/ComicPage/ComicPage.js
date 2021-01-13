import React from 'react';
import { Redirect } from 'react-router-dom';
import Auth from '../AuthHelperMethods';
import './index.css';

export default class ComicPage extends React.Component {
    constructor(props) {
        super(props);
        this.canvasRef = React.createRef();

        const authors = [...this.props.authors.reduce((p, c) => p.set(c, true), new Map()).keys()];
        this.authors = "Auteur" + (authors.length > 1 ? "s" : "") + " : ";
        this.authorsTitle = this.authors + "\n";

        for (const author of authors) {
            this.authors += author + ", ";
            this.authorsTitle += author + "\n";
        }

        this.authors = this.authors.slice(0, -2);
    }

    state = {
        canvasW: 0,
        canvasH: 0,
        redirectVote: false,
        fullscreen: false,
        rate: null
    }

    componentDidMount() {
        this.drawPlanche();
    }

    drawPlanche = async () => {
        const canvas = this.canvasRef.current;
        const template = await (await fetch(Auth.url + '/cdn/templates/template' + this.props.template + '.json')).json();
        const ctx = canvas.getContext('2d');

        let max_x = 0;
        let max_y = 0;

        for (const frame of template) {
            if (frame.x + frame.w > max_x) max_x = frame.x + frame.w;
            if (frame.y + frame.h > max_y) max_y = frame.y + frame.h;
        }

        const ccbyncX = 240;
        const ccbyncY = 84;

        max_y += ccbyncY; // space for cc by nc

        this.setState({
            canvasW: max_x,
            canvasH: max_y
        });

        //todo : charger les templates de maniere lazy et pas recalculer pour chaque page !=

        for (const i in this.props.images) {
            const image = new Image();
            image.src = Auth.url + this.props.images[i];
            image.onload = () => {
                ctx.drawImage(image, template[i].x, template[i].y, template[i].w, template[i].h);
            }
        }

        const ccbync = new Image();
        ccbync.src = Auth.url + '/icons/cc-by-nc.svg';
        ccbync.onload = () => {
            ctx.drawImage(ccbync, max_x - ccbyncX, max_y - ccbyncY, ccbyncX, ccbyncY);
        }
    }

    /* VOTE */

    handleVoteClick = (rate) => {
        this.setState({ rate: rate });
        Auth.fetch("rate.php", {
            method: "POST",
            body: JSON.stringify({
                pageId: this.props.pageId,
                rateType: rate
            })
        }).then(res => {
            if (res.message) {
                // User not loged In
                if (window.confirm(res.message)) {
                    this.setState({
                        redirectVote: true
                    })
                }
            }
        })
    }

    /* Agrandissement Page */

    toggleFullscreen = () => {
        if (window.innerWidth <= 600) return;

        if (this.state.fullscreen) {
            this.setState({ fullscreen: false });
        } else {
            this.setState({ fullscreen: true });
        }
    }

    componentDidUpdate() {
        this.drawPlanche();
    }
    /* Supression page */

    deletePage = (action) => {
        if (window.confirm("Voulez vous vraiment " + ((action === "delete") ? "supprimer" : "restaurer") + " cette page ?")) {
            if(action === "delete"){
                const reason = window.prompt("Veuillez mentionner la raison de suppression : ");
                if (reason) {
                    Auth.fetch("delete.php", {
                        method: "POST",
                        body: JSON.stringify({
                            pageId: this.props.pageId,
                            action: action,
                            reason: reason
                        })
                    }).then(res => {
                        if (window.confirm(res.message + "\nConfirmer pour continuer : ")) {
                            window.location.reload();
                        }
                    })
                }
            }else{
                Auth.fetch("delete.php", {
                    method: "POST",
                    body: JSON.stringify({
                        pageId: this.props.pageId,
                        action: action
                    })
                }).then(res => {
                    if (window.confirm(res.message + "\nConfirmer pour continuer : ")) {
                        window.location.reload();
                    }
                })
            }
        }
    }

    render() {
        if (this.state.redirectVote) return <Redirect to="/auth" />
        else if (this.state.fullscreen) return (
            <div>
                <div onClick={this.toggleFullscreen} className="comicPlancheFullscreenAround" />
                <div className="comicPlancheFullscreenWrapper">
                    <img onClick={this.toggleFullscreen} className="comicPlancheFullscreenClose" src={Auth.url + "/icons/cancel.svg"} alt="X" />
                    <div className={`comicPlancheFullscreen ${this.state.rate === 1 ? "plancheLike" : ""} ${this.state.rate === 0 ? "plancheDislike" : ""}`}>
                        <canvas onClick={this.toggleFullscreen} title={this.authorsTitle} className="comicPlancheFullscreenImg" ref={this.canvasRef} width={this.state.canvasW} height={this.state.canvasH} />
                        <div className="comicPlancheTopInfos comicPlancheFullscreenInfos">
                            <textarea readOnly disabled className="comicName" value={this.props.name} />
                            <textarea readOnly disabled className="comicDescriFullscreen" value={this.props.description} />
                            <p className="comicMode">{this.props.gamemode}</p>
                            <p className="comicUser">{this.authors}</p>
                        </div>
                    </div>
                </div>
            </div>
        );
        else return (
            <div className="comicPlancheWrapper">
                <div className={`comicPlanche ${this.state.rate === 1 ? "plancheLike" : ""} ${this.state.rate === 0 ? "plancheDislike" : ""}`}>
                    <div className="comicPlancheTop">
                        <canvas onClick={this.toggleFullscreen} title={this.authorsTitle} className="comicPlancheImg" ref={this.canvasRef} width={this.state.canvasW} height={this.state.canvasH} />
                        <div className="comicPlancheTopInfos">
                            <textarea readOnly disabled className="comicName" value={this.props.name} />
                            <textarea readOnly disabled className="comicDescri" value={this.props.description} />
                            <p className="comicMode">{this.props.gamemode}</p>
                            <p className="comicUser">{this.authors}</p>
                        </div>
                    </div>
                    {this.props.deleteInfos 
                    ? 
                    <section className="comicPlancheBottom">
                        <hr/>
                        <p className="comicDeleteInfosTitle">Information de supression : </p>
                        <div className="comicDeleteInfos">
                            <ul>
                                <li>Utilisateur : {this.props.deleteInfos.userWhoDelete}</li>
                                <li>Raison : {this.props.deleteInfos.reason}</li>
                                <li>Date : {this.props.deleteInfos.date}</li>
                            </ul>
                        </div>
                        <button className="comicDeleteAdminButton" type="button" onClick={() => this.deletePage("unDelete")}>Restaurer</button>
                    </section> 
                    :
                    <section className="comicPlancheBottom">
                        <div className="comicPlancheVote">
                            <button type="button" onClick={() => this.handleVoteClick(1)}>Like</button>
                            <button type="button" onClick={() => this.handleVoteClick(0)}>Dislike</button>
                        </div>
                        {this.props.userIsAdmin && <button className="comicDeleteAdminButton" type="button" onClick={() => this.deletePage("delete")}>Supprimer</button>}
                    </section>
                    }
                </div>
            </div>
        );
    }
}