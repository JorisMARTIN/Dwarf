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
    }

    loadedIds = [];
    index = 0;

    requestFrame = (direction) => {
        Auth.fetch("selector.php", {
            method: 'POST',
            body: JSON.stringify({
                'loadedIds': this.loadedIds,
                'index': this.index + direction,
            })
        }).then(res => {
            console.log(res);
            if(res && res.page != null) {
                this.setState({
                    name: res.page.name,
                    description: res.page.description,
                    gamemode: res.page.gamemode,
                    img: res.page.imagePtr,
                });

                this.loadedIds = res.loadedIds;
                this.index = res.index;
            }
        });
    }

    drawThis = () => {
        this.setState({goToCanvas: true});
    }

    componentDidMount() {
        this.requestFrame(0);
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
            <div>
                <h1>Select a comic to continue based on the latest frame drawn</h1>
                <button onClick={() => this.requestFrame(-1)}>&lt; Previous</button>
                <img src={"http://dwarf.jorismartin.fr" + this.state.img} alt={this.state.name} />
                <div>
                    <textarea readOnly disabled className="" value={this.state.name} />
                    <textarea readOnly disabled className="" value={this.state.description} />
                    <p className="">{this.state.gamemode}</p>
                </div>
                <button onClick={() => this.requestFrame(1)}>Next &gt;</button>
                <button onClick={this.drawThis}>Draw !</button>
            </div>
        ); else return (
            <div>
                <p>There is nothing to draw ! :(</p>
            </div>
        );
    }
}

export default withAuth(Draw);