<template>
    <div>
        <div class="flex justify-between ...">
            <h1 class="text-3xl font-medium text-gray-700">EDITAR USUARIO</h1>
        </div>

        <div class="flex flex-col mt-8 border border-yellow-700">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <form @submit.prevent="editUser" class="space-y-4 text-gray-700 border border-red-500 p-8">

                    <div class="flex flex-wrap">
                        <div class="w-full">
                            <label class="block mb-1" for="apellido_paterno">Apellido Paterno</label>
                            <input
                                v-model="apellido_paterno"
                                class="w-full h-10 px-3 text-base placeholder-gray-600 border rounded-lg focus:shadow-outline"
                                type="text" id="apellido_paterno" />
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-full">
                            <label class="block mb-1" for="apellido_materno">Apellido Materno</label>
                            <input
                                v-model="apellido_materno"
                                class="w-full h-10 px-3 text-base placeholder-gray-600 border rounded-lg focus:shadow-outline"
                                type="text" id="apellido_materno" />
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-full">
                            <label class="block mb-1" for="name">Nombres</label>
                            <input
                                v-model="name"
                                class="w-full h-10 px-3 text-base placeholder-gray-600 border rounded-lg focus:shadow-outline"
                                type="text" id="name" />
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-full">
                            <label class="block mb-1" for="cargo">Cargo</label>
                            <input
                                v-model="cargo"
                                class="w-full h-10 px-3 text-base placeholder-gray-600 border rounded-lg focus:shadow-outline"
                                type="text" id="cargo" />
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-full">
                            <label class="block mb-1" for="email">Email</label>
                            <input
                                v-model="email"
                                class="w-full h-10 px-3 text-base placeholder-gray-600 border rounded-lg focus:shadow-outline"
                                type="email" id="email" />
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-full">
                            <label class="block mb-1" for="password">Contraseña</label>
                            <input
                                v-model="password"
                                class="w-full h-10 px-3 text-base placeholder-gray-600 border rounded-lg focus:shadow-outline"
                                type="password" id="password" />
                        </div>
                    </div>

                    <!-- Botones aceptar/cancelar -->
                    <div class="flex justify-end">
                        <div class="px-1">
                            <button type="button" @click="cancelar"
                                class="mb-2 me-2 rounded-full bg-red-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-red-700">
                                Cancelar
                            </button>
                        </div>
                        <div class="">
                            <button type="submit"
                                class="mb-2 me-2 rounded-full bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700">
                                Aceptar
                            </button>
                        </div>
                    </div>

                </form>
                <div v-if="error" class="text-red-500">{{ error }}</div> <!-- Mostrar mensajes de error -->
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'V-UsuarioEditar',
    data() {
        return {
            email: '',
            password: '',
            name: '',
            cargo: '',
            apellido_paterno: '',
            apellido_materno: '',
            error: null,
        };
    },
    mounted() {
        this.loadUserData(); // Cargar datos del usuario al montar el componente
    },
    methods: {
        async loadUserData() {
            const userId = this.$route.params.id; // Obtener el ID del usuario desde los parámetros de la ruta
            try {
                const response = await axios.get(`http://127.0.0.1:8000/api/users/${userId}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                    }
                });

                // Asignar los datos del usuario a las propiedades del formulario
                const user = response.data;
                console.log(user)
                this.apellido_paterno = user.user.apellido_paterno || ''; // Usar un valor por defecto
                this.apellido_materno = user.user.apellido_materno || '';
                this.name = user.user.name || '';
                this.cargo = user.user.cargo || '';
                this.email = user.user.email || '';
                // this.contraseña = user.user.contraseña || '';  // No cargar la contraseña por razones de seguridad
            } catch (err) {
                this.error = 'Error al cargar los datos del usuario';
            }
        },
        async editUser() {
            this.error = null; // Reiniciar el error antes de hacer la solicitud
            try {
                const userId = this.$route.params.id; // Obtener el ID del usuario a editar
                const response = await axios.put(`http://127.0.0.1:8000/api/users/${userId}`, {
                    email: this.email,
                    password: this.password, // Manejar correctamente la contraseña
                    name: this.name,
                    cargo: this.cargo,
                    apellido_paterno: this.apellido_paterno,
                    apellido_materno: this.apellido_materno,
                }, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                    }
                });

                console.log(response);

                // Redirige a la página principal o a la ruta deseada
                this.$router.push('/usuarios');
            } catch (err) {
                this.error = err.response.data.message || 'Error al editar el usuario';
            }
        },
        cancelar() {
            this.$router.push('/usuarios');
        }
    },
};
</script>
