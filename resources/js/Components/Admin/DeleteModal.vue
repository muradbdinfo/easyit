<script setup>
defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: 'Delete Confirmation' },
    message: { type: String, default: 'Are you sure? This action cannot be undone.' },
})
defineEmits(['confirm', 'cancel'])
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="$emit('cancel')"></div>
        <div class="relative bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ title }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ message }}</p>
            <div class="flex justify-end gap-3">
                <button @click="$emit('cancel')" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                <button @click="$emit('confirm')" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>
</template>
