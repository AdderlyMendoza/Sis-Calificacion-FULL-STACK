<template>
    <div>
        <h3 class="text-3xl font-medium text-gray-700">FICHAS DE RESPUESTAS</h3>

        <!-- Subir fichas de respuestas -->
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
                                <h1>Subir Fichas de Respuestas</h1>
                                <form @submit.prevent="subirRptPostulantes" class="mt-4 flex items-center">
                                    <input type="file" @change="cargaDeFile('rptp')" multiple
                                        aria-label="Subir Fichas de Respuestas"
                                        class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
                                    <button type="submit"
                                        class="ml-4 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        Subir
                                    </button>
                                </form>
                                <div class="mt-4">
                                    <p v-if="message.rptp" class="text-green-500">{{ message.rptp }}</p>
                                    <p v-if="error.rptp" class="text-red-500">{{ error.rptp }}</p>
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
                        <div class="flex justify-between">
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
                            <div class="text-left">
                                <button @click="exportarErrores()" class="bg-yellow-500 text-white px-4 py-2 rounded">
                                    Exportar Errores
                                </button>
                            </div>
                        </div>

                        <!-- Sección de Datos -->
                        <div v-if="activo === 'div1'" class="p-4">
                            <h2 class="font-bold mb-4">DATOS DE LAS FICHAS DE RESPUESTAS Y ERRORES</h2>
                            <table
                                class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">N°</th>
                                        <th class="py-2 px-4 border-b text-left">DNI</th>
                                        <th class="py-2 px-4 border-b text-left">LITHO</th>
                                        <th class="py-2 px-4 border-b text-left">TIPO</th>
                                        <th class="py-2 px-4 border-b text-left">RESPUESTAS</th>
                                        <th class="py-2 px-4 border-b text-left">ERRORES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="datos.length === 0">
                                        <td colspan="6" class="py-4 text-center text-gray-500">No hay datos
                                            disponibles.
                                        </td>
                                    </tr>
                                    <tr v-for="(item, i) in datos" :key="item.id">
                                        <td class="py-2 px-4 border-b text-left">{{ (currentPageDatos - 1) * perPage
                                            +
                                            (i + 1) }}</td>
                                        <td class="py-2 px-4 border-b text-left">{{ item.dni_identificacion }}</td>
                                        <td class="py-2 px-4 border-b text-left">{{ item.litho }}</td>
                                        <td class="py-2 px-4 border-b text-left">{{ item.tipo }}</td>
                                        <td class="py-2 px-4 border-b text-left">{{ item.respuestas }}</td>
                                        <td class="py-2 px-4 border-b text-center">
                                            <div v-if="item.tipo !== item.tipo_identificacion"
                                                class="rounded border border-red-400 bg-red-100 p-1 text-red-700 mx-1 text-xs">
                                                <strong class="font-bold">TIPO</strong>
                                                <!-- <p class="text-xs">R: {{ item.tipo }}, I: {{ item.tipo_identificacion }}</p> -->
                                            </div>
                                        </td>
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
                            <h2 class="font-bold mb-4">ARCHIVOS DE LAS FICHAS DE IDENTIFICACIÓN</h2>
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


        <!-- Exportar resultados de calificacion -->
        <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h1 class="text-xl font-semibold text-gray-800">Exportar Resultados</h1>
                        <div class="mt-4 flex space-x-4">
                            <button @click="exportarDatos('excel')"
                                class="flex items-center bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v8m0 0l-4-4m4 4l4-4M4 6h16M4 10h16M4 14h16M4 18h16M4 22h16"></path>
                                </svg>
                                Excel
                            </button>
                            <button @click="exportarDatos('pdf')"
                                class="flex items-center bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v8m0 0l-4-4m4 4l4-4M4 6h16M4 10h16M4 14h16M4 18h16M4 22h16"></path>
                                </svg>
                                PDF
                            </button>
                            <button @click="exportarDatos('txt')"
                                class="flex items-center bg-gray-500 text-white p-2 rounded-lg hover:bg-gray-600 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v8m0 0l-4-4m4 4l4-4M4 6h16M4 10h16M4 14h16M4 18h16M4 22h16"></path>
                                </svg>
                                TXT
                            </button>
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
        name: 'V-CalFichaRespuestas',
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
            // async subirRptPostulantes() { // enviar solo respuestas
            //     const formData = this.createFormData(this.files.rptp);
            //     try {
            //         await axios.post('http://localhost:8000/api/fr-resp-postulantes', formData, {
            //             headers: {
            //                 'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
            //                 'Content-Type': 'multipart/form-data',
            //             },
            //         });
            //         this.setMessage('rptp', 'Archivos cargados con éxito');
            //     } catch (error) {
            //         this.setError('rptp', error);
            //     }
            // },
            async subirRptPostulantes() { // enviar respuestas con area
                // Crear un nuevo FormData
                const formData = new FormData();

                // Agregar los archivos seleccionados al FormData (esto depende de cómo estés gestionando los archivos en `this.files.rptp`)
                if (this.files.rptp && this.files.rptp.length > 0) {
                    this.files.rptp.forEach(file => {
                        formData.append('files[]', file);
                    });
                }

                // Agregar el área seleccionada al FormData
                formData.append('frArea', this.selectedArea);

                try {
                    // Enviar el formulario con los archivos y el área seleccionada
                    await axios.post('http://localhost:8000/api/fr-resp-postulantes', formData, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                            'Content-Type': 'multipart/form-data',
                        },
                    });
                    this.setMessage('rptp', 'Archivos cargados con éxito');
                } catch (error) {
                    this.setError('rptp', error);
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
            async exportarDatos(tipo) {
                try {
                    const response = await axios.post(`http://localhost:8000/api/fr-out-resultados/${tipo}`, null, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                        },
                        responseType: 'blob',
                    });

                    // Determinar la extensión del archivo basándonos en el tipo
                    let extension = '';
                    let fileName = '';

                    switch (tipo) {
                        case 'pdf':
                            extension = 'pdf';
                            fileName = 'resultados.pdf'; // Nombre del archivo PDF
                            break;
                        case 'txt':
                            extension = 'txt';
                            fileName = 'resultados.txt'; // Nombre del archivo TXT
                            break;
                        case 'excel':
                            extension = 'xlsx';
                            fileName = 'resultados.xlsx'; // Nombre del archivo Excel
                            break;
                        default:
                            extension = tipo; // En caso de que haya otros tipos de archivo, usamos el tipo proporcionado
                            fileName = `resultados.${extension}`;  // Asigna un nombre genérico
                    }

                    // Crear un enlace para descargar el archivo
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', fileName);  // Usar el nombre del archivo con la extensión correcta
                    document.body.appendChild(link);
                    link.click();
                    link.remove();  // Limpiar el DOM
                    window.URL.revokeObjectURL(url);  // Liberar memoria

                    console.log('Archivo exportado con éxito', response.data); // Manejar la respuesta aquí
                } catch (error) {
                    console.error('Error al exportar el archivo:', error); // Mensaje de error
                }
            },

            mostrarDiv(div) {
                this.activo = div;
            },

            ///////////////////////////////////////////////// MOSTRAR DATOS
            async obtenerDatos() {
                this.isLoading = true; // Start loading
                try {
                    const response = await axios.get(`http://localhost:8000/api/datosFichaRespuestas?per_page=${this.perPage}&page=${this.currentPageDatos}`, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                        },
                    });
                    this.datos = response.data.data;
                    this.currentPageDatos = response.data.current_page;
                    this.lastPageDatos = response.data.last_page;
                    // console.log("OBTENER DATOS: ", this.currentPageDatos, this.lastPageDatos);

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
                    const response = await axios.get(`http://localhost:8000/api/listarFichasRespuestas?per_page=${this.perPage}&page=${this.currentPageArchivos}`, {
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

            ///////////////////////////////////////////////// EXPORTAR ERRORES
            async exportarErrores() {
                try {
                    const response = await axios.post(`http://localhost:8000/api/fr-out-resultadosErrores/`, null, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                        },
                        responseType: 'blob',
                    });

                    let fileName = 'erroresFichasRespuestas.pdf'; // formato pdf

                    // Crear un enlace para descargar el archivo
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', fileName);  // Usar el nombre del archivo con la extensión correcta
                    document.body.appendChild(link);
                    link.click();
                    link.remove();  // Limpiar el DOM
                    window.URL.revokeObjectURL(url);  // Liberar memoria

                    console.log('Archivo de errores exportado con éxito', response.data); // Manejar la respuesta aquí
                } catch (error) {
                    console.error('Error al exportar el archivo de errores:', error); // Mensaje de error
                }
            },
        },
    };
</script>