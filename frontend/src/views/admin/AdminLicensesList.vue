<template>
  <div class="min-h-screen bg-gray-50">
    <AdminLayout>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
          <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Operating Licenses</h1>
            <p class="mt-2 text-sm text-gray-700">Manage pipeline operating licenses and permits</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
              @click="$router.push('/admin/licenses/new')"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
            >
              Add License
            </button>
          </div>
        </div>

        <div class="mt-8 flex flex-col">
          <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">License Number</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Pipeline</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Issued By</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Valid From</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Valid To</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                      <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Actions</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="license in licenses" :key="license.id">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="flex items-center">
                          <div>
                            <div class="font-medium text-gray-900">{{ license.license_number }}</div>
                            <div class="text-gray-500">{{ license.type }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ license.pipeline?.name || 'N/A' }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ license.issued_by }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ formatDate(license.valid_from) }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ formatDate(license.valid_to) }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5" :class="getStatusClass(license)">
                          {{ getStatusText(license) }}
                        </span>
                      </td>
                      <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <div class="flex space-x-2">
                          <button
                            @click="$router.push(`/admin/licenses/${license.id}/edit`)"
                            class="text-indigo-600 hover:text-indigo-900"
                          >
                            Edit
                          </button>
                          <button
                            @click="deleteLicense(license.id)"
                            class="text-red-600 hover:text-red-900"
                          >
                            Delete
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
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

interface License {
  id: number
  license_number: string
  type: string
  issued_by: string
  valid_from: string
  valid_to: string
  status: 'active' | 'expired' | 'pending'
  pipeline?: {
    id: number
    name: string
  }
  created_at: string
  updated_at: string
}

const licenses = ref<License[]>([])

const fetchLicenses = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/licenses`, {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      licenses.value = await response.json()
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    }
  } catch (error) {
    console.error('Error fetching licenses:', error)
  }
}

const deleteLicense = async (id: number) => {
  if (!confirm('Are you sure you want to delete this license?')) return
  
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/licenses/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      licenses.value = licenses.value.filter(l => l.id !== id)
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    }
  } catch (error) {
    console.error('Error deleting license:', error)
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const getStatusClass = (license: License) => {
  const now = new Date()
  const validTo = new Date(license.valid_to)
  const validFrom = new Date(license.valid_from)
  
  if (now < validFrom) {
    return 'bg-yellow-100 text-yellow-800'
  } else if (now > validTo) {
    return 'bg-red-100 text-red-800'
  } else {
    return 'bg-green-100 text-green-800'
  }
}

const getStatusText = (license: License) => {
  const now = new Date()
  const validTo = new Date(license.valid_to)
  const validFrom = new Date(license.valid_from)
  
  if (now < validFrom) {
    return 'Not Valid'
  } else if (now > validTo) {
    return 'Expired'
  } else {
    return 'Valid'
  }
}

onMounted(() => {
  fetchLicenses()
})
</script>