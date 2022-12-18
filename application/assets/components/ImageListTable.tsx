import React from "react";
import axios from "axios";
import {isHistogramInterface, isImageResponseInterface} from "../interfaces/ImageResponse";
import {createHistogramCanvas, drawHistogramOnCanvas} from "./Histogram";
import {startLoading, stopLoading} from "./Loading";

export function refreshTable(data: Array<string>) {
    const imageList = document.querySelector<HTMLElement>('#image-list');
    const imageListLoading = document.querySelector<HTMLElement>('#image-list-loading');
    stopLoading(imageListLoading)
    imageList.innerHTML = '';

    let i = 0;

    data.forEach(e => {
        if (!isImageResponseInterface(e)) {
            console.log('error: response doesnt correspond to interface.');
            return;
        }

        i++;
        const tableRow = document.createElement('tr');
        const img = document.createElement('img');
        img.src = e.filename;
        img.height = 80;

        const redCanvas = createHistogramCanvas(`red-${i}`)
        const greenCanvas = createHistogramCanvas(`green-${i}`)
        const blueCanvas = createHistogramCanvas(`blue-${i}`)

        tableRow.innerHTML = `
            <td>${i}</td>
            <td><a href="${e.filename}">${img.outerHTML}</a></td>
            <td class="histogram-canvases">${redCanvas.outerHTML}${greenCanvas.outerHTML}${blueCanvas.outerHTML}</td>
        `;

        imageList.appendChild(tableRow);

        if (isHistogramInterface(e.histogram)) {
            const redCanvas = document.querySelector<HTMLCanvasElement>(`#red-${i}`);
            const greenCanvas = document.querySelector<HTMLCanvasElement>(`#green-${i}`);
            const blueCanvas = document.querySelector<HTMLCanvasElement>(`#blue-${i}`);
            drawHistogramOnCanvas(e.histogram, redCanvas, greenCanvas, blueCanvas);
        }
    });
}

export default class ImageListTable extends React.Component<any, any> {

    componentDidMount() {
        const imageListLoading = document.querySelector<HTMLElement>('#image-list-loading');
        const imageList = document.querySelector<HTMLElement>('#image-list');
        imageList.innerHTML = '';
        startLoading(imageListLoading);

        let myPromise = new Promise<void>(resolve => setTimeout(resolve, 1000));

        myPromise.then(() => {
            axios.get('/api/images').then(r => {
                if (Array.isArray(r.data)) {
                    refreshTable(r.data);
                }
            }).catch(e => {
                alert(`Error occurred while loading images.`);
                console.log(e);
            });
        });
    }

    render() {
        return (
            <div className="table-responsive border bg-white rounded position-relative" style={{height: 800}}>
                <div id="image-list-loading"></div>
                <table className="table">
                    <thead>
                    <tr className="table-light">
                        <th>#</th>
                        <th>Image</th>
                        <th>RGB histogram</th>
                    </tr>
                    </thead>
                    <tbody className="overflow-scroll h-50 position-relative" id="image-list">
                    </tbody>
                </table>
            </div>
        )
    }
}