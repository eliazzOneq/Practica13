import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token'),
    user: null,
    permisos: {
      crear: false,
      editar: false,
      eliminar: false
    }
  }),

  getters: {
    autenticado: state => !!state.token
  },

  actions: {
    async login(email, password) {
      const { data } = await axios.post(
        '/api/login',
        {
          email,
          password
        }
      )

      this.token = data.token
      this.user = data.user

      localStorage.setItem(
        'token',
        data.token
      )

      axios.defaults.headers.common[
        'Authorization'
      ] = `Bearer ${data.token}`

      await this.obtenerPerfil()
    },

    async obtenerPerfil() {
      const { data } = await axios.get(
        '/api/me'
      )

      this.user = data
      this.permisos = data.permisos
    },

    async logout() {
      await axios.post('/api/logout')

      localStorage.removeItem('token')

      this.token = null
      this.user = null
    }
  }
})