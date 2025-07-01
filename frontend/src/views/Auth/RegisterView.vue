<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore, type RegisterData } from '../../stores/auth'
import { Input } from '../../components/ui/input'
import { Button } from '../../components/ui/button'
import { Label } from '../../components/ui/label'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive<RegisterData>({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const errors = ref<Record<string, string[]>>({})
const isSubmitting = ref(false)

const validateForm = () => {
  errors.value = {}

  if (!form.name.trim()) {
    errors.value.name = ['O nome é obrigatório']
  }

  if (!form.email.trim()) {
    errors.value.email = ['O email é obrigatório']
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.value.email = ['Digite um email válido']
  }

  if (!form.password) {
    errors.value.password = ['A senha é obrigatória']
  } else if (form.password.length < 6) {
    errors.value.password = ['A senha deve ter pelo menos 6 caracteres']
  }

  if (form.password !== form.password_confirmation) {
    errors.value.password_confirmation = ['As senhas não coincidem']
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
    await authStore.register(form)

    router.push('/')
  } catch (error) {
    console.error('Erro no registro:', error)
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
  >
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Criar nova conta
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Faça seu registro e comece a usar nossa plataforma
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="space-y-4">
          <!-- Nome -->
          <div>
            <Label for="name">Nome completo</Label>
            <Input
              id="name"
              v-model="form.name"
              type="text"
              placeholder="Digite seu nome completo"
              :class="errors.name ? 'border-red-500' : ''"
              class="mt-1"
            />
            <div v-if="errors.name" class="mt-1">
              <p v-for="error in errors.name" :key="error" class="text-sm text-red-600">
                {{ error }}
              </p>
            </div>
          </div>

          <!-- Email -->
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

          <!-- Senha -->
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
              <p
                v-for="error in errors.password"
                :key="error"
                class="text-sm text-red-600"
              >
                {{ error }}
              </p>
            </div>
          </div>

          <!-- Confirmação de Senha -->
          <div>
            <Label for="password_confirmation">Confirmar senha</Label>
            <Input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              placeholder="Confirme sua senha"
              :class="errors.password_confirmation ? 'border-red-500' : ''"
              class="mt-1"
            />
            <div v-if="errors.password_confirmation" class="mt-1">
              <p
                v-for="error in errors.password_confirmation"
                :key="error"
                class="text-sm text-red-600"
              >
                {{ error }}
              </p>
            </div>
          </div>
        </div>

        <!-- Erro da API -->
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
            <span v-if="isSubmitting || authStore.loading">Criando conta...</span>
            <span v-else>Criar conta</span>
          </Button>
        </div>

        <!-- Link para login -->
        <div class="text-center">
          <p class="text-sm text-gray-600">
            Já tem uma conta?
            <router-link
              to="/login"
              class="font-medium text-indigo-600 hover:text-indigo-500"
            >
              Faça login
            </router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped></style>
