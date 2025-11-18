<template>
  <div class="p-4 max-w-sm mx-auto space-y-4">
    <h2 class="text-xl font-semibold">Iniciar sesión</h2>
    <form @submit.prevent="submit" class="space-y-3">
      <input v-model="email" type="email" class="w-full border rounded p-2" placeholder="Email" />
      <input v-model="password" type="password" class="w-full border rounded p-2" placeholder="Password" />
      <button class="w-full px-4 py-2 bg-blue-600 text-white rounded" :disabled="loading">
        {{ loading ? 'Accediendo...' : 'Acceder' }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import api from '../lib/api'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const loading = ref(false)
const router = useRouter()
const auth = useAuthStore()

async function submit() {
  loading.value = true
  try {
    const { data } = await api.post('/api/login', { email: email.value, password: password.value })
    localStorage.setItem('token', data.token)
    api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
    auth.setToken(data.token)
    try {
      const me = await api.get('/api/user')
      auth.setRole(me.data.role || 'viewer')
    } catch {}
    router.push('/scan')
  } catch (e) {
    alert('Error de autenticación')
  } finally {
    loading.value = false
  }
}
</script>