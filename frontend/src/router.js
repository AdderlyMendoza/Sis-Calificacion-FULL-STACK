// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

// Componentes que se utilizarán en las rutas
import Login from './views/Login.vue'
import Dashboard from './views/Dashboard.vue'
import DistribucionAulas from './views/DistribucionAulas.vue' // eliminar el que no se esta utilizando
import Usuarios from './views/Usuarios.vue'
import UsuarioNuevo from './views/UsuarioNuevo.vue'
import UsuarioEditar from './views/UsuarioEditar.vue'
import Calificacion from './views/Calificacion.vue'
import CalFichaIdentificacion from './views/calFichaIdentificacion.vue'
import CalFichaRespuestas from './views/calFichaRespuestas.vue'
import CalFichaRespuestasCorrectas from './views/calFichaRespuestasCorrectas.vue'
import ProcesoAdmision from './views/ProcesoAdmision.vue'
import Postulantes from './views/Postulantes.vue'


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
    path: '/postulantes',
    name: 'V-Postulantes',
    component: Postulantes,
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
    path: '/Calificacion/FichaRespuestasCorrectas',
    name: 'V-FichaRespuestasCorrectas',
    component: CalFichaRespuestasCorrectas,
  },
  {
    path: '/Calificacion/FichaRespuestas',
    name: 'V-CalFichaRespuestas',
    component: CalFichaRespuestas,
  },
  {
    path: '/ProcesoAdmision',
    name: 'V-ProcesoAdmision',
    component: ProcesoAdmision,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router

