import React from 'react';
import { CirclePicker } from 'react-color';
import CanvasDraw from 'react-canvas-draw';
import './index.css';
import Auth from '../components/AuthHelperMethods';
import { Redirect, withRouter } from 'react-router-dom';
import withAuth from '../components/withAuth';

class Canvas extends React.Component {
    state = {
        color: "#ff0000",
        brushRadius: 10,
        lazyRadius: 12,
    }

    constructor(props) {
        super(props);
        this.canvas = React.createRef();
        this.frameid = parseInt(this.props.match.params.id, 10);
    }


    //based on https://github.com/embiem/react-canvas-draw/pull/78
    exportImageToFile = () => {
        if(this.canvas.current !== null) {
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
            context.fillStyle = "#ffffff";

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

            //return imageData;
            return imageData;
        }
    }

    handleSubmit() {
        const image = this.exportImageToFile();
        Auth.fetch("savedrawing.php", {
            method:'POST',
            body: JSON.stringify({
                frameid: this.frameid,
                img: image
            })
        })
    }

    render() {
        if(isNaN(this.frameid)) return <Redirect to='/init' />
        else
        return (
            <div>
                <h1>Vous dessinez la frame {this.frameid}</h1>
                <CanvasDraw
                    ref={this.canvas}
                    brushColor={this.state.color}
                    brushRadius={this.state.brushRadius}
                    lazyRadius={this.state.lazyRadius}
                    canvasWidth={1920}
                    canvasHeight={700}
                />
                <CirclePicker color={this.state.color} onChange={(color) => (this.setState({ color: color.hex }))} />
                <div>
                    <button onClick={() => this.canvas.current.undo()}>Undo</button>
                    <div>
                        <label>Brush-Radius:</label>
                        <input
                            type="number"
                            value={this.state.brushRadius}
                            onChange={e =>
                                this.setState({ brushRadius: parseInt(e.target.value, 10) })
                            }
                        />
                    </div>
                    <div>
                        <label>Lazy-Radius:</label>
                        <input
                            type="number"
                            value={this.state.lazyRadius}
                            onChange={e =>
                                this.setState({ lazyRadius: parseInt(e.target.value, 10) })
                            }
                        />
                    </div>
                    <button onClick={this.handleSubmit}>Submit</button>
                </div>
            </div>
        );
    }
}

export default withAuth(withRouter(Canvas));