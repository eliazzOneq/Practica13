<script setup>
import AdminNotificaciones
from '../../components/AdminNotificaciones.vue'
</script>

<template>
  <div>
    <h2>Dashboard</h2>
    <AdminNotificaciones />
    <br>
    <button @click="$router.push('/catalogo')">← Volver</button>
  </div>
</template>