<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const items = ref([])
const categories = ref([])
const loading = ref(true)
const message = ref('')
const filters = ref({ search: '', category_id: '' })

onMounted(async () => {
  await Promise.all([fetchItems(), fetchCategories()])
})

async function fetchItems() {
  loading.value = true
  try {
    const params = new URLSearchParams()
    if (filters.value.search) params.append('search', filters.value.search)
    if (filters.value.category_id) params.append('category_id', filters.value.category_id)
    
    const response = await api.get(`/items?${params}`)
    // Handle paginated response: data.data.data or data.data or data
    const data = response.data?.data?.data || response.data?.data || response.data || []
    items.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Failed to fetch items:', error)
    items.value = []
  }
  loading.value = false
}

async function fetchCategories() {
  try {
    const response = await api.get('/categories')
    const data = response.data?.data?.data || response.data?.data || response.data || []
    categories.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Failed to fetch categories:', error)
    categories.value = []
  }
}

async function deleteItem(id) {
  if (!confirm('Hapus barang ini?')) return
  
  try {
    await api.delete(`/items/${id}`)
    message.value = 'Barang berhasil dihapus!'
    await fetchItems()
    setTimeout(() => message.value = '', 3000)
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menghapus barang')
  }
}

function formatCurrency(value) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value)
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <p class="text-gray-500">Kelola data barang inventaris Anda</p>
      <router-link to="/items/create" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Barang
      </router-link>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-3">
      <input v-model="filters.search" @input="fetchItems" type="text" placeholder="Cari barang..." class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      <select v-model="filters.category_id" @change="fetchItems" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Kategori</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
      </select>
    </div>

    <div v-if="message" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
      {{ message }}
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="text-left text-xs text-gray-500 bg-gray-50">
            <th class="px-6 py-4 font-medium">Kode</th>
            <th class="px-6 py-4 font-medium">Nama Barang</th>
            <th class="px-6 py-4 font-medium">Kategori</th>
            <th class="px-6 py-4 font-medium text-center">Stok</th>
            <th class="px-6 py-4 font-medium text-right">Harga</th>
            <th class="px-6 py-4 font-medium text-center">Status</th>
            <th class="px-6 py-4 font-medium text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          <tr v-if="loading">
            <td colspan="7" class="px-6 py-12 text-center text-gray-400">Loading...</td>
          </tr>
          <tr v-else-if="!items.length">
            <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada barang</td>
          </tr>
          <tr v-for="item in items" :key="item.id" class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ item.code }}</td>
            <td class="px-6 py-4 font-medium text-gray-800">{{ item.name }}</td>
            <td class="px-6 py-4 text-gray-500">{{ item.category?.name }}</td>
            <td class="px-6 py-4 text-center">
              <span :class="item.stock <= 10 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'" class="px-3 py-1 text-xs rounded-lg font-medium">{{ item.stock }}</span>
            </td>
            <td class="px-6 py-4 text-right text-gray-600">{{ formatCurrency(item.price) }}</td>
            <td class="px-6 py-4 text-center">
              <span :class="item.status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'" class="px-3 py-1 text-xs rounded-lg font-medium capitalize">{{ item.status }}</span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex justify-end gap-2">
                <router-link :to="`/items/${item.id}/edit`" class="px-3 py-1.5 text-xs bg-cyan-100 text-cyan-700 rounded-lg hover:bg-cyan-200 transition">Edit</router-link>
                <button @click="deleteItem(item.id)" class="px-3 py-1.5 text-xs bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition">Hapus</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
