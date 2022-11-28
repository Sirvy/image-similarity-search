import * as React from "react";
import SamplePreviewForm from "./SamplePreviewForm";
import {HistogramInterface} from "../interfaces/ImageResponse";
import axios from "axios";
import {refreshTable} from "./ImageListTable";
import {startLoading} from "./Loading";

export default class MainForm extends React.Component<any, any> {
    constructor(props: any) {
        super(props);
        this.state = {
            sampleHistogram: {
                'red': [],
                'green': [],
                'blue': [],
                'y': [],
                'u': [],
                'v': [],
            },
            colorRangeFrom: 0,
            colorRangeTo: 255,
            comparator: 'euclidRGB',
        }
        this.onValueChange = this.onValueChange.bind(this);
        this.onApplySearch = this.onApplySearch.bind(this);
        this.updateSampleHistogram = this.updateSampleHistogram.bind(this);
    }

    onValueChange(event: React.ChangeEvent<HTMLInputElement>) {
        this.setState({
            comparator: event.currentTarget.value
        });
    }

    updateSampleHistogram(histogram: HistogramInterface) {
        this.setState({sampleHistogram: histogram});
    }

    onApplySearch(event: React.MouseEvent<HTMLButtonElement>) {
        if (parseInt(this.state.colorRangeFrom) >= parseInt(this.state.colorRangeTo)) {
            alert('Color range FROM must be lower than color range TO')
            return;
        }

        if (this.state.sampleHistogram.red.length == 0) {
            alert('Invalid sample. Please upload a valid sample image for comparison.')
            return;
        }

        const imageListLoading = document.querySelector<HTMLElement>('#image-list-loading');
        const imageList = document.querySelector<HTMLElement>('#image-list');
        imageList.innerHTML = '';
        startLoading(imageListLoading);
        const target = event.currentTarget;
        target.disabled = true;
        target.innerText = 'Applying...';

        const formData = new FormData();
        formData.append('sampleHistogram', JSON.stringify(this.state.sampleHistogram))
        formData.append('comparator', this.state.comparator)
        formData.append('colorRangeFrom', this.state.colorRangeFrom)
        formData.append('colorRangeTo', this.state.colorRangeTo)

        axios.post('/api/apply-search', formData).then(r => {
            if (Array.isArray(r.data)) {
                refreshTable(r.data)
            }
        }).catch((e) => {
            alert(`ERROR while applying algorithm.`);
            console.log(e);
        }).finally(() => {
            target.innerText = 'Apply algorithm';
            target.disabled = false;
        })
    }

    render() {
        return (
            <div id="mainForm">
                <div className="mb-3">
                    <SamplePreviewForm updateSampleHistogram={this.updateSampleHistogram}/>
                </div>
                <div className="mb-3">
                    <p>Similarity function</p>
                </div>
                <div className="mb-3">
                    <div className="form-check">
                        <input className="form-check-input mt-0" type="radio" name="algorithm" id="euclidRGB"
                               value="euclidRGB"
                               checked={this.state.comparator === 'euclidRGB'} onChange={this.onValueChange}/>
                        <label className="form-check-label" htmlFor="euclidRGB">
                            Euclidean Distance RGB
                        </label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input mt-0" type="radio" name="algorithm" id="euclidYUV"
                               value="euclidYUV"
                               checked={this.state.comparator === 'euclidYUV'} onChange={this.onValueChange}/>
                        <label className="form-check-label" htmlFor="euclidYUV">
                            Euclidean Distance YUV
                        </label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input mt-0" type="radio" name="algorithm" id="bcRGB" value="bcRGB"
                               checked={this.state.comparator === 'bcRGB'} onChange={this.onValueChange}/>
                        <label className="form-check-label" htmlFor="bcRGB">
                            Bhattacharyya Distance RGB
                        </label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input mt-0" type="radio" name="algorithm" id="bcYUV" value="bcYUV"
                               checked={this.state.comparator === 'bcYUV'} onChange={this.onValueChange}/>
                        <label className="form-check-label" htmlFor="bcYUV">
                            Bhattacharyya Distance YUV
                        </label>
                    </div>
                </div>
                <div className="mb-3">
                    <label htmlFor="colorRangeFrom" className="form-label">
                        Color range from: {this.state.colorRangeFrom}
                    </label>
                    <input type="range" className="form-range" min="0" max="255" step="1"
                           value={this.state.colorRangeFrom} id="colorRangeFrom"
                           onInput={(event) => this.setState({colorRangeFrom: event.currentTarget.value})}
                           onChange={(event) => this.setState({colorRangeFrom: event.currentTarget.value})}/>
                </div>
                <div className="mb-3">
                    <label htmlFor="colorRangeTo" className="form-label">
                        Color range to: {this.state.colorRangeTo}
                    </label>
                    <input type="range" className="form-range" min="0" max="255" step="1"
                           value={this.state.colorRangeTo} id="colorRangeTo"
                           onInput={(event) => this.setState({colorRangeTo: event.currentTarget.value})}
                           onChange={(event) => this.setState({colorRangeTo: event.currentTarget.value})}/>
                </div>
                <button type="button" className="btn btn-primary" onClick={this.onApplySearch}>Apply Search</button>
            </div>
        );
    }
}