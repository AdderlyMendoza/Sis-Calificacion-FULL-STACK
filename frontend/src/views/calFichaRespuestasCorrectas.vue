<template>
    <div>
        <h3 class="text-3xl font-medium text-gray-700">FICHAS DE RESPUESTAS CORRECTAS</h3>

        <!-- Respuestas correctas -->
        <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                        <div class="items-center justify-between py-3 bg-gray-50 border-b border-gray-200">
                            <div>
                                <h1>Elegir área</h1>
                                <select v-model="selectedArea"
                                    class="my-4 w-full text-sm text-gray-700 border border-gray-300 rounded-lg py-1 px-3">
                                    <option value="" disabled selected>Selecciona un área</option>
                                    <option value="ingenieria">Ingeniería</option>
                                    <option value="biomedicas">Biomédicas</option>
                                    <option value="sociales">Sociales</option>
                                </select>
                            </div>

                            <div>
                                <h1>Subir Respuestas Correctas</h1>
                                <form @submit.prevent="subirRptCorrectas" class="mt-4 flex items-center">
                                    <input type="file" @change="cargaDeFile('rptc')" multiple
                                        aria-label="Subir Fichas de Respuestas"
                                        class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
                                    <button type="submit"
                                        class="ml-4 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        Subir
                                    </button>
                                </form>
                                <div class="mt-4">
                                    <p v-if="message.rptc" class="text-green-500">{{ message.rptc }}</p>
                                    <p v-if="error.rptc" class="text-red-500">{{ error.rptc }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Mostrar datos / archivos u otro -->
        <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
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
                            <h2 class="font-bold mb-4">DATOS DE LAS FICHAS DE RESPUESTAS CORRECTAS</h2>
                            <table
                                class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">N°</th>
                                        <th class="py-2 px-4 border-b text-left">AREA</th>
                                        <th class="py-2 px-4 border-b text-left">LITHO</th>
                                        <th class="py-2 px-4 border-b text-left">TIPO</th>
                                        <th class="py-2 px-4 border-b text-left">RESPUESTAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="datos.length === 0">
                                        <td colspan="6" class="py-4 text-center text-gray-500">No hay datos disponibles.
                                        </td>
                                    </tr>
                                    <tr v-for="(item, i) in datos" :key="item.id">
                                        <td class="py-2 px-4 border-b text-left">{{ (currentPageDatos - 1) * perPage +(i + 1) }}</td>
                                        <!-- <td class="py-2 px-4 border-b text-left">{{ item.area_id }}</td> -->
                                        <td v-if="item.area_id == 1" class="py-2 px-4 border-b text-left">Ing</td>
                                        <td v-if="item.area_id == 2" class="py-2 px-4 border-b text-left">Bio</td>
                                        <td v-if="item.area_id == 3" class="py-2 px-4 border-b text-left">Soc</td>
                                        <td class="py-2 px-4 border-b text-left">{{ item.litho }}</td>
                                        <td class="py-2 px-4 border-b text-left">{{ item.tipo }}</td>
                                        <td class="py-2 px-4 border-b text-left">{{ item.respuestas }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="flex justify-between items-center mt-4">
                                <button @click="cambiarPagina(currentPageDatos - 1)" :disabled="currentPageDatos === 1"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                                    Anterior
                                </button>
                                <span class="text-gray-700">Página {{ currentPageDatos }} de {{ lastPageDatos
                                    }}</span>
                                <button @click="cambiarPagina(currentPageDatos + 1)"
                                    :disabled="currentPageDatos >= lastPageDatos"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                                    Siguiente
                                </button>
                            </div>
                        </div>


                        <!-- Sección de Archivos -->
                        <div v-if="activo === 'div2'" class="p-4">
                            <h2 class="font-bold mb-4">ARCHIVOS DE LAS FICHAS DE RESPUESTAS CORRECTAS</h2>
                            <table
                                class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">N°</th>
                                        <th class="py-2 px-4 border-b text-left">Archivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="archivos.length === 0">
                                        <td colspan="2" class="py-4 text-center text-gray-500">No hay archivos
                                            disponibles.</td>
                                    </tr>
                                    <tr v-for="(archivo, index) in archivos" :key="index">
                                        <td class="py-2 px-4 border-b text-left">{{ (currentPageArchivos - 1) *
                                            perPage
                                            + (index
                                            + 1) }}</td>
                                        <td class="py-2 px-4 border-b text-left">{{ archivo }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="flex justify-between items-center mt-4">
                                <button @click="cambiarPagina(currentPageArchivos - 1)"
                                    :disabled="currentPageArchivos === 1"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                                    Anterior
                                </button>
                                <span class="text-gray-700">Página {{ currentPageArchivos }} de {{ lastPageArchivos
                                    }}</span>
                                <button @click="cambiarPagina(currentPageArchivos + 1)"
                                    :disabled="currentPageArchivos >= lastPageArchivos"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                                    Siguiente
                                </button>
                            </div>
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

    export default {
        name: 'V-CalFichaRespuestasCorrectas',
        data() {
            return {
                activo: 'div1',
                datos: [], // datos de las fichas de identificacion
                archivos: [], // nombres de las fichas de identificacion
                // datos
                currentPageDatos: 1,
                lastPageDatos: 1,

                // archivos
                currentPageArchivos: 1,
                lastPageArchivos: 1,

                perPage: 5,

                files: {
                    id: [],
                    rpt: []
                },
                message: {
                    id: '',
                    rpt: ''
                },
                error: {
                    id: '',
                    rpt: ''
                }
            };
        },
        mounted() {
            this.obtenerDatos(); // Llamada a la API al montar el componente
            this.obtenerArchivos();
        },
        methods: {
            cargaDeFile(type) {
                this.files[type] = Array.from(event.target.files);
            },
            // async subirRptCorrectas() {
            //     const formData = this.createFormData(this.files.rptc);
            //     try {
            //         await axios.post('http://localhost:8000/api/fr-resp-correctas', formData, {
            //             headers: {
            //                 'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
            //                 'Content-Type': 'multipart/form-data',
            //             },
            //         });
            //         this.setMessage('rptc', 'Archivos cargados con éxito');
            //     } catch (error) {
            //         this.setError('rptc', error);
            //     }
            // },
            async subirRptCorrectas() { // enviar respuestas con area
                // Crear un nuevo FormData
                const formData = new FormData();

                // Agregar los archivos seleccionados al FormData (esto depende de cómo estés gestionando los archivos en `this.files.rptp`)
                if (this.files.rptc && this.files.rptc.length > 0) {
                    this.files.rptc.forEach(file => {
                        formData.append('files[]', file);
                    });
                }

                // Agregar el área seleccionada al FormData
                formData.append('frArea', this.selectedArea);

                try {
                    // Enviar el formulario con los archivos y el área seleccionada
                    await axios.post('http://localhost:8000/api/fr-resp-correctas', formData, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                            'Content-Type': 'multipart/form-data',
                        },
                    });
                    this.setMessage('rptc', 'Archivos cargados con éxito');
                } catch (error) {
                    this.setError('rptc', error);
                }
            },
            createFormData(files) {
                const formData = new FormData();
                files.forEach(file => {
                    formData.append('files[]', file);
                });
                return formData;
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

            ///////////////////////////////////////////////// MOSTRAR DATOS
            async obtenerDatos() {
                this.isLoading = true; // Start loading
                try {
                    const response = await axios.get(`http://localhost:8000/api/datosFichaRespuestasCorrectas?per_page=${this.perPage}&page=${this.currentPageDatos}`, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                        },
                    });
                    this.datos = response.data.data;
                    this.currentPageDatos = response.data.current_page;
                    this.lastPageDatos = response.data.last_page;
                    console.log("OBTENER DATOS: ", this.currentPageDatos, this.lastPageDatos);

                } catch (error) {
                    console.error('Error al obtener los datos:', error);
                } finally {
                    this.isLoading = false; // End loading
                }
            },

            ///////////////////////////////////////////////// MOSTRAR ARCHIVOS
            async obtenerArchivos() {
                this.isLoading = true;
                try {
                    const response = await axios.get(`http://localhost:8000/api/listarFichasRespuestasCorrectas?per_page=${this.perPage}&page=${this.currentPageArchivos}`, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                        },
                    });
                    this.archivos = response.data.data;
                    this.currentPageArchivos = response.data.current_page;
                    this.lastPageArchivos = response.data.last_page;
                } catch (error) {
                    console.error('Error al obtener archivos:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            async cambiarPagina(page) {
                if (this.activo === 'div1') {
                    if (page < 1 || page > this.lastPageDatos) return;
                    this.currentPageDatos = page;
                    await this.obtenerDatos();
                } else if (this.activo === 'div2') {
                    if (page < 1 || page > this.lastPageArchivos) return;
                    this.currentPageArchivos = page;
                    await this.obtenerArchivos();
                }
            },
        },
    };
</script>