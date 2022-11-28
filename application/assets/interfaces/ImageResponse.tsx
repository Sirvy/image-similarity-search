export function isImageResponseInterface(object: any): object is ImageResponseInterface {
    return typeof object === 'object' && 'id' in object && 'filename' in object && 'histogram' in object;
}

export function isHistogramInterface(object: any): object is HistogramInterface {
    return typeof object === 'object' && 'red' in object && 'green' in object && 'blue' in object && 'y' in object && 'u' in object && 'v' in object;
}

export interface HistogramInterface {
    red: Array<number>,
    green: Array<number>,
    blue: Array<number>,
    y: Array<number>,
    u: Array<number>,
    v: Array<number>,
}

export interface ImageResponseInterface {
    id: number,
    filename: string,
    histogram: object
}