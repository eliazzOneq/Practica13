import { defineStore } from 'pinia'

export const useCarritoStore = defineStore('carrito', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('carrito')) || []
  }),

  getters: {
    total() {
      return this.items.reduce(
        (total, item) => total + item.precio * item.cantidad,
        0
      )
    },

    cantidadDeProducto() {
      return (id) => {
        const producto = this.items.find(
          item => item.id === id
        )

        return producto
          ? producto.cantidad
          : 0
      }
    }
  },

  actions: {
    agregar(producto) {
      const existente = this.items.find(
        item => item.id === producto.id
      )

      if (existente) {
        existente.cantidad++
      } else {
        this.items.push({
          ...producto,
          cantidad: 1
        })
      }
    },

    aumentar(id) {
      const producto = this.items.find(
        item => item.id === id
      )

      if (producto) {
        producto.cantidad++
      }
    },

    disminuir(id) {
      const producto = this.items.find(
        item => item.id === id
      )

      if (producto && producto.cantidad > 1) {
        producto.cantidad--
      }
    },

    eliminar(id) {
      this.items = this.items.filter(
        item => item.id !== id
      )
    },

    vaciar() {
      this.items = []
    }
  }
})