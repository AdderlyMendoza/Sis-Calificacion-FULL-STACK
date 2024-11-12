<template>
  <div>
    <h3 class="text-3xl font-medium text-gray-700">DISTRIBUCIÓN DE AULAS</h3>


    <!-- seleccionar campos INGENIERIAS-->
    <div class="flex flex-col mt-8">
      <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
          <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">


            <!-- Botones para mostrar diferentes secciones -->
            <div class="flex space-x-4 mb-4">
              <button @click="mostrarDiv('div1')" class="bg-blue-500 text-white px-4 py-2 rounded">
                Mostrar Datos
              </button>
              <button @click="mostrarDiv('div2')" class="bg-green-500 text-white px-4 py-2 rounded">
                Mostrar Archivos
              </button>
              <button @click="mostrarDiv('div3')" class="bg-red-500 text-white px-4 py-2 rounded">
                Mostrar Otro
              </button>
            </div>

            <!-- Sección de Datos -->
            <div v-if="activo === 'div1'" class="p-4">
              <h2 class="font-bold mb-4">GENERAR CODIGO : AREA INGENIERIAAS </h2>
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


            <!-- Sección de Archivos -->
            <div v-if="activo === 'div2'" class="p-4">
              <h2 class="font-bold mb-4">ARCHIVOS DE LAS FICHAS DE IDENTIFICACIÓN</h2>

            </div>



            <!-- Sección de Otro -->
            <div v-if="activo === 'div3'" class="p-4 bg-gray-400">
              <h2>Otro</h2>
              <p>Contenido del tercer div.</p>
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
        activo: 'div1',
        loading: true,
        error: null,
        totalItems: 0,
        campos: [],
      };
    },
    mounted() {
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
      },
      setMessage(type, msg) {
        this.message[type] = msg;
        this.error[type] = '';
        this.files[type] = []; // Limpiar archivos después de la carga
        setTimeout(() => {
          this.message[type] = '';
        }, 5000); // Clear message after 5 seconds
      },
      setError(type, error) {
        console.error('Error:', error);
        this.error[type] = error.response?.data?.message || 'Error en la carga de archivos';
        this.message[type] = '';
      },
      mostrarDiv(div) {
        this.activo = div;
      },
    }
  }
</script>