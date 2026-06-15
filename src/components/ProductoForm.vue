<template>
  <form @submit.prevent="guardar">
    <InputField
      label="Nombre"
      v-model="nombre"
      :error="errors.nombre || erroresServidor.nombre?.[0]"
    />

    <InputField
      label="Descripción"
      v-model="descripcion"
      :error="errors.descripcion || erroresServidor.descripcion?.[0]"
    />

    <InputField
      label="Precio"
      type="number"
      v-model="precio"
      :error="errors.precio || erroresServidor.precio?.[0]"
    />

    <InputField
      label="Stock"
      type="number"
      v-model="stock"
      :error="errors.stock || erroresServidor.stock?.[0]"
    />
    
    <button type="submit">{{ esEdicion ? 'Actualizar' : 'Guardar' }}</button>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm, useField } from 'vee-validate'

import productoService from '../services/productoService'
import { productoSchema } from '../schemas/productoSchema'

import InputField from './InputField.vue'

const props = defineProps({
  producto: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['guardado'])
const esEdicion = ref(false)
const mensajeExito = ref('')
const mensajeError = ref('')
const erroresServidor = ref({})

const { handleSubmit, errors, resetForm } = useForm({
  validationSchema: productoSchema
})

const { value: nombre } = useField('nombre')
const { value: descripcion } = useField('descripcion')
const { value: precio } = useField('precio')
const { value: stock } = useField('stock')

watch(
  () => props.producto,
  (nuevo) => {
    if (nuevo) {
      esEdicion.value = true
      nombre.value = nuevo.nombre
      descripcion.value = nuevo.descripcion
      precio.value = nuevo.precio
      stock.value = nuevo.stock
    }
  },
  { immediate: true }
)

const guardar = handleSubmit(async (values) => {
  mensajeExito.value = ''
  mensajeError.value = ''
  erroresServidor.value = {}
  try {
    if (esEdicion.value) {
      await productoService.actualizarProducto(
        props.producto.id,
        values
      )
      mensajeExito.value = 'Producto actualizado'
    } else {
      await productoService.crearProducto(values)
      mensajeExito.value = 'Producto creado'
      resetForm()
    }
    emit('guardado')
  } catch (error) {
    if (error.response?.status === 422) {
      erroresServidor.value =
        error.response.data.errors || {}
    } else {
      mensajeError.value =
        'Ocurrió un error al guardar'
    }
  }
})
</script>

<style scoped>
.error {
  border: 1px solid red;
}

.error-msg {
  color: red;
  font-size: 0.9rem;
}
</style>
