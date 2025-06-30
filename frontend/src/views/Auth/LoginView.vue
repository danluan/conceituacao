<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore, type LoginData } from '../../stores/auth'
import { Input } from '../../components/ui/input'
import { Button } from '../../components/ui/button'
import { Label } from '../../components/ui/label'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive<LoginData>({
  email: '',
  password: ''
})

const errors = ref<Record<string, string[]>>({})
const isSubmitting = ref(false)

const validateForm = () => {
  errors.value = {}
  
  if (!form.email.trim()) {
    errors.value.email = ['O email é obrigatório']
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.value.email = ['Digite um email válido']
  }
  
  if (!form.password) {
    errors.value.password = ['A senha é obrigatória']
  }
  
  return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
  if (!validateForm()) {
    return
  }

  isSubmitting.value = true
  authStore.clearError()

  try {
    await authStore.login(form)
    router.push('/')
  } catch (error) {
    console.error('Erro no login:', error)
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Entrar na sua conta
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Faça login para acessar sua conta
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="space-y-4">
          <div>
            <Label for="email">Email</Label>
            <Input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="Digite seu email"
              :class="errors.email ? 'border-red-500' : ''"
              class="mt-1"
            />
            <div v-if="errors.email" class="mt-1">
              <p v-for="error in errors.email" :key="error" class="text-sm text-red-600">
                {{ error }}
              </p>
            </div>
          </div>

          <div>
            <Label for="password">Senha</Label>
            <Input
              id="password"
              v-model="form.password"
              type="password"
              placeholder="Digite sua senha"
              :class="errors.password ? 'border-red-500' : ''"
              class="mt-1"
            />
            <div v-if="errors.password" class="mt-1">
              <p v-for="error in errors.password" :key="error" class="text-sm text-red-600">
                {{ error }}
              </p>
            </div>
          </div>
        </div>

        <div v-if="authStore.error" class="mt-4">
          <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-600">{{ authStore.error }}</p>
          </div>
        </div>

        <!-- Botão de Submit -->
        <div>
          <Button
            type="submit"
            :disabled="isSubmitting || authStore.loading"
            class="w-full"
            size="lg"
          >
            <span v-if="isSubmitting || authStore.loading">Entrando...</span>
            <span v-else>Entrar</span>
          </Button>
        </div>

        <!-- Link para registro -->
        <div class="text-center">
          <p class="text-sm text-gray-600">
            Não tem uma conta?
            <router-link
              to="/register"
              class="font-medium text-indigo-600 hover:text-indigo-500"
            >
              Criar conta
            </router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
</style>
