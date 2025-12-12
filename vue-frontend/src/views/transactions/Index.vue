<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const transactions = ref([])
const loading = ref(true)
const message = ref('')
const filters = ref({ type: '', start_date: '', end_date: '' })

onMounted(async () => {
  await fetchTransactions()
})

async function fetchTransactions() {
  loading.value = true
  try {
    const params = new URLSearchParams()
    if (filters.value.type) params.append('type', filters.value.type)
    if (filters.value.start_date) params.append('start_date', filters.value.start_date)
    if (filters.value.end_date) params.append('end_date', filters.value.end_date)
    
    const response = await api.get(`/transactions?${params}`)
    const data = response.data?.data?.data || response.data?.data || response.data || []
    transactions.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Failed to fetch transactions:', error)
    transactions.value = []
  }
  loading.value = false
}

async function deleteTransaction(id) {
  if (!confirm('Hapus transaksi ini? Stok akan dikembalikan.')) return
  
  try {
    await api.delete(`/transactions/${id}`)
    message.value = 'Transaksi berhasil dihapus!'
    await fetchTransactions()
    setTimeout(() => message.value = '', 3000)
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menghapus transaksi')
  }
}

function formatCurrency(value) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value)
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <p class="text-gray-500">Riwayat transaksi barang masuk dan keluar</p>
      <router-link to="/transactions/create" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Transaksi
      </router-link>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-3">
      <select v-model="filters.type" @change="fetchTransactions" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Tipe</option>
        <option value="masuk">Barang Masuk</option>
        <option value="keluar">Barang Keluar</option>
      </select>
      <input v-model="filters.start_date" @change="fetchTransactions" type="date" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      <input v-model="filters.end_date" @change="fetchTransactions" type="date" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
            <th class="px-6 py-4 font-medium">Barang</th>
            <th class="px-6 py-4 font-medium text-center">Tipe</th>
            <th class="px-6 py-4 font-medium text-center">Qty</th>
            <th class="px-6 py-4 font-medium text-right">Total</th>
            <th class="px-6 py-4 font-medium">Tanggal</th>
            <th class="px-6 py-4 font-medium text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          <tr v-if="loading">
            <td colspan="7" class="px-6 py-12 text-center text-gray-400">Loading...</td>
          </tr>
          <tr v-else-if="!transactions.length">
            <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada transaksi</td>
          </tr>
          <tr v-for="tx in transactions" :key="tx.id" class="border-b border-gray-50 hover:bg-gray-50">
            <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ tx.transaction_code }}</td>
            <td class="px-6 py-4 font-medium text-gray-800">{{ tx.item?.name }}</td>
            <td class="px-6 py-4 text-center">
              <span :class="tx.type === 'masuk' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'" class="px-3 py-1 text-xs rounded-lg font-medium capitalize">{{ tx.type }}</span>
            </td>
            <td class="px-6 py-4 text-center font-medium">{{ tx.quantity }}</td>
            <td class="px-6 py-4 text-right text-gray-600">{{ formatCurrency(tx.total_price) }}</td>
            <td class="px-6 py-4 text-gray-500">{{ formatDate(tx.transaction_date) }}</td>
            <td class="px-6 py-4 text-right">
              <button @click="deleteTransaction(tx.id)" class="px-3 py-1.5 text-xs bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
