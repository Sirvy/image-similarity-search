import * as React from "react";
import axios from "axios";
import {drawHistogramOnCanvas} from "./Histogram";
import {isHistogramInterface, isImageResponseInterface} from "../interfaces/ImageResponse";
import {startLoading} from "./Loading";

export default class SamplePreviewForm extends React.Component<any, any> {
    handleClick() {
        const formData = new FormData();
        const imageFile = document.querySelector<HTMLInputElement>('#formFile');

        if (imageFile.files.length == 0) {
            alert('No File selected.');
            return;
        }

        formData.append("image", imageFile.files[0]);
        const imagePreview = document.querySelector<HTMLElement>("#imagePreview");
        startLoading(imagePreview)
        axios.post('/api/sample', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(r => {
            imagePreview.innerHTML = '';
            const image = document.createElement('img');
            image.style.maxWidth = '100%';
            image.style.maxHeight = '100%';

            if (!isImageResponseInterface(r.data)) {
                alert(`Error: response format doesn't correspond to the interface. Please try again.`);
                return;
            }

            if (isHistogramInterface(r.data.histogram)) {
                const redCanvas = document.querySelector<HTMLCanvasElement>('#red');
                const greenCanvas = document.querySelector<HTMLCanvasElement>('#green');
                const blueCanvas = document.querySelector<HTMLCanvasElement>('#blue');
                drawHistogramOnCanvas(r.data.histogram, redCanvas, greenCanvas, blueCanvas);
                this.props.updateSampleHistogram(r.data.histogram);
            }

            image.src = r.data.filename;
            imagePreview.appendChild(image);
        }).catch(e => {
            alert(`Error occurred while trying to upload sample image.`);
            console.log(e);
        });
    }

    render() {
        return (
            <div>
                <p>Similarity Search Form</p>
                <div id="imagePreview" style={{background: "#fff", height: 320, textAlign: 'center'}}
                     className="w-100 position-relative border"></div>
                <div>
                    <canvas id="red" className="col-4 p-0 m-0" style={{height: 50}}></canvas>
                    <canvas id="green" className="col-4 p-0 m-0" style={{height: 50}}></canvas>
                    <canvas id="blue" className="col-4 p-0 m-0" style={{height: 50}}></canvas>
                </div>
                <div className="row align-items-end">
                    <div className="col-10">
                        <input className="form-control" type="file" id="formFile"
                               accept="image/png, image/jpeg, image/webp"/>
                    </div>
                    <div className="col-2 px-0">
                        <button type="button" className="btn btn-primary mt-3 w-100"
                                onClick={() => this.handleClick()}>Upload
                        </button>
                    </div>
                </div>
            </div>
        )
    }
}