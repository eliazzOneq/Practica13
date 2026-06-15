import { useAuthStore } from '../stores/auth'

function actualizar(el, binding) {
  const auth = useAuthStore()

  const permiso = binding.value

  if (!auth.permisos[permiso]) {
    el.style.display = 'none'
  } else {
    el.style.display = ''
  }
}

export const vCan = {
  mounted: actualizar,
  updated: actualizar
}