<template>
  <div class="alpine-example p-4 bg-white rounded-lg shadow">
    <h3 class="text-lg font-bold mb-4">Ejemplo Alpine.js - Componente Interactivo</h3>
    
    <div x-data="{ open: false, count: 0 }" class="space-y-4">
      <!-- Botón toggle con Alpine.js -->
      <button 
        @click="open = !open" 
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
      >
        <span x-text="open ? 'Ocultar' : 'Mostrar'">Mostrar</span> Detalles
      </button>
      
      <!-- Contenido condicional -->
      <div x-show="open" x-transition class="p-4 bg-gray-50 rounded">
        <p class="text-gray-700">Este contenido se muestra/oculta con Alpine.js</p>
        <p class="text-sm text-gray-600 mt-2">Perfecto para interactividad ligera sin Vue.js</p>
      </div>
      
      <!-- Contador interactivo -->
      <div class="flex items-center space-x-4">
        <button 
          @click="count--" 
          class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
        >
          -
        </button>
        <span class="text-xl font-bold" x-text="count">0</span>
        <button 
          @click="count++" 
          class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600"
        >
          +
        </button>
      </div>
      
      <!-- Input dinámico -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Texto en tiempo real:</label>
        <input 
          x-model="message" 
          type="text" 
          placeholder="Escribe algo..."
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <p class="mt-2 text-sm text-gray-600">Has escrito: <span x-text="message || '...'" class="font-medium"></span></p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AlpineExample'
}
</script>