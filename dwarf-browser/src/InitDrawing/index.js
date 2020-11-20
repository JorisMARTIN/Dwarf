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
        alert('Création de la page ...');
        e.preventDefault();

        Auth.fetch("initDrawing.php", {
            method: 'POST',
            body: JSON.stringify({
                title: this.state.title,
                gamemode: this.state.gamemode,
                descritpion: this.state.descritpion,
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
                <h1>Initiate a Drawing</h1>
                <form>

                    <fieldset className="first">
                        <label htmlFor="title" className="labelTitle">Title :</label>
                        <input type="text" maxLength="32" name="title" id="title" value={this.state.title} onChange={this._handleChange} required/>
                    </fieldset>

                    <fieldset className="second">
                        <div>
                            <label className="labelTitle">Game Mode :</label>
                            <div className="radiosButtons">
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
                        <div>
                            <label htmlFor="description" className="labelTitle">Description :</label>
                            <textarea name="descritpion" rows="5" cols="60" maxLength="512" id="description" onChange={this._handleChange}></textarea>
                        </div>
                    </fieldset>

                    <fieldset className="third">
                        <label className="labelTitle">Game type :</label>
                        <div>
                            <input type="radio" name="gametype" id="public" value="0" onChange={this._handleChange} checked/>
                            <label htmlFor="public">Public</label>
                        </div>
                        <div>
                            <input type="radio" name="gametype" id="private" value="1" onChange={this._handleChange} disabled/>
                            <label htmlFor="private">Private</label>
                        </div>
                    </fieldset>

                    <button className="submitButton" onClick={this.handleFormSubmit}>Submit</button>
                </form>
            </div>
        )
    };
}

export default withAuth(InitDrawing);