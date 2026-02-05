import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

const pinia = createPinia()

createApp(App)
    .use(router)
    .use(pinia)
    .use(PrimeVue, {
        theme: {
            preset: Aura
        }
    })
    .mount('#app');
