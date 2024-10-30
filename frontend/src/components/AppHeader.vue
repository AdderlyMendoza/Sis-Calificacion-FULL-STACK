<template>
  <header class="flex items-center justify-between px-6 py-4 bg-white border-b-4 border-indigo-600">
    <div class="flex items-center">
      <!-- Botón para menú responsive -->
      <button class="text-gray-500 focus:outline-none lg:hidden" @click="isOpen = true">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>

    <div class="flex items-center">
      <!-- Mostrar nombre del usuario cuando esté cargado -->
      <div v-if="userData" class="flex mx-2 text-gray-600 focus:outline-none">
        <p>{{ userData.name }}</p>
      </div>
      <div v-else class="flex mx-2 text-gray-600 focus:outline-none">
        <h1>Cargando...</h1>
      </div>

      <!-- Dropdown para perfil y logout -->
      <div class="relative">
        <button class="relative z-10 block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none"
          @click="toggleDropdown">
          <img class="object-cover w-full h-full"
            src="https://images.unsplash.com/photo-1528892952291-009c663ce843?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=296&q=80"
            alt="Your avatar" />
        </button>

        <!-- Overlay para cerrar el dropdown -->
        <div v-show="dropdownOpen" class="fixed inset-0 z-10" @click="closeDropdown"></div>

        <!-- Menú desplegable -->
        <transition enter-active-class="transition duration-150 ease-out transform"
          enter-from-class="scale-95 opacity-0" enter-to-class="scale-100 opacity-100"
          leave-active-class="transition duration-150 ease-in transform" leave-from-class="scale-100 opacity-100"
          leave-to-class="scale-95 opacity-0">
          <div v-show="dropdownOpen" class="absolute right-0 z-20 w-48 py-2 mt-2 bg-white rounded-md shadow-xl">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
              Perfil
            </a>
            <router-link to="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white" @click="logout">
              Log out
            </router-link>
          </div>
        </transition>
      </div>
    </div>
  </header>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      userData: null,
      error: null,
      dropdownOpen: false, // Controla la apertura del dropdown
    };
  },
  mounted() {
    // Hacer la petición al servidor para obtener los datos del usuario
    axios.get('http://127.0.0.1:8000/api/user', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('access_token')}`
      }
    })
    .then(response => {
      // Guardar los datos del usuario en la variable `userData`
      this.userData = response.data;
    })
    .catch(error => {
      console.error('Error fetching user data:', error);
      this.error = 'Error al obtener datos del usuario';
    });
  },
  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
      this.dropdownOpen = false;
    },
    logout() {
      // Eliminar el token de localStorage
      localStorage.removeItem('access_token');
      
      // Redirigir al usuario a la página de login o inicio
      this.$router.push('/');  // Aquí puedes especificar la ruta donde quieres redirigir
    }
  }
};
</script>

<style scoped>
/* Estilos opcionales para el componente */
</style>
