<template>
  <div class="p-4 space-y-4 max-w-2xl">
    <h2 class="text-xl font-semibold">Editar Tubería</h2>
    <form @submit.prevent="save" class="space-y-3">
      <input v-model="form.name" class="w-full border rounded p-2" placeholder="Nombre" />
      <input v-model="form.address" class="w-full border rounded p-2" placeholder="Dirección" />
      <input v-model="form.diameter" class="w-full border rounded p-2" placeholder="Diámetro" />
      <input v-model="form.material" class="w-full border rounded p-2" placeholder="Material" />
      <select v-model="form.status" class="w-full border rounded p-2">
        <option value="active">Activo</option>
        <option value="inactive">Inactivo</option>
        <option value="maintenance">Mantenimiento</option>
      </select>
      <div class="flex gap-2">
        <button class="px-3 py-2 bg-blue-600 text-white rounded" :disabled="loading">Guardar</button>
        <button type="button" class="px-3 py-2 bg-gray-700 text-white rounded" @click="generateQr">Generar QR</button>
        <a v-if="qrUrl" :href="qrUrl" target="_blank" class="px-3 py-2 bg-green-600 text-white rounded">Exportar QR</a>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '../../lib/api'
import { useRoute } from 'vue-router'

const route = useRoute()
const id = Number(route.params.id)
const form = ref<any>({ name: '', address: '', diameter: '', material: '', status: 'active' })
const loading = ref(false)
const qrUrl = ref<string>('')

onMounted(async () => {
  const { data } = await api.get(`/api/pipelines/${id}`)
  Object.assign(form.value, data.data)
})

async function save() {
  loading.value = true
  try {
    await api.put(`/api/pipelines/${id}`, form.value)
    alert('Guardado')
  } finally {
    loading.value = false
  }
}

async function generateQr() {
  const { data } = await api.post(`/api/pipelines/${id}/generate-qr`)
  qrUrl.value = data.data.download_url
}
</script>