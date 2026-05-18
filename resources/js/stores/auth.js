import { defineStore } from 'pinia';
import api from '../services/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
    },
    actions: {
        async login(email, password) {
            try {
                // We need to request CSRF cookie first for Sanctum (if using session), 
                // but we are using plainTextToken in AuthController so no need for CSRF cookie endpoint.
                const response = await api.post('/auth/login', { email, password });
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('auth_token', this.token);
                return true;
            } catch (error) {
                console.error('Login error', error);
                throw error;
            }
        },
        async logout() {
            try {
                await api.post('/auth/logout');
            } catch (error) {
                console.error('Logout error', error);
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('auth_token');
            }
        },
        async fetchUser() {
            if (!this.token) return;
            try {
                const response = await api.get('/auth/me');
                this.user = response.data;
            } catch (error) {
                console.error('Fetch user error', error);
                this.token = null;
                this.user = null;
                localStorage.removeItem('auth_token');
            }
        }
    }
});
