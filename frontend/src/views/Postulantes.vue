<template>
  <div>
    <h3 class="text-3xl font-medium text-gray-700">SUBIR POSTULANTES</h3>

    <!-- Subir excel -->
    <div class="flex flex-col mt-8">
      <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">

          <!-- Subir datos de postulantes por medio de un excel -->
          <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <form @submit.prevent="uploadFile" class="flex items-center">
              <input type="file" ref="file" class="border border-gray-300 rounded-md px-3 py-2 mr-2" />
              <button type="submit"
                class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition duration-200">
                Subir Excel
              </button>
            </form>
          </div>

          <!-- tabla de postulantes -->
          <table class="min-w-full">
            <thead>
              <tr>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Nº</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  DNI</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Paterno</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Materno</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Nombre</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Ubigeo</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Colegio</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Celular</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Email</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Carrera</th>
                <th
                  class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                  Codigo</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(u, index) in postulantes" :key="u?.id" class="hover:bg-gray-100">
                <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td> -->
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 ">{{ (currentPage - 1) * perPage + index +
                  1
                  }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.dni }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.paterno }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.materno }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.ubigeo }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.colegio }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.celular }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.carrera }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.codigo }}</td>
              </tr>
            </tbody>
          </table>
          
          <div v-if="loading" class="absolute inset-0 flex justify-center items-center bg-gray-100 bg-opacity-50">
            <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600"></div>
          </div>

          <!-- Paginacion -->
          <div class="flex justify-end mt-4">
            <button @click="previousPage" :disabled="currentPage === 1"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 disabled:opacity-50 transition duration-200 mr-2">
              Anterior
            </button>
            <div class="flex items-center">
              <h1 class="text-lg font-semibold text-gray-800">Página {{ currentPage }}</h1>
            </div>
            <button @click="nextPage" :disabled="currentPage * perPage >= totalItems"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 disabled:opacity-50 transition duration-200 ml-2">
              Siguiente
            </button>
          </div>

        </div>
      </div>
    </div>


  </div>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'V-Postulantes',
    data() {
      return {
        postulantes: [],
        loading: true,
        error: null,
        currentPage: 1,
        perPage: 7,
        totalItems: 0,
      };
    },
    mounted() {
      this.fetchUsers(this.currentPage);
    },
    methods: {
      fetchUsers(page) {
        axios.get(`http://127.0.0.1:8000/api/postulantes?page=${page}&per_page=${this.perPage}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        })
          .then(response => {
            this.postulantes = response.data.data;
            this.totalItems = response.data.total;
          })
          .catch(error => {
            console.error('Error al obtener los postulantes:', error);
            this.error = 'Error al obtener los postulantes. Inténtalo de nuevo más tarde.';
            alert(this.error);
          })
          .finally(() => {
            this.loading = false;
          });
      },
      uploadFile() {
        const file = this.$refs.file.files[0];
        if (!file) {
          alert('Por favor selecciona un archivo');
          return;
        }
        const formData = new FormData();
        formData.append('file', file);

        this.loading = true; // Mostrar indicador de carga

        axios.post('http://localhost:8000/api/upload-excel', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          },
        })
          .then(response => {
            alert(response.data.success);
            this.fetchUsers(this.currentPage); // Refrescar la lista de postulantes
          })
          .catch(error => {
            console.error(error);
            alert('Error al subir el archivo.');
          })
          .finally(() => {
            this.loading = false; // Esconder indicador de carga
          });
      },
      nextPage() {
        if (this.currentPage * this.perPage < this.totalItems) {
          this.currentPage++;
          this.fetchUsers(this.currentPage);
        }
      },
      previousPage() {
        if (this.currentPage > 1) {
          this.currentPage--;
          this.fetchUsers(this.currentPage);
        }
      },
    }
  }
</script>