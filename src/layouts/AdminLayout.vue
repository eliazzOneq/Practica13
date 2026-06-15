<template>
  <div>
    <h1>Panel Administrador</h1>

    <nav>
      <router-link to="/admin">
        Dashboard
      </router-link>

      |

      <router-link to="/admin/productos">
        Productos
      </router-link>
    </nav>

    <hr>

    <router-view />
  </div>
</template>