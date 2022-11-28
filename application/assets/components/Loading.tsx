import React from "react";

export function startLoading(element: HTMLElement) {
    element.innerHTML = '';

    const loadingElement = document.createElement('div');
    loadingElement.className = "spinner-border text-secondary";
    loadingElement.style.position = 'absolute';
    loadingElement.style.left = '0';
    loadingElement.style.right = '0';
    loadingElement.style.top = '0';
    loadingElement.style.bottom = '0';
    loadingElement.style.margin = 'auto';
    loadingElement.role = "status";

    element.appendChild(loadingElement);
}

export function stopLoading(element: HTMLElement) {
    element.innerHTML = '';
}