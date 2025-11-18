<template>
  <div class="p-4">
    <h2 class="text-xl font-semibold">Frontend Health</h2>
    <pre class="mt-2 bg-gray-100 p-3 rounded text-sm">{{ json }}</pre>
  </div>
</template>

<script setup lang="ts">
const json = {
  status: 'ok',
  timestamp: new Date().toISOString(),
  app: 'Frontend',
  version: '1.0',
}
</script>