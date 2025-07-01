import { useToast, POSITION, TYPE } from 'vue-toastification'

export const useNotifications = () => {
  const toast = useToast()

  const showSuccess = (message: string) => {
    toast.success(message)
  }

  const showError = (message: string) => {
    toast.error(message)
  }

  const showWarning = (message: string) => {
    toast.warning(message)
  }

  const showInfo = (message: string) => {
    toast.info(message)
  }

  const showPermissionError = () => {
    toast.error('Você não possui permissão para realizar esta ação.')
  }

  const showLoginRequired = () => {
    toast.error('Você precisa estar logado para acessar esta página.')
  }

  const showSessionExpired = () => {
    toast.error('Sessão expirada. Faça login novamente.')
  }

  const showAdminRequired = () => {
    toast.error('Você não possui permissão para acessar esta página. É necessário ter perfil de Administrador.')
  }

  return {
    showSuccess,
    showError,
    showWarning,
    showInfo,
    showPermissionError,
    showLoginRequired,
    showSessionExpired,
    showAdminRequired,
  }
}
