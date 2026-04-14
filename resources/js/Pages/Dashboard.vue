<script setup>
import { computed, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const user = computed(() => usePage().props.auth?.user)

onMounted(() => {
    if (user.value?.is_admin) {
        router.visit('/admin/dashboard')
    }
})
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Welcome, {{ user?.name }}</h2>
            <p class="text-gray-500">Redirecting...</p>
        </div>
    </div>
</template>
