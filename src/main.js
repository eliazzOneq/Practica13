  import { createApp } from 'vue'
  import { createPinia } from 'pinia'
  import './echo'

  import App from './App.vue'
  import router from './router'
  import axios from 'axios'
  import { vCan } from './directives/can'

  import { useCarritoStore } from './stores/carrito'

  const app = createApp(App)

  const pinia = createPinia()

  app.use(pinia)
  app.use(router)

  import { useAuthStore } from './stores/auth'

  app.use(router)
  const auth = useAuthStore(pinia)

  if (auth.token) {
    axios.defaults.headers.common[
      'Authorization'
    ] = `Bearer ${auth.token}`
    auth.obtenerPerfil()
  }

  const carrito = useCarritoStore(pinia)

  carrito.$subscribe((mutation, state) => {
    localStorage.setItem(
      'carrito',
      JSON.stringify(state.items)
    )
  })

  app.directive('can', vCan) 
  app.mount('#app')