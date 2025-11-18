import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createPinia } from 'pinia'
import router from './router'
import { registerSW } from 'virtual:pwa-register'
import Alpine from 'alpinejs'

// Declarar tipo para Alpine en window
declare global {
  interface Window {
    Alpine: typeof Alpine
  }
}

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app')

// Initialize Alpine.js
window.Alpine = Alpine
Alpine.start()

registerSW({ immediate: true })
