<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const producto = ref(null)

onMounted(async () => {
  const respuesta = await axios.get(
    `http://127.0.0.1:8000/api/productos/${route.params.id}`
  )

  producto.value = respuesta.data.data
})
</script>

<template>
  <div v-if="producto">
    <h1>{{ producto.nombre }}</h1>
    <p>Descripción: {{ producto.descripcion }}</p>
    <p>Precio: {{ producto.precio }}</p>

    <button @click="$router.push('/catalogo')">← Volver</button>
  </div>
</template>