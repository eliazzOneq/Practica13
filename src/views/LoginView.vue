<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')

async function iniciarSesion() {
  try {
    await auth.login(
      email.value,
      password.value
    )

    router.push('/catalogo')
  } catch {
    alert('Credenciales incorrectas')
  }
}
</script>

<template>
  <div>
    <h1>Login</h1>

    <input v-model="email" placeholder="Correo">
    <input type="password" v-model="password" placeholder="Contraseña">

    <button @click="iniciarSesion">Ingresar</button><br><br>
    <router-link to="/register">Crear cuenta</router-link>

    <br><br>

    <button @click="$router.push('/')">← Volver</button>
  </div>
</template>