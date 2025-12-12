import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        userName: (state) => state.user?.name || 'User',
        userInitial: (state) => (state.user?.name || 'U').charAt(0).toUpperCase(),
    },

    actions: {
        async login(credentials) {
            try {
                const response = await api.post('/login', credentials)
                this.token = response.data.token
                this.user = response.data.user

                localStorage.setItem('token', this.token)
                localStorage.setItem('user', JSON.stringify(this.user))

                return { success: true }
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message || 'Login gagal'
                }
            }
        },

        async register(data) {
            try {
                const response = await api.post('/register', data)
                this.token = response.data.token
                this.user = response.data.user

                localStorage.setItem('token', this.token)
                localStorage.setItem('user', JSON.stringify(this.user))

                return { success: true }
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message || 'Registrasi gagal',
                    errors: error.response?.data?.errors || {}
                }
            }
        },

        async logout() {
            try {
                await api.post('/logout')
            } catch (error) {
                // Ignore errors on logout
            }

            this.token = null
            this.user = null
            localStorage.removeItem('token')
            localStorage.removeItem('user')
        },
    },
})
