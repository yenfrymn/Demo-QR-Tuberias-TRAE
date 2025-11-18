<template>
  <div class="p-4 space-y-4">
    <h2 class="text-xl font-semibold">Detalle de Tubería</h2>
    <div v-if="loading">Cargando...</div>
    <div v-else-if="!pipeline">No encontrado</div>
    <div v-else class="space-y-4">
      <div class="border rounded p-3">
        <div class="font-bold">{{ pipeline.name }}</div>
        <div class="text-sm">QR: {{ pipeline.qr_code }}</div>
        <div class="text-sm">Ubicación: {{ pipeline.location.address }}</div>
        <div class="text-sm">Diámetro: {{ pipeline.specifications.diameter }} | Material: {{ pipeline.specifications.material }}</div>
      </div>

      <div class="border rounded p-3">
        <div class="font-semibold">Empresas</div>
        <div class="text-sm">Iniciadora: {{ pipeline.companies?.initiator?.name || '-' }}</div>
        <div class="text-sm">Operador: {{ pipeline.companies?.current_operator?.name || '-' }}</div>
      </div>

      <div class="border rounded p-3">
        <div class="font-semibold">Certificaciones</div>
        <ul class="text-sm space-y-1">
          <li v-for="c in pipeline.certifications" :key="c.id">
            <span class="font-medium">{{ c.type }}</span> - {{ c.status }}
            <button v-if="c.document_url" @click="download(c.document_url)" class="ml-2 px-2 py-1 bg-blue-600 text-white rounded text-xs">Descargar</button>
          </li>
        </ul>
      </div>

      <div class="border rounded p-3">
        <div class="font-semibold">Planos</div>
        <ul class="text-sm space-y-1">
          <li v-for="b in pipeline.blueprints" :key="b.id">
            <span class="font-medium">{{ b.title }}</span> - {{ b.file_type }}
            <button v-if="b.download_url" @click="download(b.download_url)" class="ml-2 px-2 py-1 bg-blue-600 text-white rounded text-xs">Descargar</button>
          </li>
        </ul>
      </div>

      <div class="border rounded p-3">
        <div class="font-semibold">Licencia de Operación</div>
        <div v-if="pipeline.companies?.current_operator?.license" class="text-sm">
          N°: {{ pipeline.companies.current_operator.license.number }}
          Vigente hasta: {{ pipeline.companies.current_operator.license.expiry_date }}
        </div>
        <div v-else class="text-sm">Sin licencia</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import api from '../lib/api'
import { useRoute } from 'vue-router'

const route = useRoute()
const loading = ref(true)
const pipeline = ref<any>(null)

onMounted(async () => {
  try {
    const code = route.params.id as string
    const { data } = await api.get('/api/pipelines', { params: { qr_code: code } })
    const first = data.data?.[0]
    if (first) {
      pipeline.value = first
    }
  } finally {
    loading.value = false
  }
})

function download(url: string) {
  window.open(url, '_blank')
}
</script>