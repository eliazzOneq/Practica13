<template>
  <div class="filtros-panel">
    <h3>Filtros</h3>

    <!-- Búsqueda -->
    <div class="campo">
      <label>Buscar producto</label>
      <input v-model="busquedaLocal" type="text" placeholder="Buscar..."/>
    </div>

    <!-- Categorías -->
    <div class="campo">
      <label>Categoría</label>
      <select v-model="filtros.categoria_id">
        <option value="">Todas las categorías</option>

        <option v-for="categoria in categorias"
          :key="categoria.id"
          :value="categoria.id">{{ categoria.nombre }}</option>
      </select>
    </div>

    <!-- Precio mínimo -->
    <div class="campo">
      <label>Precio mínimo</label>
      <input v-model.number="filtros.precio_min" type="number" min="0"/>
    </div>

    <!-- Precio máximo -->
    <div class="campo">
      <label>Precio máximo</label>
      <input v-model.number="filtros.precio_max" type="number" min="0"/>
    </div>

    <!-- Ordenamiento -->
    <div class="campo">
      <label>Ordenar por</label>
      <select v-model="filtros.orden">
        <option value="">Sin ordenar</option>
        <option value="nombre_asc">Nombre A-Z</option>
        <option value="precio_asc">Precio menor</option>
        <option value="precio_desc">Precio mayor</option>
      </select>
    </div>

    <!-- Limpiar -->
    <button @click="limpiarFiltros">Limpiar filtros</button>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue'
import axios from 'axios'

const emit = defineEmits(['filtros-cambiados'])
const categorias = ref([])
const filtros = reactive({
  busqueda: '',
  categoria_id: '',
  precio_min: '',
  precio_max: '',
  orden: ''
})

const busquedaLocal = ref('')

let timeout = null

// Debounce 300ms para búsqueda
watch(busquedaLocal, (valor) => {
  clearTimeout(timeout)

  timeout = setTimeout(() => {
    filtros.busqueda = valor
    emitirFiltros()
  }, 300)
})

// Detectar cambios en el resto de filtros
watch(
  () => [
    filtros.categoria_id,
    filtros.precio_min,
    filtros.precio_max,
    filtros.orden
  ],
  () => {
    emitirFiltros()
  }
)

function emitirFiltros() {
  emit('filtros-cambiados', { ...filtros })
}

function limpiarFiltros() {
  busquedaLocal.value = ''
  filtros.busqueda = ''
  filtros.categoria_id = ''
  filtros.precio_min = ''
  filtros.precio_max = ''
  filtros.orden = ''
  emitirFiltros()
}

async function cargarCategorias() {
  try {
    const response = await axios.get(
      'http://localhost:8000/api/categorias'
    )

    categorias.value = response.data.data
  } catch (error) {
    console.error('Error al cargar categorías', error)
  }
}

onMounted(() => {
  cargarCategorias()
})
</script>

<style scoped>
.filtros-panel {
  display: flex;
  flex-direction: column;
  gap: 12px;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.campo {
  display: flex;
  flex-direction: column;
}

button {
  cursor: pointer;
  padding: 8px;
}
</style>