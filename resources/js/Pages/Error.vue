<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    status: Number,
})

const messages = {
    403: "You don't have permission to access this page.",
    404: "The page you're looking for doesn't exist.",
    500: "Something went wrong on our end.",
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
        <Head><title>{{ status }} Error - Easy IT</title></Head>
        <div class="text-center">
            <p class="text-7xl font-bold text-indigo-600">{{ status }}</p>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">
                {{ status === 404 ? 'Page Not Found' : status === 403 ? 'Forbidden' : 'Server Error' }}
            </h1>
            <p class="mt-2 text-gray-500">{{ messages[status] || 'An unexpected error occurred.' }}</p>
            <div class="mt-8 flex justify-center gap-4">
                <Link href="/" class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-700 transition">
                    Go Home
                </Link>
                <Link href="/contact" class="rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Contact Us
                </Link>
            </div>
        </div>
    </div>
</template>
