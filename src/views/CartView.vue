<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useCarritoStore } from '../stores/carrito'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()
const carrito = useCarritoStore()

function vaciarCarrito() {
  if (confirm('¿Vaciar carrito?')) {
    carrito.vaciar()
  }
}

const finalizarCompra = async () => {
  try {
    await axios.post(
      'http://127.0.0.1:8000/api/pedidos',
      {
        items: carrito.items
      }
    )

    carrito.vaciar()
    alert('Compra realizada')
    router.push('/catalogo')

  } catch (error) {
    console.log(error)

    if (error.response) {
      console.log('STATUS:', error.response.status)
      console.log('DATA:', error.response.data)
    }

    alert('Revisa la consola F12')
  }
}
</script>

<template>
  <div>
    <h1>Carrito de compras</h1>
    <div v-if="carrito.items.length === 0">Carrito vacío</div>

    <div v-for="producto in carrito.items" :key="producto.id">
      <hr>
      <h3>{{ producto.nombre }}</h3>

      <p>Precio:
        ${{ producto.precio }}
      </p>

      <button @click="carrito.disminuir(producto.id)">-</button>

      {{ producto.cantidad }}

      <button @click="carrito.aumentar(producto.id)">+</button>
      <button @click="carrito.eliminar(producto.id)">×</button>

      <p>Subtotal:
        ${{ producto.precio * producto.cantidad }}
      </p>
    </div>

    <hr>

    <h2>Total:
      ${{ carrito.total }}
    </h2>

    <button @click="vaciarCarrito">Vaciar carrito</button>
    <button @click="finalizarCompra">Finalizar compra</button>

    <hr>

    <button @click="$router.push('/catalogo')">← Volver</button>
  </div>
</template>