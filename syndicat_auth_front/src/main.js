import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import store from './store';

// bootstrap css/js file import
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min.js'

// importation du router
import router from './router'

// initialisation de l'application
const app = createApp(App)
app.use(router)
app.use(store)
app.mount('#app')
