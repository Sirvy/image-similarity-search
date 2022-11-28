import * as React from "react";
import ImageList from "./ImageList";
import MainForm from "./MainForm";

export default class Application extends React.Component<any, any> {
    render() {
        return (
            <div className="container">
                <div className="d-flex flex-column justify-content-center">
                    <div className="row g-0">
                        <div className="col-lg-6 p-3">
                            <ImageList/>
                        </div>
                        <div className="col-lg-6 p-3">
                            <MainForm/>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}