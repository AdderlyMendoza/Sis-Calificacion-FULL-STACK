<template>

  <div>

    <!-- cabecera -->
    <div class="flex justify-between ...">
      <h1 class="text-3xl font-medium text-gray-700">LISTA DE PROCESOS DE ADMISIÓN</h1>
      <router-link to="/procesoAdmisionNuevo"
        class="mb-2 me-2 block rounded-full bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out"
        aria-label="Nuevo proceso de admisión">
        Nuevo proceso de admisión
      </router-link>
    </div>


    <!-- tabla de procesos -->
    <div class="flex flex-col mt-8">
      <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
          <table class="min-w-full">
            <thead>
              <tr>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Nº
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Nombre
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Ubicación
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Acciones
                </th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(u, index) in procesosAdmision" :key="u?.id" class="hover:bg-gray-100">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index+1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.nombre }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.ubicacion }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <a :href="`/usuarioEditar/${u.id}`" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>


</template>


<script>
  import axios from 'axios';

  export default {
    name: 'V-ProcesoAdmision',
    data() {
      return {
        procesosAdmision: [],  // Inicializa el arreglo de usuarios
        error: null,
        loading: true, // Controla el estado de carga
      };
    },
    mounted() {
      // Hacer la petición al servidor para obtener los datos del usuario
      axios.get('http://127.0.0.1:8000/api/procesosAdmision', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('access_token')}` // Agregar token de autenticación
        }
      })
        .then(response => {
          console.log(response.data); // Verifica la respuesta de la API
          this.procesosAdmision = response.data;
        })
        .catch(error => {
          console.error('Error fetching user data:', error);
          this.error = 'Error al obtener datos del usuario';
        })
        .finally(() => {
          this.loading = false; // Cambiar el estado de carga al finalizar
        });
    },
  };
</script>