<template>
  <div class="min-h-screen bg-gray-50">
    <AdminLayout>
      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">New Operating License</h1>
            
            <form @submit.prevent="submitForm" class="space-y-6">
              <div>
                <label for="license_number" class="block text-sm font-medium text-gray-700">License Number</label>
                <input
                  id="license_number"
                  v-model="form.license_number"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>

              <div>
                <label for="type" class="block text-sm font-medium text-gray-700">License Type</label>
                <select
                  id="type"
                  v-model="form.type"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                  <option value="operating">Operating License</option>
                  <option value="construction">Construction Permit</option>
                  <option value="environmental">Environmental License</option>
                  <option value="safety">Safety Certificate</option>
                </select>
              </div>

              <div>
                <label for="issued_by" class="block text-sm font-medium text-gray-700">Issued By</label>
                <input
                  id="issued_by"
                  v-model="form.issued_by"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="valid_from" class="block text-sm font-medium text-gray-700">Valid From</label>
                  <input
                    id="valid_from"
                    v-model="form.valid_from"
                    type="date"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>

                <div>
                  <label for="valid_to" class="block text-sm font-medium text-gray-700">Valid To</label>
                  <input
                    id="valid_to"
                    v-model="form.valid_to"
                    type="date"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
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
                <label for="conditions" class="block text-sm font-medium text-gray-700">Conditions/Restrictions</label>
                <textarea
                  id="conditions"
                  v-model="form.conditions"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                ></textarea>
              </div>

              <div class="flex justify-end space-x-3">
                <button
                  type="button"
                  @click="$router.push('/admin/licenses')"
                  class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="loading"
                  class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                >
                  {{ loading ? 'Creating...' : 'Create License' }}
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

const loading = ref(false)

const form = ref({
  license_number: '',
  type: 'operating' as 'operating' | 'construction' | 'environmental' | 'safety',
  issued_by: '',
  valid_from: '',
  valid_to: '',
  pipeline_id: '',
  conditions: ''
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
  loading.value = true
  
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/licenses`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(form.value)
    })
    
    if (response.ok) {
      router.push('/admin/licenses')
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    } else {
      const error = await response.json()
      alert('Error creating license: ' + (error.message || 'Unknown error'))
    }
  } catch (error) {
    console.error('Error creating license:', error)
    alert('Error creating license')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchPipelines()
})
</script>