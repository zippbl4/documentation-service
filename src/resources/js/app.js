import './bootstrap';
import { defineCustomElement } from 'vue';
import App from './components/App.vue';

customElements.define('example-app', defineCustomElement(App));

// const app = createApp(App);
// app.mount('.app-vuejs-container');
