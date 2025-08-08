import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import toast from './utils/toast';

createInertiaApp({
    title: (title) => `${title} - RFL Meeting System`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);

        // Handle global toast notifications
        if (props.initialPage.props.flash) {
            const { success, error, warning, info } = props.initialPage.props.flash;
            
            if (success) toast.success(success);
            if (error) toast.error(error);
            if (warning) toast.warning(warning);
            if (info) toast.info(info);
        }

        // Listen for Inertia events to show toasts
        window.addEventListener('inertia:success', (event) => {
            const { flash } = event.detail.page.props;
            if (flash) {
                if (flash.success) toast.success(flash.success);
                if (flash.error) toast.error(flash.error);
                if (flash.warning) toast.warning(flash.warning);
                if (flash.info) toast.info(flash.info);
            }
        });

        window.addEventListener('inertia:error', (event) => {
            const { errors } = event.detail;
            if (errors) {
                toast.showValidationErrors(errors);
            }
        });

        return app;
    },
    progress: {
        color: '#4B5563',
    },
}); 