<template>
  <div class="min-h-screen bg-gray-50">
    <AdminLayout>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
          <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Companies</h1>
            <p class="mt-2 text-sm text-gray-700">Manage pipeline companies and their information</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
              @click="$router.push('/admin/companies/new')"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
            >
              Add Company
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
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Contact</th>
                      <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                      <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Actions</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="company in companies" :key="company.id">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="flex items-center">
                          <div>
                            <div class="font-medium text-gray-900">{{ company.name }}</div>
                            <div class="text-gray-500">{{ company.address }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5" :class="company.type === 'operator' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                          {{ company.type }}
                        </span>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <div class="text-gray-900">{{ company.contact_person }}</div>
                        <div class="text-gray-500">{{ company.contact_email }}</div>
                      </td>
                      <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5" :class="company.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                          {{ company.is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
                      <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <div class="flex space-x-2">
                          <button
                            @click="$router.push(`/admin/companies/${company.id}/edit`)"
                            class="text-indigo-600 hover:text-indigo-900"
                          >
                            Edit
                          </button>
                          <button
                            @click="deleteCompany(company.id)"
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

interface Company {
  id: number
  name: string
  type: 'operator' | 'contractor' | 'consultant'
  address: string
  contact_person: string
  contact_email: string
  contact_phone: string
  is_active: boolean
  created_at: string
  updated_at: string
}

const companies = ref<Company[]>([])

const fetchCompanies = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/companies`, {
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      companies.value = await response.json()
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    }
  } catch (error) {
    console.error('Error fetching companies:', error)
  }
}

const deleteCompany = async (id: number) => {
  if (!confirm('Are you sure you want to delete this company?')) return
  
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/companies/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      companies.value = companies.value.filter(c => c.id !== id)
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    }
  } catch (error) {
    console.error('Error deleting company:', error)
  }
}

onMounted(() => {
  fetchCompanies()
})
</script>