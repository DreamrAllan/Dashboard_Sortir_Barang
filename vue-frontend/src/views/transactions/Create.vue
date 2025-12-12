<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()
const items = ref([])
const form = ref({ item_id: '', type: 'masuk', quantity: 1, notes: '', transaction_date: new Date().toISOString().split('T')[0] })
const errors = ref({})
const loading = ref(false)

onMounted(async () => {
  try {
    const response = await api.get('/items')
    const data = response.data?.data?.data || response.data?.data || response.data || []
    const allItems = Array.isArray(data) ? data : []
    items.value = allItems.filter(i => i.status === 'aktif')
  } catch (error) {
    console.error('Failed to fetch items:', error)
    items.value = []
  }
})

async function handleSubmit() {
  loading.value = true
  errors.value = {}
  
  try {
    await api.post('/transactions', form.value)
    router.push('/transactions')
  } catch (error) {
    errors.value = error.response?.data?.errors || {}
  }
  
  loading.value = false
}
</script>

<template>
  <div class="max-w-xl">
    <p class="text-gray-500 mb-6">Catat transaksi barang masuk atau keluar</p>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <form @submit.prevent="handleSubmit">
        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input v-model="form.type" type="radio" value="masuk" class="text-blue-500 focus:ring-blue-500">
              <span class="text-sm text-gray-700">Barang Masuk</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input v-model="form.type" type="radio" value="keluar" class="text-blue-500 focus:ring-blue-500">
              <span class="text-sm text-gray-700">Barang Keluar</span>
            </label>
          </div>
        </div>

        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang</label>
          <select v-model="form.item_id" required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            :class="{ 'border-red-500': errors.item_id }">
            <option value="">Pilih Barang</option>
            <option v-for="item in items" :key="item.id" :value="item.id">{{ item.name }} (Stok: {{ item.stock }})</option>
          </select>
          <p v-if="errors.item_id" class="text-red-500 text-xs mt-1">{{ errors.item_id[0] }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
            <input v-model.number="form.quantity" type="number" min="1" required
              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              :class="{ 'border-red-500': errors.quantity }">
            <p v-if="errors.quantity" class="text-red-500 text-xs mt-1">{{ errors.quantity[0] }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
            <input v-model="form.transaction_date" type="date" required
              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          </div>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Catatan <span class="text-gray-400">(opsional)</span></label>
          <textarea v-model="form.notes" rows="2"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
        </div>

        <div class="flex justify-end gap-3">
          <router-link to="/transactions" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-800 rounded-xl hover:bg-gray-100 transition">Batal</router-link>
          <button type="submit" :disabled="loading" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition disabled:opacity-50">
            {{ loading ? 'Menyimpan...' : 'Simpan' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
