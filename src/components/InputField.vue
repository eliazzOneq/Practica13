<template>
  <div class="campo">
    <label>{{ label }}</label>
    <input
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :class="{ error: error }"
    >

    <p v-if="error" class="error-msg">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  modelValue: [String, Number],
  error: String,
  type: {
    type: String,
    default: 'text'
  }
})

defineEmits(['update:modelValue'])
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