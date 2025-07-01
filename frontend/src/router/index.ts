import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { usePermissions } from '@/composables/usePermissions'
import { useNotifications } from '@/composables/useNotifications'
import HomeView from '../views/HomeView.vue'
import RegisterView from '../views/Auth/RegisterView.vue'
import LoginView from '../views/Auth/LoginView.vue'
import ProfilesView from '@/views/ProfilesView.vue'
import UserView from '@/views/UserView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/users',
      name: 'users',
      component: UserView,
      meta: {
        requiresAuth: true,
        requiresAdmin: true,
      },
    },
    {
      path: '/profiles',
      name: 'profiles',
      component: ProfilesView,
      meta: {
        requiresAuth: true,
        requiresAdmin: true,
      },
    }
  ],
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  const { showLoginRequired, showSessionExpired, showAdminRequired } = useNotifications()

  // Verificar se a rota requer autenticação
  if (to.meta.requiresAuth && !authStore.token) {
    showLoginRequired()
    next({ name: 'login' })
    return
  }

  // Se tem token mas não tem dados do usuário, buscar
  if (authStore.token && !authStore.user) {
    try {
      await authStore.checkAuth()
    } catch (error) {
      // Se falhar ao verificar auth, redirecionar para login
      showSessionExpired()
      authStore.logout()
      next({ name: 'login' })
      return
    }
  }

  // Verificar se a rota requer perfil de administrador
  if (to.meta.requiresAdmin) {
    // Garantir que temos os dados do usuário carregados
    if (!authStore.user) {
      showSessionExpired()
      next({ name: 'login' })
      return
    }

    // Verificar se tem o perfil de administrador
    const hasAdminProfile = authStore.hasProfile('ADMINISTRATOR')
    if (!hasAdminProfile) {
      showAdminRequired()
      next({ name: 'home' })
      return
    }
  }

  next()
})

export default router
