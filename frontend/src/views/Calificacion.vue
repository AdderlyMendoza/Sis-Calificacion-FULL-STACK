<template>
    <div>
        <h3 class="text-3xl font-medium text-gray-700">CALIFICAR</h3>

        <!-- Subir ficha de Identificación -->
        <!-- <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                        <h1>Subir Fichas de Identificación</h1>
                        <form @submit.prevent="subirIdPostulantes">
                            <input type="file" @change="cargaDeFile('id')" multiple
                                aria-label="Subir Fichas de Identificación" />
                            <button type="submit">Subir</button>
                        </form>
                        <p v-if="message.id" class="text-green-500">{{ message.id }}</p>
                        <p v-if="error.id" class="text-red-500">{{ error.id }}</p>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                        <h1>Subir Fichas de Identificación</h1>
                        <form @submit.prevent="subirIdPostulantes" class="mt-4 flex items-center">
                            <input type="file" @change="cargaDeFile('id')" multiple
                                aria-label="Subir Fichas de Respuestas"
                                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" />
                            <button type="submit"
                                class="ml-4 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Subir
                            </button>
                        </form>
                        <div class="mt-4">
                            <p v-if="message.id" class="text-green-500">{{ message.id }}</p>
                            <p v-if="error.id" class="text-red-500">{{ error.id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subir fichas de respuestas -->
        <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
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

        <!-- Subir respuestas correctas -->
        <!-- <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                        <h1>Subir Respuestas Correctas</h1>
                        <form @submit.prevent="subirRptCorrectas">
                            <input type="file" @change="cargaDeFile('rptc')" multiple
                                aria-label="Subir Respuestas Correctas" />
                            <button type="submit">Subir</button>
                        </form>
                        <p v-if="message.rptc" class="text-green-500">{{ message.rptc }}</p>
                        <p v-if="error.rptc" class="text-red-500">{{ error.rptc }}</p>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
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
        name: 'V-Calificacion',
        data() {
            return {
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
        methods: {
            cargaDeFile(type) {
                this.files[type] = Array.from(event.target.files);
            },
            async subirIdPostulantes() {
                const formData = this.createFormData(this.files.id);
                try {
                    await axios.post('http://localhost:8000/api/fr-id-postulantes', formData, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                            'Content-Type': 'multipart/form-data',
                        },
                    });
                    this.setMessage('id', 'Archivos cargados con éxito');
                } catch (error) {
                    this.setError('id', error);
                }
            },
            async subirRptPostulantes() {
                const formData = this.createFormData(this.files.rptp);
                try {
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
            async subirRptCorrectas() {
                const formData = this.createFormData(this.files.rptc);
                try {
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
            async exportarDatos(tipo) {
                try {
                    const response = await axios.post(`http://localhost:8000/api/fr-out-resultados/${tipo}`, null, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                        },
                        responseType: 'blob',
                    });

                    // Crear un enlace para descargar el archivo
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', `resultados.${tipo}`); // Nombre del archivo a descargar
                    document.body.appendChild(link);
                    link.click();
                    link.remove(); // Limpiar el DOM
                    window.URL.revokeObjectURL(url); // Liberar memoria

                    console.log('Archivo exportado con éxito', response.data); // Manejar la respuesta aquí
                } catch (error) {
                    console.error('Error al exportar el archivo:', error); // Mensaje de error
                }
            }
        },
    };
</script>