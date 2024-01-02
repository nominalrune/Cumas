import { createRoot } from 'react-dom/client';
import React from 'react';
import App from './src/App';
document.addEventListener("DOMContentLoaded", () => {
	const root = createRoot(document.getElementById('root'));
	root.render(<App/>);
});