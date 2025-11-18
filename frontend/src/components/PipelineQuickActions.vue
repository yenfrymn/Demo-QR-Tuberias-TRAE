<template>
  <div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
    
    <!-- Búsqueda rápida de tuberías -->
    <div class="mb-4">
      <div class="relative">
        <input 
          v-model="searchQuery"
          @input="searchPipelines"
          type="text" 
          placeholder="Buscar tubería por código..."
          class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
        >
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
          <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
      </div>
      
      <!-- Resultados de búsqueda -->
      <div v-if="searchResults.length > 0" class="mt-2 bg-white border border-gray-200 rounded-md shadow-lg">
        <ul class="py-1">
          <li v-for="pipeline in searchResults" :key="pipeline.id">
            <a 
              @click="selectPipeline(pipeline)"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
            >
              {{ pipeline.code }} - {{ pipeline.name }}
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Acciones rápidas para tubería seleccionada -->
    <div v-if="selectedPipeline" class="border-t pt-4">
      <div class="mb-3">
        <h4 class="font-medium text-gray-900">{{ selectedPipeline.name }}</h4>
        <p class="text-sm text-gray-500">{{ selectedPipeline.code }}</p>
      </div>
      
      <div class="grid grid-cols-2 gap-2">
        <button 
          @click="generateQRCode"
          class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01a16 16 0 013.622 8.518l1.527-1.527A16 16 0 0021 12c0-4.418-3.582-8-8-8s-8 3.582-8 8c0 1.73.528 3.36 1.446 4.718l1.527-1.527A16 16 0 0112 12z"></path>
          </svg>
          Generar QR
        </button>
        
        <button 
          @click="viewDetails"
          class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Ver Detalles
        </button>
        
        <button 
          @click="toggleStatus"
          class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
          </svg>
          {{ selectedPipeline.status === 'active' ? 'Desactivar' : 'Activar' }}
        </button>
        
        <button 
          @click="quickEdit"
          class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Editar
        </button>
      </div>
    </div>

    <!-- Mensajes de estado -->
    <div v-if="message" class="mt-4">
      <div 
        class="p-3 rounded-md text-sm"
        :class="messageType === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'"
      >
        {{ message }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

interface Pipeline {
  id: number
  code: string
  name: string
  status: string
}

const searchQuery = ref('')
const searchResults = ref<Pipeline[]>([])
const selectedPipeline = ref<Pipeline | null>(null)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')

// Simular búsqueda de tuberías
const searchPipelines = () => {
  if (searchQuery.value.length < 2) {
    searchResults.value = []
    return
  }
  
  // Datos de ejemplo - en producción esto sería una llamada API
  const mockPipelines: Pipeline[] = [
    { id: 1, code: 'GAS-NOR-A-001', name: 'Gasoducto Norte - Tramo A', status: 'active' },
    { id: 2, code: 'OLEO-PAT-CT-002', name: 'Oleoducto Patagónico - Tramo Central', status: 'active' },
    { id: 3, code: 'GAS-ATL-RE-003', name: 'Gasoducto del Atlántico - Rama Este', status: 'maintenance' }
  ]
  
  searchResults.value = mockPipelines.filter(pipeline => 
    pipeline.code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    pipeline.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
}

// Seleccionar tubería
const selectPipeline = (pipeline: Pipeline) => {
  selectedPipeline.value = pipeline
  searchQuery.value = ''
  searchResults.value = []
}

// Generar código QR
const generateQRCode = () => {
  showMessage('Código QR generado exitosamente', 'success')
  // En producción: redirigir a generador QR o descargar
}

// Ver detalles de la tubería
const viewDetails = () => {
  if (selectedPipeline.value) {
    router.push(`/admin/pipelines/${selectedPipeline.value.id}`)
  }
}

// Cambiar estado de la tubería
const toggleStatus = () => {
  if (selectedPipeline.value) {
    selectedPipeline.value.status = selectedPipeline.value.status === 'active' ? 'inactive' : 'active'
    showMessage(`Tubería ${selectedPipeline.value.status === 'active' ? 'activada' : 'desactivada'}`, 'success')
  }
}

// Edición rápida
const quickEdit = () => {
  if (selectedPipeline.value) {
    router.push(`/admin/pipelines/${selectedPipeline.value.id}/edit`)
  }
}

// Mostrar mensaje
const showMessage = (text: string, type: 'success' | 'error') => {
  message.value = text
  messageType.value = type
  setTimeout(() => {
    message.value = ''
  }, 3000)
}
</script>