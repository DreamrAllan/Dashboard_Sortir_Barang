<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()
const categories = ref([])
const form = ref({ name: '', category_id: '', stock: 0, price: 0, status: 'aktif' })
const errors = ref({})
const loading = ref(false)

onMounted(async () => {
  try {
    const response = await api.get('/categories')
    const data = response.data?.data?.data || response.data?.data || response.data || []
    categories.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Failed to fetch categories:', error)
    categories.value = []
  }
})

async function handleSubmit() {
  loading.value = true
  errors.value = {}
  
  try {
    await api.post('/items', form.value)
    router.push('/items')
  } catch (error) {
    errors.value = error.response?.data?.errors || {}
  }
  
  loading.value = false
}
</script>

<template>
  <div class="max-w-xl">
    <p class="text-gray-500 mb-6">Tambahkan barang baru ke inventaris</p>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <form @submit.prevent="handleSubmit">
        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
          <input v-model="form.name" type="text" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            :class="{ 'border-red-500': errors.name }">
          <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
        </div>

        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
          <select v-model="form.category_id" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            :class="{ 'border-red-500': errors.category_id }">
            <option value="">Pilih Kategori</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <p v-if="errors.category_id" class="text-red-500 text-xs mt-1">{{ errors.category_id[0] }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Stok Awal</label>
            <input v-model.number="form.stock" type="number" min="0" required
              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
            <input v-model.number="form.price" type="number" min="0" required
              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          </div>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <select v-model="form.status" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
          </select>
        </div>

        <div class="flex justify-end gap-3">
          <router-link to="/items" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-800 rounded-xl hover:bg-gray-100 transition">Batal</router-link>
          <button type="submit" :disabled="loading" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition disabled:opacity-50">
            {{ loading ? 'Menyimpan...' : 'Simpan' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
