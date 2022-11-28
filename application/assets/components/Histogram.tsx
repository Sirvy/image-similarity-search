import {HistogramInterface} from "../interfaces/ImageResponse";

function drawOnCanvas(canvas: HTMLCanvasElement, data: Array<number>, color: string) {
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = color;
    const w = canvas.width / 256;
    const h = canvas.height;
    for (let i = 0; i < data.length; i++) {
        ctx.fillRect(i * w, h - data[i], w, data[i]);
    }
}

export function drawHistogramOnCanvas(histogram: HistogramInterface, redCanvas: HTMLCanvasElement, greenCanvas: HTMLCanvasElement, blueCanvas: HTMLCanvasElement) {
    const reds = [];
    const blues = [];
    const greens = [];

    let maxRed = 0;
    let maxGreen = 0;
    let maxBlue = 0;
    for (let i = 0; i < 256; i++) {
        if (maxRed < histogram.red[i]) maxRed = histogram.red[i];
        if (maxGreen < histogram.green[i]) maxGreen = histogram.green[i];
        if (maxBlue < histogram.blue[i]) maxBlue = histogram.blue[i];
    }

    for (let i = 0; i < 256; i++) {
        reds.push(histogram.red[i] / maxRed * 100);
        greens.push(histogram.green[i] / maxGreen * 100);
        blues.push(histogram.blue[i] / maxBlue * 100);
    }

    drawOnCanvas(redCanvas, reds, '#ff0000')
    drawOnCanvas(greenCanvas, greens, '#00ff00')
    drawOnCanvas(blueCanvas, blues, '#0000ff')
}

export function createHistogramCanvas(id: string): HTMLCanvasElement {
    const canvas = document.createElement('canvas');
    canvas.className = "histogram";
    canvas.id = id;

    return canvas;
}