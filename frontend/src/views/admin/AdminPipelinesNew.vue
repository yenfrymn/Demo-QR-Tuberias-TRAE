<template>
  <div class="p-4 space-y-4 max-w-2xl">
    <h2 class="text-xl font-semibold">Nueva Tubería</h2>
    <form @submit.prevent="submit" class="space-y-3">
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
        <button class="px-3 py-2 bg-blue-600 text-white rounded" :disabled="loading">Crear</button>
        <button v-if="pipelineId" type="button" class="px-3 py-2 bg-gray-700 text-white rounded" @click="generateQr">Generar QR</button>
        <a v-if="qrUrl" :href="qrUrl" target="_blank" class="px-3 py-2 bg-green-600 text-white rounded">Exportar QR</a>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import api from '../../lib/api'

const form = ref<any>({ name: '', address: '', diameter: '', material: '', status: 'active' })
const loading = ref(false)
const pipelineId = ref<number|undefined>(undefined)
const qrUrl = ref<string>('')

async function submit() {
  loading.value = true
  try {
    const { data } = await api.post('/api/pipelines', form.value)
    pipelineId.value = data.data.id
    alert('Creado')
  } finally {
    loading.value = false
  }
}

async function generateQr() {
  if (!pipelineId.value) return
  const { data } = await api.post(`/api/pipelines/${pipelineId.value}/generate-qr`)
  qrUrl.value = data.data.download_url
}
</script>