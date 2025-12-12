<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const isCollapsed = ref(false)
const isAnimating = ref(false)

// Load sidebar state from localStorage
onMounted(() => {
  isCollapsed.value = localStorage.getItem('sidebarCollapsed') === 'true'
})

function toggleSidebar() {
  isAnimating.value = true
  isCollapsed.value = !isCollapsed.value
  localStorage.setItem('sidebarCollapsed', isCollapsed.value)
  
  setTimeout(() => {
    isAnimating.value = false
  }, 350)
}

async function logout() {
  await authStore.logout()
  router.push('/login')
}

const menuItems = [
  { path: '/dashboard', name: 'Dashboard', icon: 'dashboard' },
]

const masterItems = [
  { path: '/categories', name: 'Kategori', icon: 'tag' },
  { path: '/items', name: 'Barang', icon: 'box' },
]

const activityItems = [
  { path: '/transactions', name: 'Transaksi', icon: 'transaction' },
]

function isActive(path) {
  return route.path.startsWith(path)
}

function formatDate() {
  return new Date().toLocaleDateString('id-ID', { 
    weekday: 'long', 
    day: 'numeric', 
    month: 'short', 
    year: 'numeric' 
  })
}
</script>

<template>
  <div 
    class="flex min-h-screen bg-gray-50" 
    :class="{ 
      'sidebar-collapsed': isCollapsed, 
      'animate-sidebar': isAnimating 
    }"
  >
    <!-- Sidebar -->
    <aside class="sidebar bg-white border-r border-gray-100 fixed h-full z-50 shadow-sm">
      <!-- Header -->
      <div class="sidebar-header flex items-center justify-between p-4 border-b border-gray-100">
        <div class="flex items-center gap-3 flex-shrink-0">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-200">S</div>
          <span class="sidebar-text font-bold text-gray-800 text-lg">SORTIR</span>
        </div>
        <button @click="toggleSidebar" class="p-2.5 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 rounded-xl shadow-lg shadow-blue-200 transition-all flex-shrink-0">
          <svg class="toggle-arrow w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
          </svg>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="sidebar-nav p-4 space-y-1">
        <router-link 
          to="/dashboard" 
          class="nav-link relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
          :class="isActive('/dashboard') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600'"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
          <span class="sidebar-text">Dashboard</span>
        </router-link>

        <p class="sidebar-label sidebar-text px-4 pt-6 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
        
        <router-link 
          to="/categories" 
          class="nav-link relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
          :class="isActive('/categories') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600'"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          <span class="sidebar-text">Kategori</span>
        </router-link>

        <router-link 
          to="/items" 
          class="nav-link relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
          :class="isActive('/items') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600'"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          <span class="sidebar-text">Barang</span>
        </router-link>

        <p class="sidebar-label sidebar-text px-4 pt-6 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Aktivitas</p>

        <router-link 
          to="/transactions" 
          class="nav-link relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
          :class="isActive('/transactions') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600'"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
          <span class="sidebar-text">Transaksi</span>
        </router-link>
      </nav>

      <!-- User Section -->
      <div class="absolute bottom-0 w-full p-4 border-t border-gray-100 bg-gray-50/50">
        <div class="sidebar-user flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200 flex-shrink-0">
            {{ authStore.userInitial }}
          </div>
          <div class="sidebar-user-info sidebar-text flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800 truncate">{{ authStore.userName }}</p>
            <button @click="logout" class="text-xs text-red-500 hover:text-red-600 font-medium">Logout</button>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content flex-1">
      <!-- Top Header -->
      <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-40">
        <h2 class="text-xl font-bold text-gray-800">
          <slot name="title">Dashboard</slot>
        </h2>
        <div class="flex items-center gap-4">
          <span class="text-sm text-gray-500">{{ formatDate() }}</span>
        </div>
      </header>

      <div class="p-8">
        <slot />
      </div>
    </main>
  </div>
</template>
