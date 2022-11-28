import React from "react";
import axios from "axios";
import {refreshTable} from "./ImageListTable";
import {startLoading} from "./Loading";

export default class ImageListFileUpload extends React.Component<any, any> {
    handleUpload(event: React.MouseEvent<HTMLButtonElement>) {
        const formData = new FormData();
        const imageFiles = document.querySelector<HTMLInputElement>('#formFileMultiple');

        if (imageFiles.files.length < 1) {
            alert("No files selected.");
            return;
        }

        for (let i = 0; i < imageFiles.files.length; i++) {
            formData.append(`${i}`, imageFiles.files[i]);
        }

        const imageListLoading = document.querySelector<HTMLElement>('#image-list-loading');
        const imageList = document.querySelector<HTMLElement>('#image-list');
        imageList.innerHTML = '';
        startLoading(imageListLoading);

        const target = event.currentTarget;
        target.disabled = true;
        target.innerText = 'Uploading...';

        axios.post('/api/images', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(r => {
            if (Array.isArray(r.data)) {
                refreshTable(r.data);
                imageFiles.value = '';
            }
        }).catch(e => {
            alert(`Error occurred while uploading images.`);
            console.log(e);
        }).finally(() => {
            target.disabled = false;
            target.innerText = 'Upload';
        });
    }

    handleReset() {
        axios.delete('/api/images').then(() => {
            refreshTable([]);
        }).catch(e => {
            alert(`Unable to reset.`);
            console.log(e);
        });
    }

    render() {
        return (
            <div>
                <p>Image database</p>
                <div className="row align-items-end">
                    <div className="col-8">
                        <input className="form-control" type="file" id="formFileMultiple" multiple
                               accept="image/png, image/jpeg, image/webp"/>
                    </div>
                    <div className="col-2 px-0">
                        <button type="button" className="btn btn-primary w-100"
                                onClick={this.handleUpload}>Upload
                        </button>
                    </div>
                    <div className="col-2">
                        <button type="button" className="btn btn-danger w-100"
                                onClick={this.handleReset}>Reset
                        </button>
                    </div>
                </div>
            </div>
        )
    }
}