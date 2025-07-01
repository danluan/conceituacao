<template>
    <div v-if="isVisible" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="closeModal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4" @click.stop>
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ title }}
                </h3>
                <button 
                    @click="closeModal"
                    class="text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="handleSubmit" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nome
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': errors.name }"
                        />
                        <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': errors.email }"
                        />
                        <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Senha
                        </label>
                        <input
                            v-model="form.password"
                            type="password"
                            id="password"
                            :required="!isEditing"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': errors.password }"
                        />
                        <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
                    </div>
                    
                    <div>
                        <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar Senha
                        </label>
                        <input
                            v-model="form.passwordConfirmation"
                            type="password"
                            id="passwordConfirmation"
                            :required="!isEditing"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': errors.passwordConfirmation }"
                        />
                        <p v-if="errors.passwordConfirmation" class="text-red-500 text-sm mt-1">{{ errors.passwordConfirmation }}</p>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button
                        type="button"
                        @click="closeModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Carregando...
                        </span>
                        <span v-else>
                            {{ isEditing ? 'Atualizar' : 'Criar' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import type { User } from '@/types/types'
import api from '@/services/api'
import { useToast } from '@/composables/useToast'

interface Props {
    isVisible: boolean
    user?: User | null
}

interface Emits {
    (e: 'close'): void
    (e: 'created'): void
    (e: 'updated'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()
const { error: showError } = useToast()

const loading = ref(false)
const errors = ref<Record<string, string>>({})

const form = ref({
    name: '',
    email: '',
    password: '',
    passwordConfirmation: ''
})

const isEditing = computed(() => !!props.user?.id)
const title = computed(() => isEditing.value ? 'Editar Usuário' : 'Criar Usuário')

// Reset form when modal opens/closes or user changes
watch([() => props.isVisible, () => props.user], () => {
    if (props.isVisible) {
        resetForm()
        clearErrors()
        if (props.user) {
            form.value.name = props.user.name
            form.value.email = props.user.email
        }
    }
})

const resetForm = () => {
    form.value = {
        name: '',
        email: '',
        password: '',
        passwordConfirmation: ''
    }
}

const clearErrors = () => {
    errors.value = {}
}

const validateForm = () => {
    clearErrors()
    let isValid = true

    if (!form.value.name.trim()) {
        errors.value.name = 'Nome é obrigatório'
        isValid = false
    }

    if (!form.value.email.trim()) {
        errors.value.email = 'Email é obrigatório'
        isValid = false
    }

    if (!isEditing.value) {
        if (!form.value.password) {
            errors.value.password = 'Senha é obrigatória'
            isValid = false
        }

        if (form.value.password !== form.value.passwordConfirmation) {
            errors.value.passwordConfirmation = 'Senhas não coincidem'
            isValid = false
        }
    }

    return isValid
}

const handleSubmit = async () => {
    if (!validateForm()) return

    loading.value = true
    clearErrors()

    try {
        if (isEditing.value) {
            await updateUser()
        } else {
            await createUser()
        }
    } catch (error: any) {
        handleApiErrors(error)
    } finally {
        loading.value = false
    }
}

const createUser = async () => {
    const response = await api.post('/user', {
        name: form.value.name,
        email: form.value.email,
        password: form.value.password,
        password_confirmation: form.value.passwordConfirmation
    })

    if (response.status === 201) {
        emit('created')
        closeModal()
    }
}

const updateUser = async () => {
    const response = await api.put(`/user/${props.user!.id}`, {
        name: form.value.name,
        email: form.value.email
    })

    if (response.status === 200) {
        emit('updated')
        closeModal()
    }
}

const handleApiErrors = (error: any) => {
    if (error.response?.status === 422) {
        const validationErrors = error.response.data.errors
        Object.keys(validationErrors).forEach(key => {
            errors.value[key] = validationErrors[key][0]
        })
    } else {
        console.error('Erro ao salvar usuário:', error)
        showError('Erro ao salvar usuário. Tente novamente.')
    }
}

const closeModal = () => {
    emit('close')
}
</script>