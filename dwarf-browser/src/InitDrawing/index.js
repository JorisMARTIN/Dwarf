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
            console.log(res);
        });
    }

    render() {
        return (
            <div className="container">
                <h1>Initiate a Drawing</h1>
                <form>

                    <fieldset className="first">
                        <label htmlFor="title">Title :</label>
                        <input type="text" name="title" id="title" value={this.state.title} onChange={this._handleChange} required/>
                    </fieldset>

                    <fieldset className="second">
                        <div>
                            <label>Game Mode :</label>
                            <div>
                                <input type="radio" name="gamemode" id="normal" value="0" onChange={this._handleChange} checked/>
                                <label htmlFor="normal">Normal</label>

                                <input type="radio" name="gamemode" id="reverse" value="1" onChange={this._handleChange}/>
                                <label htmlFor="reverse">Reverse</label>

                                <input type="radio" name="gamemode" id="intermediate" value="2" onChange={this._handleChange}/>
                                <label htmlFor="intermediate">Intermediate</label>
                            </div>
                        </div>
                        <div>
                            <label htmlFor="description">Description :</label>
                            <textarea name="descritpion" id="description" onChange={this._handleChange}></textarea>
                        </div>
                    </fieldset>

                    <fieldset className="third">
                        <label>Game type :</label>

                        <input type="radio" name="gametype" id="public" value="0" onChange={this._handleChange} checked/>
                        <label htmlFor="public">Public</label>

                        <input type="radio" name="gametype" id="private" value="1" onChange={this._handleChange}/>
                        <label htmlFor="private">Private</label>
                    </fieldset>

                    <button onClick={this.handleFormSubmit}>Submit</button>
                </form>
            </div>
        )
    };
}

export default withAuth(InitDrawing);