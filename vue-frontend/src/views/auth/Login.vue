<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
  remember: false
})
const error = ref('')
const loading = ref(false)

async function handleLogin() {
  loading.value = true
  error.value = ''
  
  const result = await authStore.login(form.value)
  
  if (result.success) {
    router.push('/dashboard')
  } else {
    error.value = result.message
  }
  
  loading.value = false
}
</script>

<template>
  <div class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4 shadow-lg shadow-blue-200">S</div>
        <h1 class="text-2xl font-bold text-gray-800">Sortir Barang</h1>
        <p class="text-gray-500 mt-1">Masuk ke akun Anda</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div v-if="error" class="bg-red-50 text-red-600 text-sm p-4 rounded-xl mb-6">
          {{ error }}
        </div>

        <form @submit.prevent="handleLogin">
          <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input 
              v-model="form.email"
              type="email" 
              required
              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input 
              v-model="form.password"
              type="password" 
              required
              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2">
              <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
              <span class="text-sm text-gray-600">Ingat saya</span>
            </label>
          </div>

          <button 
            type="submit" 
            :disabled="loading"
            class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-medium py-3 rounded-xl transition disabled:opacity-50"
          >
            {{ loading ? 'Loading...' : 'Masuk' }}
          </button>
        </form>
      </div>

      <p class="text-center text-sm text-gray-500 mt-6">
        Belum punya akun? 
        <router-link to="/register" class="text-blue-500 hover:underline">Daftar</router-link>
      </p>
    </div>
  </div>
</template>
