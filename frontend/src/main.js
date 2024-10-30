// import { createApp } from 'vue'
// import App from './App.vue'


// createApp(App).mount('#app')

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import DashboardLayout from './components/DashboardLayout.vue'
import './css/index.css'

// createApp(App)
//   .use(router)  // Agregar el router a la instancia de Vue
//   .mount('#app')

const app = createApp(App)

app.component('DefaultLayout', DashboardLayout)

app.use(router)
app.mount('#app')
