import 'bootstrap';
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import FrontLayout from "@/Layouts/FrontLayout.vue";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        let page: any;
        // @ts-ignore
        page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./**/**/*.vue'));
        page.then((module) => {
            module.default.layout = module.default.layout || FrontLayout;
        });
        return page;
    },
    setup({el, app, props, plugin}) {

        const App = createApp({render: () => h(app, props)})
        App.use(plugin).mount(el);
        return App;
    },
});

InertiaProgress.init({color: '#4B5563'});

