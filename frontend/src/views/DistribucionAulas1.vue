<template>
  <div>
    <h3 class="text-3xl font-medium text-gray-700">DISTRIBUCIÓN DE AULAS</h3>

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

    <!-- seleccionar campos INGENIERIAS-->
    <div class="flex flex-col mt-8">
      <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
          <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">



            <div class="p-4">
              <h3 class="text-lg font-semibold mb-2">Selecciona Campos</h3>
              <form @submit.prevent="submitForm">
                <table>
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="border-b border-gray-300 p-2 text-center">N°</th>
                      <th class="border-b border-gray-300 p-2 text-center">Campo</th>
                      <th class="border-b border-gray-300 p-2 text-center">Inicio</th>
                      <th class="border-b border-gray-300 p-2 text-center">Medio</th>
                      <th class="border-b border-gray-300 p-2 text-center">Final</th>
                      <th class="border-b border-gray-300 p-2 text-center">Hasta</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(campo, index) in campos" :key="campo.nombre">
                      <td class="p-2">
                        {{ index + 1 }}
                      </td>
                      <td class="p-2">
                        <input type="checkbox" v-model="campo.seleccionado" @change="actualizarListaSeleccionados"
                          class="mr-2" />
                        {{ campo.nombre }}
                      </td>

                      <td class="p-2 text-center">
                        <input v-if="campo.seleccionado" id="inicio-checkbox-{{ campo.nombre }}-{{ index }}"
                          type="checkbox" :checked="campo.pivot === 'INICIO'" @change="setPivot(index, 'INICIO')"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" />
                      </td>
                      <td class="p-2 text-center">
                        <input v-if="campo.seleccionado" id="medio-checkbox-{{ campo.nombre }}-{{ index }}"
                          type="checkbox" :checked="campo.pivot === 'MEDIO'" @change="setPivot(index, 'MEDIO')"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" />
                      </td>
                      <td class="p-2 text-center">
                        <input v-if="campo.seleccionado" id="final-checkbox-{{ campo.nombre }}-{{ index }}"
                          type="checkbox" :checked="campo.pivot === 'FINAL'" @change="setPivot(index, 'FINAL')"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" />
                      </td>

                      <td class="p-2">
                        <input v-if="campo.seleccionado" type="text" v-model="campo.valor"
                          class="border border-gray-300 rounded p-1 w-10 h-10" />
                      </td>
                    </tr>
                  </tbody>
                </table>

                <!-- Ordenar datos seleccionado -->
                <h3 class="text-lg font-semibold mb-2">Ordena los campos</h3>
                <ul
                  class="join rounded-full bg-green-700 divide-x [&_*]:p-2 [&_*]:cursor-pointer text-white select-none flex w-max"
                  id="orderList">
                </ul>
                <div id="orderMsg" class="!m-0 text-error text-red-500">
                  No se seleccionó ningún campo
                </div>

                <!-- Enviar -->
                <button type="submit" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded">Enviar</button>
              </form>

              <div v-if="error" class="text-red-500 mt-2 font-semibold">{{ error }}</div>
            </div>


          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import axios from 'axios';
  import Sortable from 'sortablejs'; // Ensure you have sortablejs installed

  export default {
    name: 'V-DistribucionAulas',
    data() {
      return {
        postulantes: [],
        loading: true,
        error: null,
        currentPage: 1,
        perPage: 7,
        totalItems: 0,
        campos: [],
      };
    },
    mounted() {
      this.fetchUsers(this.currentPage);
      this.obtenerCampos();
      this.initSortable();
    },
    methods: {
      initSortable() {
        const orderList = document.getElementById('orderList');
        new Sortable(orderList, {
          animation: 150,
          onEnd: () => this.ordenarEnviar(),
        });
      },
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
            console.error('Error fetching user data:', error);
            this.error = 'Error al obtener datos del usuario';
          })
          .finally(() => {
            this.loading = false;
          });
      },
      uploadFile() {
        const file = this.$refs.file.files[0];
        const formData = new FormData();
        formData.append('file', file);

        axios.post('http://localhost:8000/api/upload-excel', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          },
        })
          .then(response => {
            alert(response.data.success);
            this.fetchUsers(this.currentPage);
          })
          .catch(error => {
            console.error(error);
            alert('Error al subir el archivo.');
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
      obtenerCampos() {
        axios.get('http://localhost:8000/api/obtener-campos', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          },
        })
          .then(response => {
            this.campos = response.data.map(campo => ({
              nombre: campo,
              seleccionado: false,
              valor: '',
              pivot: '',
            }));
          })
          .catch(error => {
            console.error('Error al obtener los campos:', error);
            this.error = 'Error al obtener los campos';
          });
      },
      actualizarListaSeleccionados() {
        const lista = this.campos.filter(campo => campo.seleccionado);
        document.getElementById('orderMsg').style.display = lista.length ? 'none' : 'block';

        const orderList = document.getElementById('orderList');
        orderList.innerHTML = ''; // Clear previous list

        lista.forEach(campo => {
          const li = document.createElement('li');
          li.textContent = campo.nombre;
          orderList.appendChild(li);
        });
      },
      setPivot(index, pivot) {
        this.campos[index].pivot = pivot; // Set the selected pivot
      },
      ordenarEnviar() {
        const orderList = document.getElementById('orderList');
        const orderArray = Array.from(orderList.children).map(child => child.textContent);
        console.log('Nuevo orden:', orderArray);
        return orderArray; // Devuelve el nuevo orden
      },
      submitForm() {
        const nuevoOrden = this.ordenarEnviar();
        console.log(nuevoOrden);

        const dataToSend = this.campos
          .filter(campo => campo.seleccionado)
          .map(campo => ({
            campo: campo.nombre,
            desde: campo.pivot,
            hasta: campo.valor,
          }));

        console.log(dataToSend);

        // Crear un mapeo de nombres a índices
        const indexMap = {};
        dataToSend.forEach((item, index) => {
          indexMap[item.campo] = index;
        });

        // Ordenar dataToSend según nuevoOrden
        const orderedDataToSend = nuevoOrden.map(name => dataToSend[indexMap[name]]).filter(Boolean);

        console.log('Datos ordenados:', orderedDataToSend);

        axios.post('http://localhost:8000/api/fr-campos-seleccionados', orderedDataToSend, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          }
        })
          .then(response => {
            console.log('Success:', response.data);
          })
          .catch(error => {
            console.error('Error:', error.response ? error.response.data : error.message);
          });
      }
    }
  }
</script>