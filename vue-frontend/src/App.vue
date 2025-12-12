<script setup>
import { useAuthStore } from './stores/auth'
import AppLayout from './components/AppLayout.vue'

const authStore = useAuthStore()
</script>

<template>
  <AppLayout v-if="authStore.isAuthenticated">
    <router-view />
  </AppLayout>
  <router-view v-else />
</template>
