<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

export default {
  setup() {
    const defaultLayout = 'default'
    
    // Usar useRoute para acceder a la ruta actual
    const route = useRoute()

    // Computed property para determinar el layout dinámico
    const layout = computed(() => {
      return `${route.meta.layout || defaultLayout}-layout`
    })

    // Asegúrate de retornar 'layout' para que se pueda usar en el template
    return {
      layout
    }
  }
}
</script>

<template>
  <!-- Usamos el layout dinámico en el componente -->
  <component :is="layout">
    <router-view />
  </component>
</template>
