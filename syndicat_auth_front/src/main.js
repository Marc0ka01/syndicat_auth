import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import store from './store';

// bootstrap css/js file import
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min.js'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// importation du router
import router from './router'

// initialisation de l'application
const app = createApp(App)
app.use(VueSweetalert2);
app.use(router)
app.use(store)
app.mount('#app')
