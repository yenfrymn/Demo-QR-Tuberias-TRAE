<template>
  <div class="min-h-screen bg-gray-50">
    <AdminLayout>
      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">New Company</h1>
            
            <form @submit.prevent="submitForm" class="space-y-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>

              <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Company Type</label>
                <select
                  id="type"
                  v-model="form.type"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                  <option value="operator">Operator</option>
                  <option value="contractor">Contractor</option>
                  <option value="consultant">Consultant</option>
                </select>
              </div>

              <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea
                  id="address"
                  v-model="form.address"
                  rows="3"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                ></textarea>
              </div>

              <div>
                <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                <input
                  id="contact_person"
                  v-model="form.contact_person"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>

              <div>
                <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                <input
                  id="contact_email"
                  v-model="form.contact_email"
                  type="email"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>

              <div>
                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                <input
                  id="contact_phone"
                  v-model="form.contact_phone"
                  type="tel"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>

              <div class="flex items-center">
                <input
                  id="is_active"
                  v-model="form.is_active"
                  type="checkbox"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                  Active Company
                </label>
              </div>

              <div class="flex justify-end space-x-3">
                <button
                  type="button"
                  @click="$router.push('/admin/companies')"
                  class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="loading"
                  class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                >
                  {{ loading ? 'Creating...' : 'Create Company' }}
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)

const form = ref({
  name: '',
  type: 'operator' as 'operator' | 'contractor' | 'consultant',
  address: '',
  contact_person: '',
  contact_email: '',
  contact_phone: '',
  is_active: true
})

const submitForm = async () => {
  loading.value = true
  
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/companies`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(form.value)
    })
    
    if (response.ok) {
      router.push('/admin/companies')
    } else if (response.status === 401) {
      authStore.logout()
      router.push('/login')
    } else {
      const error = await response.json()
      alert('Error creating company: ' + (error.message || 'Unknown error'))
    }
  } catch (error) {
    console.error('Error creating company:', error)
    alert('Error creating company')
  } finally {
    loading.value = false
  }
}
</script>