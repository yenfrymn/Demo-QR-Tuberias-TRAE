<template>
  <div class="min-h-screen bg-gray-50">
    <AdminLayout>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
          <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Certifications</h1>
            <p class="mt-2 text-sm text-gray-700">Manage pipeline certifications and compliance documents</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
              @click="$router.push('/admin/certifications/new')"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
            >
              Add Certification
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
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Title</th>
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
                    <tr v-for="cert in certifications" :key="cert.id">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="flex items-center">
                          <div>
                            <div class="font-medium text-gray-900">{{ cert.title }}</div>
                            <div class="text-gray-500">{{ cert.document_number }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ cert.pipeline?.name || 'N/A' }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ cert.issued_by }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ formatDate(cert.valid_from) }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        {{ formatDate(cert.valid_to) }}
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5" :class="getStatusClass(cert)">
                          {{ getStatusText(cert) }}
                        </span>
                      </td>
                      <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <div class="flex space-x-2">
                          <a
                            :href="`${import.meta.env.VITE_API_URL}/api/certifications/${cert.id}/download`"
                            class="text-indigo-600 hover:text-indigo-900"
                            target="_blank"
                          >
                            Download
                          </a>
                          <button
                            @click="$router.push(`/admin/certifications/${cert.id}/edit`)"
                            class="text-indigo-600 hover:text-indigo-900"
                          >
                            Edit
                          </button>
                          <button
                            @click="deleteCertification(cert.id)"
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

interface Certification {
  id: number
  title: string
  document_number: string
  issued_by: string
  valid_from: string
  valid_to: string
  file_path: string
  pipeline?: {
    id: number
    name: string
  }
  created_at: string
  updated_at: string
}

const certifications = ref<Certification[]>([])

const fetchCertifications = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/certifications`, {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      certifications.value = await response.json()
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    }
  } catch (error) {
    console.error('Error fetching certifications:', error)
  }
}

const deleteCertification = async (id: number) => {
  if (!confirm('Are you sure you want to delete this certification?')) return
  
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/certifications/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      certifications.value = certifications.value.filter(c => c.id !== id)
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    }
  } catch (error) {
    console.error('Error deleting certification:', error)
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const getStatusClass = (cert: Certification) => {
  const now = new Date()
  const validTo = new Date(cert.valid_to)
  const validFrom = new Date(cert.valid_from)
  
  if (now < validFrom) {
    return 'bg-yellow-100 text-yellow-800'
  } else if (now > validTo) {
    return 'bg-red-100 text-red-800'
  } else {
    return 'bg-green-100 text-green-800'
  }
}

const getStatusText = (cert: Certification) => {
  const now = new Date()
  const validTo = new Date(cert.valid_to)
  const validFrom = new Date(cert.valid_from)
  
  if (now < validFrom) {
    return 'Not Valid'
  } else if (now > validTo) {
    return 'Expired'
  } else {
    return 'Valid'
  }
}

onMounted(() => {
  fetchCertifications()
})
</script>