<script setup lang="ts">
import { useAuthStore } from '../stores/auth'
import { Button } from '../components/ui/button'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-3xl font-bold text-gray-900">Home</h1>
          
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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div v-if="authStore.isAuthenticated" class="border-4 border-dashed border-gray-200 rounded-lg p-8">
          <div class="text-center">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">
              Bem-vindo(a), {{ authStore.user?.name }}!
            </h2>
            <p class="text-gray-600 mb-6">
              Você está conectado(a) com o email: {{ authStore.user?.email }}
            </p>
            <Button @click="router.push('/profiles')" class="mb-4">
              Gerenciar Perfis
            </Button>
            <Button @click="router.push('/users')">
              Gerenciar Usuários
            </Button>
          </div>
        </div>
        
        <div v-else class="border-4 border-dashed border-gray-200 rounded-lg p-8">
          <div class="text-center">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">
              Bem-vindo à nossa plataforma!
            </h2>
            <p class="text-gray-600 mb-6">
              Para acessar todas as funcionalidades, faça login ou crie uma nova conta.
            </p>
            <div class="space-x-4">
              <Button @click="router.push('/login')">
                Fazer Login
              </Button>
              <Button variant="outline" @click="router.push('/register')">
                Criar Conta
              </Button>
            </div>
          </div>
        </div>
      </div>
    </main>

  </div>
</template>

<style scoped>
</style>
