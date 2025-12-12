<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const stats = ref({
  totalItems: 0,
  totalStock: 0,
  totalValue: 0,
  totalCategories: 0,
  lowStockItems: [],
  recentTransactions: []
})
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await api.get('/dashboard')
    const data = response.data?.data || response.data || {}
    
    // Map API response (snake_case) to Vue state (camelCase)
    stats.value = {
      totalItems: data.summary?.total_items || 0,
      totalStock: data.summary?.total_stock || 0,
      totalValue: data.summary?.total_value || 0,
      totalCategories: data.summary?.total_categories || 0,
      lowStockItems: data.low_stock_items || [],
      recentTransactions: data.recent_transactions || []
    }
  } catch (error) {
    console.error('Failed to load dashboard:', error)
  }
  loading.value = false
})

function formatCurrency(value) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value)
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
}
</script>

<template>
  <div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl p-6 text-white shadow-lg">
      <h2 class="text-xl font-bold mb-1">Selamat Datang! 👋</h2>
      <p class="text-blue-100">Kelola inventaris barang Anda dengan mudah</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-800">{{ stats.totalItems }}</p>
            <p class="text-sm text-gray-500">Total Barang</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-800">{{ stats.totalStock }}</p>
            <p class="text-sm text-gray-500">Total Stok</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-800">{{ formatCurrency(stats.totalValue) }}</p>
            <p class="text-sm text-gray-500">Nilai Inventaris</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-800">{{ stats.totalCategories }}</p>
            <p class="text-sm text-gray-500">Total Kategori</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Transactions -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-4 border-b border-gray-100">
          <h3 class="font-semibold text-gray-800">Transaksi Terbaru</h3>
        </div>
        <div class="p-4">
          <div v-if="stats.recentTransactions?.length" class="space-y-3">
            <div v-for="tx in stats.recentTransactions" :key="tx.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
              <div>
                <p class="font-medium text-gray-800 text-sm">{{ tx.item?.name }}</p>
                <p class="text-xs text-gray-500">{{ formatDate(tx.transaction_date) }}</p>
              </div>
              <div class="text-right">
                <span :class="tx.type === 'masuk' ? 'text-emerald-600' : 'text-rose-600'" class="font-medium text-sm">
                  {{ tx.type === 'masuk' ? '+' : '-' }}{{ tx.quantity }}
                </span>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-400 text-center py-8">Belum ada transaksi</p>
        </div>
      </div>

      <!-- Low Stock Alert -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-4 border-b border-gray-100">
          <h3 class="font-semibold text-gray-800">Stok Menipis ⚠️</h3>
        </div>
        <div class="p-4">
          <div v-if="stats.lowStockItems?.length" class="space-y-3">
            <div v-for="item in stats.lowStockItems" :key="item.id" class="flex items-center justify-between p-3 bg-amber-50 rounded-xl">
              <div>
                <p class="font-medium text-gray-800 text-sm">{{ item.name }}</p>
                <p class="text-xs text-gray-500">{{ item.category?.name }}</p>
              </div>
              <span class="px-3 py-1 bg-amber-200 text-amber-800 rounded-lg text-xs font-medium">{{ item.stock }} unit</span>
            </div>
          </div>
          <p v-else class="text-gray-400 text-center py-8">Semua stok aman</p>
        </div>
      </div>
    </div>
  </div>
</template>
