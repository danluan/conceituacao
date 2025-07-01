<template>
    <div v-if="isVisible" class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50" @click="close">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4" @click.stop>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ title }}
                        </h3>
                    </div>
                </div>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-500">
                        {{ message }}
                    </p>
                </div>
                
                <div class="flex justify-between gap-3">
                    <Button
                        type="button"
                        @click="close"
                        variant="outline"
                    >
                        Cancelar
                    </Button>
                    <Button
                        type="button"
                        @click="confirm"
                        :disabled="loading"
                        variant="destructive"
                    >
                        <span v-if="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Carregando...
                        </span>
                        <span v-else>
                            Confirmar
                        </span>
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue'

interface Props {
    isVisible: boolean
    title: string
    message: string
    loading?: boolean
}

interface Emits {
    (e: 'close'): void
    (e: 'confirm'): void
}

defineProps<Props>()
const emit = defineEmits<Emits>()

const close = () => {
    emit('close')
}

const confirm = () => {
    emit('confirm')
}
</script>
