<template>
  <div class="p-4 space-y-4">
    <h2 class="text-xl font-semibold">Escanear QR</h2>
    <div class="flex gap-2">
      <button @click="mode='camera'" :class="btnClass(mode==='camera')">Usar cámara</button>
      <button @click="mode='image'" :class="btnClass(mode==='image')">Desde imagen</button>
    </div>

    <div v-if="mode==='camera'">
      <div id="reader" class="border rounded" style="width:100%; max-width:480px"></div>
    </div>
    <div v-else class="space-y-2">
      <input type="file" accept="image/*" @change="onImage" class="block" />
      <div class="text-sm text-gray-600">Selecciona una imagen que contenga un código QR.</div>
    </div>
    <div v-if="code" class="mt-4">
      <p class="text-sm">Código detectado: <span class="font-mono">{{ code }}</span></p>
      <button @click="goDetail" class="mt-2 px-4 py-2 bg-green-600 text-white rounded">Ver detalle</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue'
import { Html5Qrcode } from 'html5-qrcode'
import { useRouter } from 'vue-router'

const code = ref<string | null>(null)
const router = useRouter()
let qr: Html5Qrcode | null = null
const mode = ref<'camera'|'image'>('camera')

onMounted(async () => {
  qr = new Html5Qrcode('reader')
  if (mode.value === 'camera') {
    try {
      await qr.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: 250 },
        (decoded) => { code.value = decoded },
        () => {}
      )
    } catch (e) {
      alert('No se pudo iniciar la cámara')
    }
  }
})

onUnmounted(async () => {
  if (qr) {
    await qr.stop().catch(() => {})
    qr.clear()
  }
})

function goDetail() {
  if (!code.value) return
  router.push({ name: 'pipeline-detail', params: { id: code.value } })
}

function onImage(e: Event) {
  const input = e.target as HTMLInputElement
  const file = input.files?.[0]
  if (!file) return
  if (!qr) qr = new Html5Qrcode('reader')
  qr.scanFile(file, true)
    .then((decoded) => { code.value = decoded })
    .catch(() => alert('No se pudo leer el QR de la imagen'))
}

function btnClass(active: boolean) {
  return active ? 'px-3 py-2 bg-blue-600 text-white rounded' : 'px-3 py-2 bg-gray-200 rounded'
}
</script>