import React from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import withAuth from '../components/withAuth';
import Canvas from '../Canvas';

class TemplateCanvas extends React.Component {
    constructor(props) {
        super(props);
        this.canvasRef = React.createRef();
    }

    state = {
        canvasW: 0,
        canvasH: 0
    }

    async componentDidMount() {
        const canvas = this.canvasRef.current;
        const template = await (await fetch('https://dwarf.jorismartin.fr/cdn/templates/template' + this.props.id + '.json')).json();
        const ctx = canvas.getContext('2d');

        let max_x = 0;
        let max_y = 0;

        for (const frame of template) {
            if (frame.x + frame.w > max_x) max_x = frame.x + frame.w;
            if (frame.y + frame.h > max_y) max_y = frame.y + frame.h;
        }
        
        this.setState({
            canvasW: max_x,
            canvasH: max_y
        });

        ctx.fillStyle = "#f2f2f7";
        ctx.fillRect(0, 0, max_x, max_y);
        
        ctx.fillStyle = "#8E8E93";
        ctx.strokeStyle = "#1c1c1e";
        ctx.lineWidth = 20;
        for (const frame of template) {
            ctx.rect(frame.x + 30, frame.y + 30, frame.w - 60, frame.h - 60);
        }
        ctx.fill();
        ctx.stroke();
    }

    render() {
        return (
            <canvas style={{
                border: '5px solid #f2f2f7',
                width: 150,
                height: 150,
            }} width={this.state.canvasW} height={this.state.canvasH} ref={this.canvasRef} />
        );
    }
}

class InitDrawing extends React.Component {

    state = {
        title: "",
        gamemode: 0,
        description: "",
        template: 0,
        gametype: 0,

        initResponse: null,
    };

    _handleChange = (e) => {
        this.setState({ [e.target.name]: e.target.value });
    }

    handleFormSubmit = (e) => {
        e.preventDefault();

        Auth.fetch("initdrawing.php", {
            method: 'POST',
            body: JSON.stringify({
                title: this.state.title,
                gamemode: this.state.gamemode,
                description: this.state.description,
                template: this.state.template,
                gametype: this.state.gametype
            })
        }).then(res => {
            if (res.frameId && res.frameId !== -1) {
                this.setState({
                    initResponse: {
                        frameId: res.frameId,
                        frameWidth: res.width,
                        frameHeight: res.height,
                        pageName: res.pageName,
                        pageGM: res.gameMode,
                        pageDesc: res.description,
                    }
                });
            } else {
                alert("Erreur dans la création de la page");
            }
        });
    }

    render() {
        if (this.state.initResponse !== null) return (
            <Canvas {...this.state.initResponse} />
        ); else return (
            <div className="initateDrawingContainer">
                <h1 className="initateDrawingTitle">Créer une BD</h1>

                <form className="initateDrawingForm" >

                    <fieldset className="initateDrawingChoseTitle">
                        <label htmlFor="title" className="labelTitle">Titre :</label>
                        <input className="initateDrawingTitleFrame" type="text" maxLength="32" name="title" id="title" value={this.state.title} onChange={this._handleChange} required />
                    </fieldset>

                    <fieldset className="initateDrawingGameMode">
                        <div>
                            <label className="labelTitle">Mode de jeu :</label>
                            <div className="initDrawingRadiosButtons">
                                <div>
                                    <input type="radio" name="gamemode" id="normal" value="0" onChange={this._handleChange} checked />
                                    <label htmlFor="normal">Normal</label>
                                </div>
                                <div>
                                    <input type="radio" name="gamemode" id="reverse" value="1" onChange={this._handleChange} disabled />
                                    <label htmlFor="reverse">Reverse</label>
                                </div>
                                <div>
                                    <input type="radio" name="gamemode" id="intermediate" value="2" onChange={this._handleChange} disabled />
                                    <label htmlFor="intermediate">Intermediate</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset className="initateDrawingDescription">
                        <label htmlFor="description" className="labelTitle">Description :</label>
                        <textarea name="description" rows="5" cols="60" maxLength="512" id="description" onChange={this._handleChange}></textarea>
                    </fieldset>

                    <fieldset className="initateDrawingGameType">
                        <label className="labelTitle">Game type :</label>
                        <div className="initDrawingRadiosButtons">
                            <div>
                                <input type="radio" name="gametype" id="public" value="0" onChange={this._handleChange} checked />
                                <label htmlFor="public">Public</label>
                            </div>
                            <div>
                                <input type="radio" name="gametype" id="private" value="1" onChange={this._handleChange} disabled />
                                <label htmlFor="private">Private</label>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset className="initateDrawingTemplate">
                        <label className="labelTemplate">Template :</label>
                        <div className="initDrawingRadiosButtons">
                            <div>
                                <input type="radio" name="template" id="template0" value="0" onChange={this._handleChange} />
                                <label htmlFor="template0"><TemplateCanvas id="0" /></label>
                            </div>
                            <div>
                                <input type="radio" name="template" id="template1" value="1" onChange={this._handleChange} />
                                <label htmlFor="template1"><TemplateCanvas id="1" /></label>
                            </div>
                            <div>
                                <input type="radio" name="template" id="template2" value="2" onChange={this._handleChange} />
                                <label htmlFor="template2"><TemplateCanvas id="2" /></label>
                            </div>
                        </div>
                    </fieldset>

                    <button className="initateDrawingSubmitButton" onClick={this.handleFormSubmit}>Submit</button>
                </form>
            </div>
        )
    };
}

export default withAuth(InitDrawing);
