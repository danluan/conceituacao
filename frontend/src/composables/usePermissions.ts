import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

export const usePermissions = () => {
  const authStore = useAuthStore()

  const canAccessAdminRoutes = computed(() => {
    return authStore.isFullyAuthenticated && authStore.hasProfile('ADMINISTRATOR')
  })

  const canAccessUsers = computed(() => {
    return canAccessAdminRoutes.value
  })

  const canAccessProfiles = computed(() => {
    return canAccessAdminRoutes.value
  })

  const requiresAdmin = (profileNames: string[] = ['ADMINISTRATOR']) => {
    return authStore.isFullyAuthenticated && authStore.hasAnyProfile(profileNames)
  }

  return {
    canAccessAdminRoutes,
    canAccessUsers,
    canAccessProfiles,
    requiresAdmin,
  }
}
