import React from 'react';
import { CirclePicker } from 'react-color';
import CanvasDraw from 'react-canvas-draw';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import { Redirect } from 'react-router-dom';
import withAuth from '../components/withAuth';

class Canvas extends React.Component {
    colors = [ // si ceci change, laisser la couleur par défaut en [0] et celle du background en [1]
        "#1c1c1e", // default : noir
        "#f2f2f7", // blanc
        "#0a84ff", // bleu
        "#ff453a", // rouge
        "#30d158", // vert
        "#ffd60a", // jaune
        "#8944ab", // violet
        "#ff9f0a", // orange
    ]

    state = {
        color: this.colors[0],
        brushRadius: 7,
        lazyRadius: 1,
        redirectToHome: false,
        blockSubmit: false,
    }

    constructor(props) {
        super(props);
        this.canvas = React.createRef();
    }

    //based on https://github.com/embiem/react-canvas-draw/pull/78
    exportImageToFile = () => {
        if (this.canvas.current !== null) {
            // Get a reference to the "drawing" layer of the canvas
            let canvasToExport = this.canvas.current.canvas.drawing;

            let context = canvasToExport.getContext("2d");

            //cache height and width		
            let width = canvasToExport.width;
            let height = canvasToExport.height;

            //get the current ImageData for the canvas
            let storedImageData = context.getImageData(0, 0, width, height);

            //store the current globalCompositeOperation
            var compositeOperation = context.globalCompositeOperation;

            //set to draw behind current content
            context.globalCompositeOperation = "destination-over";

            //set background color
            context.fillStyle = this.colors[1];

            //fill entire canvas with background colour
            context.fillRect(0, 0, width, height);

            // Export the canvas to data URL
            let imageData = canvasToExport.toDataURL('image/png');

            //clear the canvas
            context.clearRect(0, 0, width, height);

            //restore it with original / cached ImageData
            context.putImageData(storedImageData, 0, 0);

            //reset the globalCompositeOperation to what it was
            context.globalCompositeOperation = compositeOperation;
            return imageData;
        }
    }

    handleSubmit = () => {
        this.setState({ blockSubmit: true });

        const image = this.exportImageToFile();

        Auth.fetch("savedrawing.php", {
            method: 'POST',
            body: JSON.stringify({
                frameid: this.props.frameId,
                img: image
            })
        }).then(res => {
            this.setState({ redirectToHome: true });
        })
    }

    keydownHandler = (e) => {
        if (e.code === 'KeyW' && e.ctrlKey) this.canvas.current.undo();
    }

    componentDidMount() {
        document.addEventListener('keydown', this.keydownHandler);
    }

    componentWillUnmount() {
        document.removeEventListener('keydown', this.keydownHandler);
    }

    copyRefereeAsBG = () => {
        // if(this.canvas.current !== null) {
        //     this.canvas.current.image.src = "https://dwarf.jorismartin.fr" + this.props.refereeImage;
        // }
    }

    render() {
        if (this.state.redirectToHome) return <Redirect to='/' />
        else return (
            <div>
                <div className="canvasMain">
                    <div className="canvasToolsLeft">
                        <div className="canvasToolsLeftDropDown">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div className="canvasToolsLeftImage">
                            {this.props.refereeImage && <img onClick={this.copyRefereeAsBG} src={"https://dwarf.jorismartin.fr" + this.props.refereeImage} alt="referee frame" />}
                        </div>
                        <div className="canvasToolsLeftOthersTools">
                            <button
                                className="canvasToolsLeftUndo"
                                onClick={() => this.canvas.current.undo()}>Undo (CTRL + Z)</button>
                            <div className="canvasToolsLeftBrush">
                                <label>Brush-Radius:</label>
                                <input
                                    type="range"
                                    min="1"
                                    max="60"
                                    value={this.state.brushRadius}
                                    onChange={e =>
                                        this.setState({ brushRadius: parseInt(e.target.value, 10) })
                                    }
                                />
                            </div>
                            <div className="canvasToolsLeftLazy">
                                <label>Lazy-Radius:</label>
                                <input
                                    type="range"
                                    min="1"
                                    max="30"
                                    value={this.state.lazyRadius}
                                    onChange={e =>
                                        this.setState({ lazyRadius: parseInt(e.target.value, 10) })
                                    }
                                />
                            </div>
                        </div>
                        <button
                            className="canvasToolsLeftSubmit"
                            onClick={this.handleSubmit}
                            disabled={this.state.blockSubmit}>Submit
                        </button>
                    </div>
                    <div className="canvasDraw"
                        onContextMenu={(e) => { e.preventDefault(); }}
                        style={{
                            maxWidth: this.props.frameWidth
                        }}
                    >
                        <div className="canvasDrawTitle">
                            <textarea disabled readOnly value={this.props.pageName} />
                            <textarea disabled readOnly value={this.props.pageDesc} />
                        </div>
                        <CanvasDraw
                            className="canvasDrawSection"
                            ref={this.canvas}
                            brushColor={this.state.color}
                            brushRadius={this.state.brushRadius}
                            lazyRadius={this.state.lazyRadius}
                            canvasWidth={this.props.frameWidth}
                            canvasHeight={this.props.frameHeight}
                            backgroundColor={this.colors[1]}
                            hideGrid
                        />
                    </div>
                    <div className="canvasToolsRight">
                        <div className="canvasToolsRightDropDown">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <CirclePicker
                            className="canvasToolsRightColorPicker"
                            color={this.state.color}
                            onChange={color => this.setState({ color: color.hex })}
                            width={104}
                            circleSize={32}
                            circleSpacing={20}
                            colors={this.colors}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

export default withAuth(Canvas);