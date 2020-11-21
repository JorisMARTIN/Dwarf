import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import withAuth from '../components/withAuth';
import Canvas from '../Canvas';

class InitDrawing extends Component {

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
                <h1 className="initateDrawingTitle">Initiate a Drawing</h1>

                <form className="initateDrawingForm" >

                    <fieldset className="initateDrawingChoseTitle">
                        <label htmlFor="title" className="labelTitle">Title :</label>
                        <input className="initateDrawingTitleFrame" type="text" maxLength="32" name="title" id="title" value={this.state.title} onChange={this._handleChange} required />
                    </fieldset>

                    <fieldset className="initateDrawingGameMode">
                        <div>
                            <label className="labelTitle">Game Mode :</label>
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
                                <input type="radio" name="template" id="template1" value="0" onChange={this._handleChange} checked />
                                <label htmlFor="template1">1</label>
                            </div>
                            <div>
                                <input type="radio" name="template" id="template2" value="1" onChange={this._handleChange} disabled />
                                <label htmlFor="template2">2</label>
                            </div>
                            <div>
                                <input type="radio" name="template" id="template3" value="2" onChange={this._handleChange} disabled />
                                <label htmlFor="template3">3</label>
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
