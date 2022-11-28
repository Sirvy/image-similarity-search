import React from "react";
import ReactDOM from "react-dom/client";
import "../styles/index.scss";
import Application from "../components/Application";

document.addEventListener('DOMContentLoaded', () => {
	console.log('ok');
});

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<Application />);