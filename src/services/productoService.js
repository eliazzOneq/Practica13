import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api'
})

export default {
  obtenerProductos() {
    return api.get('/productos')
  },

  crearProducto(data) {
    return api.post('/productos', data)
  },

  actualizarProducto(id, data) {
    return api.put(`/productos/${id}`, data)
  },

  eliminarProducto(id) {
    return api.delete(`/productos/${id}`)
  }
}