import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import api from '@/services/api'
import { useNotifications } from '@/composables/useNotifications'

export interface User {
  id: string
  name: string
  email: string
  profiles: Profile[]
}

export interface Profile {
  id: string
  name: string
  description: string
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
  const { showSuccess, showError } = useNotifications()
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('access_token'))
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value)
  const isFullyAuthenticated = computed(() => !!token.value && !!user.value)
  const isAdmin = computed(() => 
    user.value?.profiles?.some(profile => profile.name === 'ADMINISTRATOR') ?? false
  )

  const register = async (data: RegisterData) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.post('/auth/register', data)

      const result = response.data

      user.value = result.user
      token.value = result.access_token
      localStorage.setItem('access_token', result.access_token)

      await checkAuth()

      showSuccess('Registro realizado com sucesso!')
      return result
    } catch (err) {
      if (axios.isAxiosError(err) && err.response) {
        error.value = err.response.data.message || 'Erro no registro'
        showError(error.value || 'Erro no registro')
      } else {
        error.value = err instanceof Error ? err.message : 'Erro desconhecido'
        showError(error.value || 'Erro desconhecido')
      }
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

      await checkAuth()

      showSuccess('Login realizado com sucesso!')
      return result
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Erro desconhecido'
      showError(error.value || 'Erro no login')
      throw err
    } finally {
      loading.value = false
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('access_token')
    showSuccess('Logout realizado com sucesso!')
  }

  const hasProfile = (profileName: string) => {
    return user.value?.profiles?.some(profile => profile.name === profileName) ?? false
  }

  const hasAnyProfile = (profileNames: string[]) => {
    return profileNames.some(name => hasProfile(name))
  }

  const clearError = () => {
    error.value = null
  }

  const checkAuth = async () => {
    if (!token.value) return

    try {
      const response = await fetch('http://localhost:8000/api/auth/user', {
        headers: {
          'Authorization': `Bearer ${token.value}`,
          'Accept': 'application/json',
        },
      })

      if (response.ok) {
        const userData = await response.json()
        user.value = userData
        console.log('User data loaded:', userData)
      } else {
        console.error('Failed to load user data:', response.status)
        logout()
      }
    } catch (err) {
      console.error('Error checking auth:', err)
      logout()
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isFullyAuthenticated,
    isAdmin,
    hasProfile,
    hasAnyProfile,
    register,
    login,
    logout,
    clearError,
    checkAuth,
  }
})
