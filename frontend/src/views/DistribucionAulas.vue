<template>
  <form @submit.prevent="onSubmit" class="p-4 bg-white shadow rounded">

    <div v-for="(input, index) in inputs" :key="input.id" class="flex items-baseline mb-4">

      <!-- Select para seleccionar campo -->
      <div class="flex-1">
        <label :for="'campo' + index" class="block text-sm font-medium text-gray-700">Campo</label>
        <select v-model="input.selectedCampo" :id="'campo' + index"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          <option value="" disabled>Selecciona un campo</option>
          <option v-for="campoOption in campos" :key="campoOption.nombre" :value="campoOption.nombre">
            {{ campoOption.nombre }}
          </option>
        </select>
      </div>


      <!-- Checkbox para setPivot -->
      <div class="ml-2">
        <label class="block text-sm font-medium text-gray-700">Inicio</label>
        <input type="checkbox" :id="'inicio-checkbox-' + index" v-model="input.pivot"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" />
      </div>
      <div class="ml-2">
        <label class="block text-sm font-medium text-gray-700">Medio</label>
        <input type="checkbox" :id="'medio-checkbox-' + index" v-model="input.pivot"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" />
      </div>
      <div class="ml-2">
        <label class="block text-sm font-medium text-gray-700">Final</label>
        <input type="checkbox" :id="'final-checkbox-' + index" v-model="input.pivot"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" />
      </div>

      <!-- Botón para quitar campo -->
      <button type="button" @click="removeInput(input)" class="ml-2 text-red-500 hover:text-red-700">
        X
      </button>
    </div>

    <!-- Botón para agregar campo -->
    <button type="button" @click="addInput"
      class="w-full py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-600">
      Agregar campo
    </button>

    <!-- Botón para enviar -->
    <button type="submit" class="mt-4 w-full py-2 bg-green-500 text-white font-bold rounded hover:bg-green-600">
      Enviar
    </button>
  </form>
</template>

<script setup>
  import { reactive, onMounted } from 'vue';
  import axios from 'axios';

  const inputs = reactive([{ selectedCampo: '', price: '', pivot: false, id: Date.now() }]);
  const campos = reactive([]); // Array para almacenar los campos obtenidos

  // Función para obtener campos desde la API
  const obtenerCampos = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/obtener-campos', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
        },
      });

      // Procesar la respuesta y asignar a `campos`
      campos.push(...response.data.map(campo => ({ nombre: campo }))); // Ajusta según la estructura de tu API
    } catch (error) {
      console.error('Error al obtener los campos:', error);
      // Maneja el error como necesites
    }
  };

  // Llama a la función al montar el componente
  onMounted(() => {
    obtenerCampos();
  });

  // Función para agregar un nuevo input
  const addInput = () => {
    inputs.push({ selectedCampo: '', price: '', pivot: false, id: Date.now() });
  };

  // Función para eliminar un input
  const removeInput = (input) => {
    const index = inputs.indexOf(input);
    if (index !== -1) {
      inputs.splice(index, 1);
    }
  };

  // Función para manejar el envío del formulario
  const onSubmit = () => {
    console.log('Valores del formulario:', inputs);
    // Aquí puedes manejar el envío de datos como necesites
  };
</script>