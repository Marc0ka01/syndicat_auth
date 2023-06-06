// store.js

import { createStore } from 'vuex';

const store = createStore({
    state() {
        return {
            userId: null,
        };
    },
    mutations: {
        setUserId(state, id) {
            state.userId = id;
        },
    },
});

export default store;
