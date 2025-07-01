<script setup lang="ts">
    import { Header } from "@/components/ui/header";
    import type { User } from "@/types/types";
    import { UserModal } from "@/components/userModal";
    import ConfirmDialog from "@/components/ui/ConfirmDialog.vue";
    import ToastContainer from "@/components/ui/ToastContainer.vue";
    import { ref, onMounted } from 'vue'
    import api from '@/services/api'
    import Button from "@/components/ui/button/Button.vue";
    import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell, TableCaption } from "@/components/ui/table";
    import { useToast } from '@/composables/useToast'

    const { success, error } = useToast()
    const users = ref<User[]>([])
    const showModal = ref(false)
    const showDeleteDialog = ref(false)
    const selectedUser = ref<User | null>(null)
    const loading = ref({
        fetch: false,
        delete: false
    })

const fetchUsers = async () => {
    loading.value.fetch = true
    try {
        const res = await api.get('/user')
        users.value = res.data
        console.log('Users fetched:', users.value)
    } catch (err) {
        console.error('Erro ao buscar usuários:', err)
        error('Erro ao carregar a lista de usuários')
    } finally {
        loading.value.fetch = false
    }
}

const createUser = () => {
    selectedUser.value = null
    showModal.value = true
}

const editUser = (user: User) => {
    selectedUser.value = user
    showModal.value = true
}

const confirmDeleteUser = (user: User) => {
    selectedUser.value = user
    showDeleteDialog.value = true
}

const deleteUser = async () => {
    if (!selectedUser.value) return
    
    loading.value.delete = true
    try {
        await api.delete(`/user/${selectedUser.value.id}`)
        users.value = users.value.filter(user => user.id !== selectedUser.value!.id)
        showDeleteDialog.value = false
        selectedUser.value = null
        success('Usuário excluído com sucesso')
    } catch (err) {
        console.error('Erro ao deletar usuário:', err)
        error('Erro ao excluir usuário')
    } finally {
        loading.value.delete = false
    }
}

const closeModal = () => {
    showModal.value = false
    selectedUser.value = null
}

const closeDeleteDialog = () => {
    showDeleteDialog.value = false
    selectedUser.value = null
}

const handleUserCreated = () => {
    fetchUsers() // Recarrega a lista após criar
    closeModal()
    success('Usuário criado com sucesso')
}

const handleUserUpdated = () => {
    fetchUsers() // Recarrega a lista após editar
    closeModal()
    success('Usuário atualizado com sucesso')
}

onMounted(fetchUsers)
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Header title="Gerenciamento de Usuários" />

        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <Button @click="createUser" class="mb-4">
                Adicionar Usuário
            </Button>
        </div>
        <!-- Tabela de Usuários da aplicação -->
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
                    <TableHead>Perfis</TableHead>
                    <TableHead class="text-right">
                    Ações
                    </TableHead>
                </TableRow>
                </TableHeader>
                <TableBody>
                <TableRow v-for="user in users" :key="user.id">
                    <TableCell class="font-medium">
                        {{ user.id }}
                    </TableCell>
                    <TableCell>{{ user.name }}</TableCell>
                    <TableCell>{{ user.email }}</TableCell>
                    <TableCell>{{ user.profiles.map(profile => profile.name).join(' | ') }}</TableCell>
                    <TableCell class="text-right">
                        <Button @click="editUser(user)" class="mr-2" variant="outline" size="sm">
                            Editar
                        </Button>
                        <Button @click="confirmDeleteUser(user)" variant="outline" size="sm">
                            Excluir
                        </Button>
                    </TableCell>
                </TableRow>
                <TableRow v-if="users.length === 0">
                    <TableCell colspan="4" class="text-center py-8 text-gray-500">
                        Nenhum usuário encontrado
                    </TableCell>
                </TableRow>
                </TableBody>
            </Table>
        </main>

        <UserModal 
            :isVisible="showModal" 
            :user="selectedUser"
            @close="closeModal" 
            @created="handleUserCreated"
            @updated="handleUserUpdated"
        />

        <ConfirmDialog
            :isVisible="showDeleteDialog"
            title="Excluir Usuário"
            :message="`Tem certeza que deseja excluir o usuário '${selectedUser?.name}'? Esta ação não pode ser desfeita.`"
            :loading="loading.delete"
            @close="closeDeleteDialog"
            @confirm="deleteUser"
        />

        <!-- Toast Notifications -->
        <ToastContainer />

    </div>
</template>

<style scoped></style>
