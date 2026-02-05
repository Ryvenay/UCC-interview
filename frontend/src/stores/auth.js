import { defineStore } from 'pinia';
import { loginRequest, fetchMe } from '@/auth/auth.js';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token'),
    loading: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    role: (state) => state.user?.role,
  },

  actions: {
    async login(email, password) {
      this.loading = true;

      try {
        const { token } = await loginRequest(email, password);
        this.token = token;
        localStorage.setItem('token', token);

        await this.loadUser();
      } finally {
        this.loading = false;
      }
    },

    async loadUser() {
      if (!this.token) return;

      const user = await fetchMe();
      this.user = user;
    },

    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
    },
  },
});
