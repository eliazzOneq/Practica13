import { createRouter, createWebHistory } from 'vue-router'

import HomeView from '../views/HomeView.vue'
import CatalogoView from '../views/CatalogoView.vue'
import ProductoDetalleView from '../views/ProductoDetalle.vue'
import LoginView from '../views/LoginView.vue'
import NotFoundView from '../views/NotFound.vue'
import CartView from '../views/CartView.vue'

import RegisterView from '../views/RegisterView.vue'
import AdminLayout from '../layouts/AdminLayout.vue'
import DashboardView from '../views/admin/AdminDashboard.vue'
import ProductosAdminView from '../views/admin/AdminProductos.vue'

import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/',
    component: HomeView
  },

  {
    path: '/catalogo',
    component: CatalogoView
  },

  {
    path: '/catalogo/:id',
    component: ProductoDetalleView
  },

  {
    path: '/register',
    component: RegisterView
  },

  {
    path: '/login',
    component: LoginView
  },

  {
    path: '/carrito',
    component: CartView
  },

  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },

    children: [
      {
        path: '',
        component: DashboardView
      },

      {
        path: 'productos',
        component: ProductosAdminView
      }
    ]
  },

  {
    path: '/:pathMatch(.*)*',
    component: NotFoundView
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.autenticado) {
    return `/login?redirect=${to.fullPath}`
  }
})

export default router