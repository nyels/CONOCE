// resources/js/app.js
import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import { Ziggy } from './ziggy';

const appName = 'CONOCE Cotizador';

// Hide loading screen function
const hideLoading = () => {
    const loading = document.getElementById('app-loading');
    if (loading) {
        loading.classList.add('hidden');
        setTimeout(() => loading.remove(), 300);
    }
};

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy);

        app.mount(el);

        // Hide loading screen after Vue mounts
        hideLoading();

        return app;
    },
    progress: {
        color: '#7B2D3B',
        showSpinner: false,
    },
});