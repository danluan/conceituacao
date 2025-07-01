<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { X } from 'lucide-vue-next'
import { useNotifications } from '@/composables/useNotifications'
import type { Profile, User } from '@/types/types'
import api from '@/services/api'
import { useToast } from '@/composables/useToast'
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import Button from '@/components/ui/button/Button.vue'
import { AxiosError } from 'axios'

interface Props {
    isVisible: boolean
    user: User | null
}

interface Emits {
    (e: 'close'): void
    (e: 'created'): void
}
const { showSuccess, showError } = useNotifications()
const props = defineProps<Props>()
const emit = defineEmits<Emits>()
const { success, error } = useToast()

const loading = ref(false)
const form = ref({ profile_id: '' })

const profiles = ref<Profile[]>([])
const userProfiles = ref<Profile[]>([])

const getProfiles = async () => {
    try {
        const res = await api.get('/profile')
        profiles.value = res.data
    } catch {
        error('Não foi possível carregar lista de perfis')
    }
}

const addProfile = async () => {
    if (!props.user || !form.value.profile_id) return

    loading.value = true
    const profileId = Number(form.value.profile_id)

    try {
        const response = await api.post(`/user/${props.user.id}/profile`, {
            profile_id: profileId
        })

        showSuccess('Perfil adicionado')

        form.value.profile_id = ''
        await updateUserProfiles()

    } catch (err) {
        const axiosError = err as AxiosError
        const error = (axiosError.response?.data as any)?.message || 'Erro desconhecido'
        showError(error)
    } finally {
        loading.value = false
    }
}

const removeProfile = async (profileId: number) => {
    if (!props.user) return
    loading.value = true
    try {
        await api.delete(`/user/${props.user.id}/profile/${profileId}`)
        showSuccess('Perfil removido')

        // Remove profile from user profiles
        props.user.profiles = props.user.profiles.filter(p => p.id !== profileId)
        form.value.profile_id = ''

    } catch (err) {
        const axiosError = err as AxiosError
        const error = (axiosError.response?.data as any)?.message || 'Erro desconhecido'
        showError(error)
    } finally {
        loading.value = false
    }
}

const updateUserProfiles = async () => {

    try {
        const response = await api.get(`/user/${props.user?.id}`)

        if (response.status === 200) {

            //atualiza lista de user?.profiles
            console.log(response.data.profiles)
            props.user!.profiles = response.data.profiles

        }
    } catch (err) {
        const axiosError = err as AxiosError
        const error = (axiosError.response?.data as any)?.message || 'Erro desconhecido'
        showError(error)
    }
    finally {
        loading.value = false
    }

}

const closeModal = () => emit('close')

onMounted(getProfiles);
</script>

<template>
    <div v-if="props.isVisible" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        @click="closeModal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-0" @click.stop>
            <!-- Cabeçalho -->
            <div class="p-6 border-b text-center">
                <h3 class="text-xl font-semibold">Edição Perfis</h3>
                <p class="text-gray-700 mt-1">{{ props.user?.name }}</p>
            </div>

            <div class="p-4">
                <h4 class="text-sm font-medium mb-2">Perfis Atuais</h4>
                <div class="flex flex-wrap gap-2">
                    <div v-for="profile in user?.profiles || []" :key="profile.id"
                        class="flex items-center bg-gray-200 rounded-full px-3 py-1 text-sm">
                        <button class="mr-1" @click="removeProfile(profile.id)">
                            <X class="w-4 h-4" />
                        </button>
                        <span>{{ profile.name.toUpperCase() }}</span>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t">
                <label class="block text-sm font-medium mb-2">
                    Adicionar perfil
                </label>
                <div class="flex gap-2">
                    <Select v-model="form.profile_id" class="flex-1" :disabled="loading">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Selecione um perfil" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="option in profiles" :key="option.id" :value="option.id">
                                    {{ option.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <Button @click="addProfile" :loading="loading" variant="default">
                        +
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
