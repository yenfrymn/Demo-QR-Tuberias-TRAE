<template>
  <div class="p-4 space-y-4">
    <h2 class="text-xl font-semibold">Pipelines</h2>
    <router-link to="/admin/pipelines/new" class="px-3 py-2 bg-blue-600 text-white rounded">Nuevo</router-link>
    <div class="mt-4">
      <div v-for="p in items" :key="p.id" class="border rounded p-3 mb-2 flex justify-between items-center">
        <div>
          <div class="font-medium">{{ p.name }}</div>
          <div class="text-sm">QR: {{ p.qr_code }}</div>
        </div>
        <div class="flex gap-2">
          <router-link :to="`/admin/pipelines/${p.id}/edit`" class="px-2 py-1 bg-gray-700 text-white rounded">Editar</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '../../lib/api'

const items = ref<any[]>([])
onMounted(async () => {
  const { data } = await api.get('/api/pipelines', { params: { per_page: 50 } })
  items.value = data.data
})
</script>