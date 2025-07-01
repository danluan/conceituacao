
<script setup lang="ts">
    import { useRouter } from 'vue-router'
    import { useAuthStore } from '@/stores/auth'
    import { Button } from '@/components/ui/button'
    
    defineProps<{
        title?: string
    }>()
    
    const authStore = useAuthStore()
    const router = useRouter()
    const handleLogout = () => {
      authStore.logout()
      router.push('/login')
    }
</script>

<template>
  <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-3xl font-bold text-gray-900">{{ title }}</h1>

          <div v-if="authStore.isAuthenticated" class="flex items-center space-x-4">
            <span class="text-sm text-gray-700">
              Olá, {{ authStore.user?.name }}!
            </span>
            <Button variant="outline" @click="handleLogout">
              Sair
            </Button>
          </div>
          
          <div v-else class="flex items-center space-x-4">
            <Button variant="outline" @click="router.push('/login')">
              Entrar
            </Button>
            <Button @click="router.push('/register')">
              Criar conta
            </Button>
          </div>
        </div>
      </div>
    </header>
</template>


<style scoped>
</style>