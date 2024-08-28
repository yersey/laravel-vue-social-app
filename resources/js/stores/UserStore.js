import { defineStore } from 'pinia'

export const useUserStore = defineStore('userStore', {
    state: () => ({
        user: {},
        token: null,
        isAuthenticated: false,
    }),
    actions: {
        init() {
            if (this.isAuthenticated) {
                this.getUser();
            }
        },
        async login(email, password) {
            await axios.post('/api/v1/login', { email: email, password: password })
                .then(response => {
                    this.token = response.data.token;
                    this.isAuthenticated = true;
                    this.getUser();
                }).catch(error => {
                    console.error(error);
                    throw error;
                });
        },
        async logout() {
            await axios.post('/api/v1/logout')
                .then(response => {
                    this.token = null;
                    this.isAuthenticated = false;
                    this.user = {};
                }).catch(error => {
                    console.error(error);
                });
        },
        async getUser() {
            await axios.get('/api/v1/user')
                .then(response => {
                    this.user = response.data.data
                }).catch(error => {
                    console.error(error);
                });
        },
    },
    persist: true
})