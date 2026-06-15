<script setup>
import axios from 'axios'
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useFiltros } from '../composables/useFiltros'
import { useCarritoStore } from '../stores/carrito'
import FiltrosPanel from '../components/FiltrosPanel.vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const route = useRoute()
const { filtros } = useFiltros() 
const resultado = ref({ data: [], meta: {} }) 
const cargando  = ref(false) 
const categorias = ref([])
const categoriaActiva = ref(null)
const carrito = useCarritoStore()
const productos = ref([])
const busqueda = ref('')

function actualizarFiltros(nuevosFiltros) {
  Object.assign(filtros, nuevosFiltros)
}

const productosFiltrados = computed(() => {
  if (!Array.isArray(productos.value)) {
    return []
  }

  return productos.value.filter(producto =>
    producto.nombre
      .toLowerCase()
      .includes(busqueda.value.toLowerCase())
  )
})

onMounted(async () => {
  const { data } = await axios.get('/api/categorias')
  categorias.value = data.data
})

const filtrarPorCategoria = async (cat) => {
  categoriaActiva.value = cat
  const { data } = await axios
    .get(`/api/categorias/${cat.id}/productos`)
  productos.value = data.data
}

const cargarProductos = async () => {
  cargando.value = true
  const { data } = await axios.get('/api/productos', {
    params: {
      busqueda: filtros.busqueda,
      categoria_id: filtros.categoria_id,
      precio_min: filtros.precio_min,
      precio_max: filtros.precio_max,
      page: filtros.pagina,
    }
  })
  console.log('PRODUCTOS API:', data)

  resultado.value = data
  productos.value = data.data
  cargando.value = false
}

// Recargar cuando cambien los filtros o la URL 
watch(() => route.query, cargarProductos, { immediate: true })
watch(filtros, cargarProductos, { deep: true })
</script>

<template>
  <div>
    <h1>Catálogo</h1>

    <FiltrosPanel @filtros-cambiados="actualizarFiltros"/>

    <input v-model="busqueda" placeholder="Buscar...">

    <router-link to="/carrito">
      <button>🛒 Carrito ({{ carrito.items.length }})</button>
    </router-link>

    <router-link to="/admin">
      <button>⚙️ Panel Admin</button>
    </router-link>

    <hr>

    <ul>
      <li v-for="producto in productosFiltrados" :key="producto.id">
        <router-link :to="`/catalogo/${producto.id}`">{{ producto.nombre }}</router-link>
        <img v-if="producto.imagen_url"
          :src="producto.imagen_url"
          :alt="producto.nombre"
          class="producto-imagen"/>
        <span v-else>Sin imagen</span>

        <br>

        <button @click="carrito.agregar(producto)">
          <template v-if="carrito.cantidadDeProducto(producto.id) > 0">
            En carrito
            (
              {{ carrito.cantidadDeProducto(producto.id) }}
            )
          </template>
          
          <template v-else>Agregar al carrito</template>
        </button>
      </li>
    </ul>
  </div>
  <br><br><br>
  <button @click="$router.push('/login')">← Volver</button>
</template>