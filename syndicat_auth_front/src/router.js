import {createRouter, createWebHistory} from 'vue-router';
import Register from './views/Register.vue';
import Payement from './views/Payement.vue';
import Card from './views/Card.vue';
import Echec from './views/Echec.vue';

// initialisation des chemins
const routes = [
    {path:'/', component: Register, name:'/'},
    {path:'/payement', component: Payement, name:'payement'},
    {path:'/card', component:Card, name:'card'},
    {path:'/echec', component: Echec, name:'echec'},
];

// création du router
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

// exportation du router
export default router;