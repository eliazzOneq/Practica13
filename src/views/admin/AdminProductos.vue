<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../../stores/auth'

const auth = useAuthStore()
const productos = ref([])
const categorias = ref([])

const formulario = ref({
  id: null,
  nombre: '',
  descripcion: '',
  precio: '',
  stock: '',
  categoria_id: '',
  imagen: null
})

const editando = ref(false)

async function cargarProductos() {
  const respuesta = await axios.get(
    'http://127.0.0.1:8000/api/productos'
  )

  productos.value = respuesta.data.data
}

async function cargarCategorias() {
  const respuesta = await axios.get(
    'http://127.0.0.1:8000/api/categorias'
  )

  categorias.value = respuesta.data.data
}

function seleccionarImagen(event) {
  formulario.value.imagen =
    event.target.files[0]
}

async function guardarProducto() {
  const datos = new FormData()

  datos.append(
    'nombre',
    formulario.value.nombre
  )

  datos.append(
    'descripcion',
    formulario.value.descripcion
  )

  datos.append(
    'precio',
    formulario.value.precio
  )

  datos.append(
    'stock',
    formulario.value.stock
  )

  datos.append(
    'categoria_id',
    formulario.value.categoria_id
  )

  if (formulario.value.imagen) {
    datos.append(
      'imagen',
      formulario.value.imagen
    )
  }
  try {
    if (editando.value) {
      datos.append('_method', 'PUT')
      await axios.post(
        `http://127.0.0.1:8000/api/productos/${formulario.value.id}`,
        datos
      )

    } else {
      await axios.post(
        'http://127.0.0.1:8000/api/productos',
        datos
      )
    }

    limpiarFormulario()
    await cargarProductos()

  } catch (error) {
    console.error(error)
    alert('Error al guardar')
  }
}

function editar(producto) {
  editando.value = true
  formulario.value = {
    id: producto.id,
    nombre: producto.nombre,
    descripcion: producto.descripcion,
    precio: producto.precio,
    stock: producto.stock,
    categoria_id: producto.categoria_id ?? '',
    imagen: null
  }
}

async function eliminar(id) {
  if (!confirm('¿Eliminar producto?')) {
    return
  }
  try {
    await axios.delete(
      `http://127.0.0.1:8000/api/productos/${id}`
    )
    await cargarProductos()
  } catch (error) {
    console.error(error)
  }
}

function limpiarFormulario() {
  editando.value = false
  formulario.value = {
    id: null,
    nombre: '',
    descripcion: '',
    precio: '',
    stock: '',
    categoria_id: '',
    imagen: null
  }
}

onMounted(() => {
  cargarProductos()
  cargarCategorias()
})  
</script>

<template>
  <div>
    <h1>Administración de Productos</h1>
    <hr>
    <h2>{{ editando ? 'Editar' : 'Crear' }} producto</h2>

    <form @submit.prevent="guardarProducto">
      <input v-model="formulario.nombre" placeholder="Nombre" required>
      <br><br>
      <textarea v-model="formulario.descripcion" placeholder="Descripción"></textarea>
      <br><br>
      <input v-model="formulario.precio" type="number" step="0.01" placeholder="Precio" required>
      <br><br>
      <input v-model="formulario.stock" type="number" placeholder="Stock"required>
      <select v-model="formulario.categoria_id">
        <option value=''>Sin categoría</option>
        <option v-for='cat in categorias' :key='cat.id' :value='cat.id'>{{ cat.nombre }}</option>
      </select>
      <br><br>
      <input type="file" @change="seleccionarImagen">
      <br><br>
      <button type="submit">{{ editando ? 'Actualizar' : 'Crear' }}</button>
    </form>

    <hr>
    <h2>Productos</h2>
    <table border="1">
      <thead>
        <tr>
          <th>Imagen</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="producto in productos" :key="producto.id">
          <td>
            <img
              v-if="producto.imagen_url"
              :src="producto.imagen_url"
              width="80">
          </td>

          <td>{{ producto.nombre }}</td>
          <td>${{ producto.precio }}</td>
          <td>{{ producto.stock }}</td>

          <td>
            <button v-can="'editar'" @click="editar(producto)">Editar</button>
            <!-- Solo admins ven el botón eliminar --> 
            <button v-can="'eliminar'" @click="eliminar(producto.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
    <hr>
    <button @click="$router.push('/catalogo')">← Volver</button>
  </div>
</template>