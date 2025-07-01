<script setup lang="ts">
    import { Header } from "@/components/ui/header";
    import type { Profile, User } from "@/types/types";
    import { UserModal } from "@/components/userModal";
    import ConfirmDialog from "@/components/ui/ConfirmDialog.vue";
    import ToastContainer from "@/components/ui/ToastContainer.vue";
    import { ref, onMounted } from 'vue'
    import api from '@/services/api'
    import Button from "@/components/ui/button/Button.vue";
    import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell, TableCaption } from "@/components/ui/table";
    import { useToast } from '@/composables/useToast'
import { ProfileModal } from "@/components/profileModal";

    const { success, error } = useToast()
    const profiles = ref<Profile[]>([])
    const showModal = ref(false)
    const showDeleteDialog = ref(false)
    const selectedProfile = ref<Profile | null>(null)
    const loading = ref({
        fetch: false,
        delete: false
    })

const fetchUsers = async () => {
    loading.value.fetch = true
    try {
        const res = await api.get('/profile')
        profiles.value = res.data
        console.log('Profiles fetched:', profiles.value)
    } catch (err) {
        console.error('Erro ao buscar perfis:', err)
        error('Erro ao carregar a lista de perfis')
    } finally {
        loading.value.fetch = false
    }
}

const createProfile = () => {
    selectedProfile.value = null
    showModal.value = true
}

const editProfile = (user: User) => {
    selectedProfile.value = user
    showModal.value = true
}

const confirmDeleteProfile = (user: User) => {
    selectedProfile.value = user
    showDeleteDialog.value = true
}

const deleteProfile = async () => {
    if (!selectedProfile.value) return

    loading.value.delete = true
    try {
        await api.delete(`/profile/${selectedProfile.value.id}`)
        profiles.value = profiles.value.filter(profile => profile.id !== selectedProfile.value!.id)
        showDeleteDialog.value = false
        selectedProfile.value = null
        success('Perfil excluído com sucesso')
    } catch (err) {
        console.error('Erro ao deletar perfil:', err)
        error('Erro ao excluir usuário')
    } finally {
        loading.value.delete = false
    }
}

const closeModal = () => {
    showModal.value = false
    selectedProfile.value = null
}

const closeDeleteDialog = () => {
    showDeleteDialog.value = false
    selectedProfile.value = null
}

const handleUserCreated = () => {
    fetchUsers()
    closeModal()
    success('Usuário criado com sucesso')
}

const handleUserUpdated = () => {
    fetchUsers()
    closeModal()
    success('Usuário atualizado com sucesso')
}

onMounted(fetchUsers)
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Header title="Gerenciamento de Usuários" />

        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <Button @click="createProfile" class="mb-4">
                Adicionar Perfil
            </Button>
        </div>
        <!-- Tabela de Perfis da aplicação -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div v-if="loading.fetch" class="flex justify-center items-center py-8">
                <div class="flex items-center space-x-2">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Carregando usuários...</span>
                </div>
            </div>

            <Table v-else>
                <TableHeader>
                <TableRow>
                    <TableHead class="w-[100px]">
                    ID
                    </TableHead>
                    <TableHead>Name</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead class="text-right">
                    Ações
                    </TableHead>
                </TableRow>
                </TableHeader>
                <TableBody>
                <TableRow v-for="profile in profiles" :key="profile.id">
                    <TableCell class="font-medium">
                        {{ profile.id }}
                    </TableCell>
                    <TableCell>{{ profile.name }}</TableCell>
                    <TableCell>{{ profile.description }}</TableCell>
                    <TableCell class="text-right">
                        <Button @click="editProfile(profile)" class="mr-2" variant="outline" size="sm">
                            Editar
                        </Button>
                        <Button @click="confirmDeleteProfile(profile)" variant="outline" size="sm">
                            Excluir
                        </Button>
                    </TableCell>
                </TableRow>
                <TableRow v-if="profiles.length === 0">
                    <TableCell colspan="4" class="text-center py-8 text-gray-500">
                        Nenhum perfil encontrado
                    </TableCell>
                </TableRow>
                </TableBody>
            </Table>
        </main>

        <ProfileModal
            :isVisible="showModal"
            :profile="selectedProfile"
            @close="closeModal"
            @created="handleUserCreated"
            @updated="handleUserUpdated"
        />

        <ConfirmDialog
            :isVisible="showDeleteDialog"
            title="Excluir Perfil"
            :message="`Tem certeza que deseja excluir o perfil '${selectedProfile?.name}'? Esta ação não pode ser desfeita.`"
            :loading="loading.delete"
            @close="closeDeleteDialog"
            @confirm="deleteProfile"
        />

        <!-- Toast Notifications -->
        <ToastContainer />

    </div>
</template>

<style scoped></style>
