import './bootstrap';
import {createInertiaApp} from '@inertiajs/vue3';

// twilio imports
import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, h} from 'vue';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';

import vuetify from "@/plugins/vuetify/main.js"
import '@/plugins/swiper.js';
import '../scss/app.scss';
import 'vue3-emoji-picker/css'

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async name => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );

        page.then((module) => {
            if (module.default.layout) {
                return;
            }

            let layout;

            switch (true) {
                case name.startsWith('Auth/'):
                    layout = GuestLayout;
                    break;
                case name.startsWith('Payment/'):
                    layout = GuestLayout;
                    break;
                default:
                    layout = AuthenticatedLayout;
                    break;
            }

            module.default.layout = layout
        });


        return page
    },
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(VueTelInput)
            .mount(el);
    },
    progress: {
        color: '#AB0013',
    },
});
