<script setup>
import { ref, onMounted } from 'vue'

const notificaciones = ref([])

onMounted(() => {
    window.Echo
        .private('admin-notificaciones')
        .listen('.nuevo-pedido', (data) => {
            console.log(
                'Nuevo pedido recibido',
                data
            )
            notificaciones.value.unshift(data)
        })
})
</script>

<template>
    <div>
        <h2>Notificaciones</h2>
        <div v-for="(n, index) in notificaciones":key="index">
            <hr>
            Pedido #{{ n.pedido_id }}
            <br>
            Cliente:
            {{ n.usuario }}
            <br>
            Total:
            ${{ n.total }}
            <br>
            Estado:
            {{ n.estado }}
        </div>
    </div>
</template>