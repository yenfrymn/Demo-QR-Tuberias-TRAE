<template>
  <div class="min-h-screen bg-gray-50">
    <AdminLayout>
      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Upload Blueprint</h1>
            
            <form @submit.prevent="submitForm" class="space-y-6">
              <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Blueprint Title</label>
                <input
                  id="title"
                  v-model="form.title"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>

              <div>
                <label for="pipeline_id" class="block text-sm font-medium text-gray-700">Pipeline</label>
                <select
                  id="pipeline_id"
                  v-model="form.pipeline_id"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                  <option value="">Select a pipeline</option>
                  <option v-for="pipeline in pipelines" :key="pipeline.id" :value="pipeline.id">
                    {{ pipeline.name }}
                  </option>
                </select>
              </div>

              <div>
                <label for="version" class="block text-sm font-medium text-gray-700">Version</label>
                <input
                  id="version"
                  v-model.number="form.version"
                  type="number"
                  min="1"
                  step="0.1"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>

              <div>
                <label for="file" class="block text-sm font-medium text-gray-700">Upload Blueprint</label>
                <input
                  id="file"
                  ref="fileInput"
                  type="file"
                  accept=".pdf,.dwg,.jpg,.jpeg,.png"
                  required
                  class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                />
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                ></textarea>
              </div>

              <div class="flex justify-end space-x-3">
                <button
                  type="button"
                  @click="$router.push('/admin/blueprints')"
                  class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="loading"
                  class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                >
                  {{ loading ? 'Uploading...' : 'Upload Blueprint' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AdminLayout>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const fileInput = ref<HTMLInputElement>()

const loading = ref(false)

const form = ref({
  title: '',
  pipeline_id: '',
  version: 1,
  description: ''
})

const pipelines = ref<any[]>([])

const fetchPipelines = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/pipelines`, {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      pipelines.value = await response.json()
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    }
  } catch (error) {
    console.error('Error fetching pipelines:', error)
  }
}

const submitForm = async () => {
  if (!fileInput.value?.files?.[0]) {
    alert('Please select a file')
    return
  }

  loading.value = true
  
  const formData = new FormData()
  formData.append('title', form.value.title)
  formData.append('pipeline_id', form.value.pipeline_id)
  formData.append('version', form.value.version.toString())
  formData.append('description', form.value.description)
  formData.append('file', fileInput.value.files[0])
  
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/blueprints/upload`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${authStore.token}`
      },
      body: formData
    })
    
    if (response.ok) {
      router.push('/admin/blueprints')
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    } else {
      const error = await response.json()
      alert('Error uploading blueprint: ' + (error.message || 'Unknown error'))
    }
  } catch (error) {
    console.error('Error uploading blueprint:', error)
    alert('Error uploading blueprint')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchPipelines()
})
</script>