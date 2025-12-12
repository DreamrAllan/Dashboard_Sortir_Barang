<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../../services/api'

const router = useRouter()
const route = useRoute()
const form = ref({ name: '', description: '' })
const errors = ref({})
const loading = ref(false)

onMounted(async () => {
  try {
    const response = await api.get(`/categories/${route.params.id}`)
    const data = response.data?.data || response.data || {}
    form.value = data
  } catch (error) {
    router.push('/categories')
  }
})

async function handleSubmit() {
  loading.value = true
  errors.value = {}
  
  try {
    await api.put(`/categories/${route.params.id}`, form.value)
    router.push('/categories')
  } catch (error) {
    errors.value = error.response?.data?.errors || {}
  }
  
  loading.value = false
}
</script>

<template>
  <div class="max-w-xl">
    <p class="text-gray-500 mb-6">Ubah detail kategori</p>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <form @submit.prevent="handleSubmit">
        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
          <input v-model="form.name" type="text" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            :class="{ 'border-red-500': errors.name }">
          <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-gray-400">(opsional)</span></label>
          <textarea v-model="form.description" rows="3"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
        </div>

        <div class="flex justify-end gap-3">
          <router-link to="/categories" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-800 rounded-xl hover:bg-gray-100 transition">Batal</router-link>
          <button type="submit" :disabled="loading" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition disabled:opacity-50">
            {{ loading ? 'Menyimpan...' : 'Update' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
