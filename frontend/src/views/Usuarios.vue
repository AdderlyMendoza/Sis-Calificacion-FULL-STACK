<template>
    <div>

        <!-- cabecera -->
        <div class="flex justify-between ...">
            <h1 class="text-3xl font-medium text-gray-700">LISTA DE USUARIOS</h1>
            <router-link to="/usuarioNuevo"
                class="mb-2 me-2 block rounded-full bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out"
                aria-label="Nuevo usuario">
                Nuevo usuario
            </router-link>
        </div>

        <!-- tabla de usuarios -->
        <div class="flex flex-col mt-8">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Nº
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Paterno
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Materno
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Nombre
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Cargo
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Email
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(u, index) in users" :key="u?.id" class="hover:bg-gray-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index+1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.apellido_paterno }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.apellido_materno }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.cargo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ u?.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a :href="`/usuarioEditar/${u.id}`"
                                        class="text-indigo-600 hover:text-indigo-900">Editar</a>
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
        name: 'V-Usuarios',
        data() {
            return {
                users: [],  // Inicializa el arreglo de usuarios
                error: null,
                loading: true, // Controla el estado de carga
            };
        },
        mounted() {
            // Hacer la petición al servidor para obtener los datos del usuario
            axios.get('http://127.0.0.1:8000/api/users', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}` // Agregar token de autenticación
                }
            })
                .then(response => {
                    console.log(response.data); // Verifica la respuesta de la API
                    // Guardar los datos de los usuarios en la variable `users`
                    this.users = response.data;
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