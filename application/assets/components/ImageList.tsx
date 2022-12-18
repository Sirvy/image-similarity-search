import * as React from "react";
import ImageListTable from "./ImageListTable";
import ImageListFileUpload from "./ImageListFileUpload";

export default class ImageList extends React.Component<any, any> {
    render() {
        return (
            <div>
                <div className="mb-3">
                    <ImageListFileUpload/>
                </div>
                <ImageListTable/>
            </div>
        );
    }
}