<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  name: '',
  email: '',
  password: ''
})

async function registrar() {
  await axios.post(
    '/api/register',
    form.value
  )

  alert('Usuario creado')
  router.push('/login')
}
</script>

<template>
  <div>
    <h1>Registro</h1>

    <input v-model="form.name" placeholder="Nombre">
    <input v-model="form.email" placeholder="Correo">
    <input type="password" v-model="form.password" placeholder="Contraseña">

    <select v-model="form.rol">
      <option value="cliente">Cliente</option>
      <option value="editor">Editor</option>
      <option value="admin">Admin</option>
    </select>
    <br><br>
    <button @click="registrar">Registrarse</button>
  </div>
  <br><br>
  <button @click="$router.push('/')">← Volver</button>
</template>