// import './bootstrap';
import '../css/app.css';
// import '../css/admin.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Antd from 'ant-design-vue';

import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs';
import { i18nVue } from 'laravel-vue-i18n'; 

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(LaravelPermissionToVueJS)
            .use(plugin)
            .use(ZiggyVue, typeof Ziggy !== 'undefined' ? Ziggy : {})
            .use(Antd)
            .use(i18nVue, { 
                resolve: async lang => {
                    const langs = import.meta.glob('../../lang/*.json');
                    return await langs[`../../lang/${lang}.json`]();
                }
            })
            .component('inertia-head', Head)
            .component('inertia-link', Link)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});