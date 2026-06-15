<template>
  <div>
    <h2>Lista de Productos</h2>

    <router-link to="/crear">Nuevo Producto</router-link>

    <table border="1">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="producto in productos" :key="producto.id">
          <td>{{ producto.nombre }}</td>
          <td>{{ producto.descripcion }}</td>
          <td>{{ producto.precio }}</td>
          <td>{{ producto.stock }}</td>

          <td>
            <router-link :to="`/editar/${producto.id}`">Editar</router-link>
            
            <button @click="eliminar(producto.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import productoService from '../services/productoService'

const productos = ref([])

const cargarProductos = async () => {
  const response = await productoService.obtenerProductos()
  productos.value = response.data
}

const eliminar = async (id) => {
  if (!confirm('¿Eliminar producto?')) return

  await productoService.eliminarProducto(id)
  cargarProductos()
}

onMounted(() => {
  cargarProductos()
})
</script>