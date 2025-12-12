<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()
const categories = ref([])
const loading = ref(true)
const message = ref('')

onMounted(async () => {
  await fetchCategories()
})

async function fetchCategories() {
  loading.value = true
  try {
    const response = await api.get('/categories')
    const data = response.data?.data?.data || response.data?.data || response.data || []
    categories.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Failed to fetch categories:', error)
    categories.value = []
  }
  loading.value = false
}

async function deleteCategory(id) {
  if (!confirm('Hapus kategori ini?')) return
  
  try {
    await api.delete(`/categories/${id}`)
    message.value = 'Kategori berhasil dihapus!'
    await fetchCategories()
    setTimeout(() => message.value = '', 3000)
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menghapus kategori')
  }
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <p class="text-gray-500">Kelola kategori barang Anda</p>
      <router-link to="/categories/create" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Kategori
      </router-link>
    </div>

    <div v-if="message" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
      {{ message }}
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="text-left text-xs text-gray-500 bg-gray-50">
            <th class="px-6 py-4 font-medium">No</th>
            <th class="px-6 py-4 font-medium">Nama Kategori</th>
            <th class="px-6 py-4 font-medium">Deskripsi</th>
            <th class="px-6 py-4 font-medium text-center">Jumlah Barang</th>
            <th class="px-6 py-4 font-medium text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          <tr v-if="loading">
            <td colspan="5" class="px-6 py-12 text-center text-gray-400">Loading...</td>
          </tr>
          <tr v-else-if="!categories.length">
            <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada kategori</td>
          </tr>
          <tr v-for="(category, index) in categories" :key="category.id" class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-6 py-4 text-gray-500">{{ index + 1 }}</td>
            <td class="px-6 py-4 font-medium text-gray-800">{{ category.name }}</td>
            <td class="px-6 py-4 text-gray-500">{{ category.description || '-' }}</td>
            <td class="px-6 py-4 text-center">
              <span class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg font-medium">{{ category.items_count || 0 }}</span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex justify-end gap-2">
                <router-link :to="`/categories/${category.id}/edit`" class="px-3 py-1.5 text-xs bg-cyan-100 text-cyan-700 rounded-lg hover:bg-cyan-200 transition">Edit</router-link>
                <button @click="deleteCategory(category.id)" class="px-3 py-1.5 text-xs bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition">Hapus</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
