<template>
    <div>
        <div class="flex justify-between ...">
            <h1 class="text-3xl font-medium text-gray-700">CREAR UN NUVEO USUARIO</h1>
        </div>

        <div class="flex flex-col mt-8 border border-yellow-700">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <form @submit.prevent="newUser" class="space-y-4 text-gray-700 border border-red-500 p-8">

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
        name: 'V-UsuarioNuevo',
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
        methods: {
            async newUser() {
                this.error = null; // Reiniciar el error antes de hacer la solicitud
                try {
                    const response = await axios.post('http://127.0.0.1:8000/api/users', {
                        email: this.email,
                        password: this.password,
                        name: this.name,
                        cargo: this.cargo,
                        apellido_paterno: this.apellido_paterno,
                        apellido_materno: this.apellido_materno,
                    }, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('access_token')}` // Agregar token de autenticación
                        }
                    });

                    console.log(response);

                    // Redirige a la página principal o a la ruta deseada
                    this.$router.push('/usuarios');
                } catch (err) {
                    this.error = err.response.data.message || 'Error al crear el usuario';
                }
            },
            cancelar() {
                this.$router.push('/usuarios');
            }
        },
    };
</script>
