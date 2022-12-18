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
            comparator: 'euclid',
            colorModel: 'RGB',
        }
        this.onComparatorValueChange = this.onComparatorValueChange.bind(this);
        this.onColorModelValueChange = this.onColorModelValueChange.bind(this);
        this.onApplySearch = this.onApplySearch.bind(this);
        this.updateSampleHistogram = this.updateSampleHistogram.bind(this);
    }

    onComparatorValueChange(event: React.ChangeEvent<HTMLInputElement>) {
        this.setState({
            comparator: event.currentTarget.value
        });
    }

    onColorModelValueChange(event: React.ChangeEvent<HTMLInputElement>) {
        this.setState({
            colorModel: event.currentTarget.value
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
        formData.append('colorModel', this.state.colorModel)
        formData.append('colorRangeFrom', this.state.colorRangeFrom)
        formData.append('colorRangeTo', this.state.colorRangeTo)

        axios.post('/api/apply-search', formData).then(r => {
            if (Array.isArray(r.data)) {
                refreshTable(r.data)
            }
            console.log(r.data);
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
                    <p>Color model</p>
                </div>
                <div className="mb-3">
                    <div className="form-check form-check-inline">
                        <input className="form-check-input mt-0" type="radio" name="colorModel" id="RGB" value="RGB"
                               checked={this.state.colorModel === 'RGB'} onChange={this.onColorModelValueChange}/>
                        <label className="form-check-label" htmlFor="RGB">
                            RGB
                        </label>
                    </div>
                    <div className="form-check form-check-inline">
                        <input className="form-check-input mt-0" type="radio" name="colorModel" id="YUV" value="YUV"
                               checked={this.state.colorModel === 'YUV'} onChange={this.onColorModelValueChange}/>
                        <label className="form-check-label" htmlFor="YUV">
                            YUV
                        </label>
                    </div>
                </div>
                <div className="mb-3">
                    <p>Similarity function</p>
                </div>
                <div className="mb-3">
                    <div className="form-check">
                        <input className="form-check-input mt-0" type="radio" name="algorithm" id="euclid"
                               value="euclid"
                               checked={this.state.comparator === 'euclid'} onChange={this.onComparatorValueChange}/>
                        <label className="form-check-label" htmlFor="euclid">
                            Euclidean Distance
                        </label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input mt-0" type="radio" name="algorithm" id="bc" value="bc"
                               checked={this.state.comparator === 'bc'} onChange={this.onComparatorValueChange}/>
                        <label className="form-check-label" htmlFor="bc">
                            Bhattacharyya Distance
                        </label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input mt-0" type="radio" name="algorithm" id="cos" value="cos"
                               checked={this.state.comparator === 'cos'} onChange={this.onComparatorValueChange}/>
                        <label className="form-check-label" htmlFor="cos">
                            Cosine Distance
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