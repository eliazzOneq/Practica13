<template> 
  <router-link to='/carrito'> 
    <span class='cart-icon'>🛒 
      <span v-if='carrito.totalItems > 0' class='badge'> 
        {{ carrito.totalItems }} 
      </span> 
    </span> 
  </router-link> 
</template> 