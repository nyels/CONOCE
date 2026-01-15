import './bootstrap';
import { createApp } from 'vue';
import QuoteForm from './components/QuoteForm.vue';

const app = createApp({});

app.component('quote-form', QuoteForm);

app.mount('#app');
