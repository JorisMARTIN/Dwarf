import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import withAuth from '../components/withAuth';

class InitDrawing extends Component{

    state = {
        title: "",
        gamemode: 0,
        descritpion: "",
        template: 0,
        gameType: 0
    };

    _handleChange = (e) => {
        this.setState({ [e.target.name]: e.target.value });
    }

    handleForm = (e) => {
        e.preventDefault();

        Auth.fetch("initDrawing.php", {
            method: 'POST',
            body: JSON.stringify({
                title: this.state.title,
                gamemode: this.state.gamemode,
                descritpion: this.state.description,
                template: this.state.template,
                gameType: 0 // Public par défaut, à modifier lors de la création de partie privée
            })
        }).then(res => {
            console.log("Resultat : "); // TODO
            console.log(res);
        });
    }

    render() {
        return (
            <div className="initateDrawingContainer">
                <h1 className="initateDrawingTitle">Initiate a Drawing</h1>
                <form className="initateDrawingForm" >

                    <fieldset className="initateDrawingChoseTitle">
                        <label htmlFor="title" className="labelTitle">Title :</label>
                        <input type="text" maxLength="32" name="title" id="title" value={this.state.title} onChange={this._handleChange} required/>
                    </fieldset>

                    <fieldset className="initateDrawingGameMode">
                        <div>
                            <label className="labelTitle">Game Mode :</label>
                            <div className="initDrawingRadiosButtons">
                                <div>
                                    <input type="radio" name="gamemode" id="normal" value="0" onChange={this._handleChange} checked/>
                                    <label htmlFor="normal">Normal</label>
                                </div>
                                <div>
                                    <input type="radio" name="gamemode" id="reverse" value="1" onChange={this._handleChange} disabled/>
                                    <label htmlFor="reverse">Reverse</label>
                                </div>
                                <div>
                                    <input type="radio" name="gamemode" id="intermediate" value="2" onChange={this._handleChange} disabled/>
                                    <label htmlFor="intermediate">Intermediate</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset className="initateDrawingDescription">
                        <label htmlFor="description" className="labelTitle">Description :</label>
                        <textarea name="descritpion" rows="5" cols="60" maxLength="512" id="description" onChange={this._handleChange}></textarea>
                    </fieldset>

                    <fieldset className="initateDrawingGameType">
                        <label className="labelTitle">Game type :</label>
                        <div className="initDrawingRadiosButtons">
                            <div>
                                <input type="radio" name="gametype" id="public" value="0" onChange={this._handleChange} checked/>
                                <label htmlFor="public">Public</label>
                            </div>
                            <div>
                                <input type="radio" name="gametype" id="private" value="1" onChange={this._handleChange} disabled/>
                                <label htmlFor="private">Private</label>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset className="initateDrawingTemplate">
                        <label className="labelTemplate">Game type :</label>
                        <div className="initDrawingRadiosButtons">
                            <div>
                                <input type="radio" name="gametype" id="public" value="0" onChange={this._handleChange} checked />
                                <label htmlFor="public">T1</label>
                            </div>
                            <div>
                                <input type="radio" name="gametype" id="private" value="1" onChange={this._handleChange} disabled />
                                <label htmlFor="private">T2</label>
                            </div>
                            <div>
                                <input type="radio" name="gametype" id="private" value="1" onChange={this._handleChange} disabled />
                                <label htmlFor="private">T3</label>
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
