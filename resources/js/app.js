import './bootstrap';
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { useUserStore } from "@/stores/UserStore";
import { createWebHistory, createRouter } from 'vue-router'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import App from './components/App.vue'
import Posts from './components/Posts.vue'
import Login from './components/Login.vue'
import Users from './components/Users.vue'
import Profile from './components/Profile.vue'
import Friends from './components/Friends.vue'
import FriendRequests from './components/FriendRequests.vue'

const routes = [
  { path: '/', component: Posts },
  { path: '/login', component: Login },
  { path: '/profile', component: Profile },
  { path: '/users', component: Users },
  { path: '/friend-requests', component: FriendRequests },
  { path: '/friends', component: Friends },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
});

const mainApp = createApp(App)
const pinia = createPinia();
const vuetify = createVuetify({
  components,
  directives,
})

pinia.use(piniaPluginPersistedstate);
mainApp.config.globalProperties.$formatDate = (date) => (new Date(date)).toLocaleString('pl-PL');
mainApp.use(router).use(pinia).use(vuetify);

const userStore = useUserStore();
axios.interceptors.request.use(
    (config) => {
        const token = userStore.token;

        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

mainApp.mount('#app');
