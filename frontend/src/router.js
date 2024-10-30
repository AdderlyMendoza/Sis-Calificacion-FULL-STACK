// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

// Componentes que se utilizarán en las rutas
import Login from './views/Login.vue'
import Dashboard from './views/Dashboard.vue'
import DistribucionAulas from './views/DistribucionAulas1.vue'
import Usuarios from './views/Usuarios.vue'
import UsuarioNuevo from './views/UsuarioNuevo.vue'
import UsuarioEditar from './views/UsuarioEditar.vue'
import Calificacion from './views/Calificacion.vue'
import CalFichaIdentificacion from './views/calFichaIdentificacion.vue'
import CalFichaRespuestas from './views/calFichaRespuestas.vue'


const routes = [
  {
    path: '/',
    name: 'V-Login',
    component: Login,
    meta: { layout: 'empty' },
  },
  {
    path: '/dashboard',
    name: 'V-Dashboard',
    component: Dashboard,
  },
  {
    path: '/distribucionAulas',
    name: 'V-DistribucionAulas',
    component: DistribucionAulas,
  },
  {
    path: '/usuarios',
    name: 'V-Usuarios',
    component: Usuarios,
  },
  {
    path: '/usuarioNuevo',
    name: 'V-UsuarioNuevo',
    component: UsuarioNuevo,
  },
  {
    path: '/usuarioEditar/:id',
    name: 'V-UsuarioEditar',
    component: UsuarioEditar,
  },
  {
    path: '/calificacion',
    name: 'V-Calificacion',
    component: Calificacion,
  },
  {
    path: '/Calificacion/FichaIdentificacion',
    name: 'V-CalFichaIdentificacion',
    component: CalFichaIdentificacion,
  },
  {
    path: '/Calificacion/FichaRespuestas',
    name: 'V-CalFichaRespuestas',
    component: CalFichaRespuestas,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router

