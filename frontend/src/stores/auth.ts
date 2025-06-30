import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export interface User {
  id: string
  name: string
  email: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface LoginData {
  email: string
  password: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('access_token'))
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)

  const register = async (data: RegisterData) => {
    loading.value = true
    error.value = null

    try {
      const response = await fetch('http://localhost:8000/api/auth/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(data),
      })

      const result = await response.json()

      if (!response.ok) {
        throw new Error(result.message || 'Erro no registro')
      }

      user.value = result.user.name
      token.value = result.access_token
      localStorage.setItem('access_token', result.access_token)

      return result
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Erro desconhecido'
      throw err
    } finally {
      loading.value = false
    }
  }

  const login = async (data: LoginData) => {
    loading.value = true
    error.value = null

    try {
      const response = await fetch('http://localhost:8000/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(data),
      })

      const result = await response.json()

      if (!response.ok) {
        throw new Error(result.message || 'Erro no login')
      }

      user.value = result.user
      token.value = result.access_token
      localStorage.setItem('access_token', result.access_token)

      return result
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Erro desconhecido'
      throw err
    } finally {
      loading.value = false
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('access_token')
  }

  const clearError = () => {
    error.value = null
  }

  const checkAuth = async () => {
    if (!token.value) return

    try {
      const response = await fetch('http://localhost:8000/api/user', {
        headers: {
          'Authorization': `Bearer ${token.value}`,
          'Accept': 'application/json',
        },
      })

      if (response.ok) {
        const userData = await response.json()
        user.value = userData
      } else {
        // Token inválido, remover
        logout()
      }
    } catch (err) {
      // Erro na verificação, remover token
      logout()
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    register,
    login,
    logout,
    clearError,
    checkAuth,
  }
})
