<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ service: Object })

const icons = {
    network: '🌐', globe: '🔗', server: '🖥️', code: '💻',
    database: '🗄️', users: '👥', shield: '🛡️', message: '💬',
}
</script>
<template>
    <Link :href="`/services/${service.slug}`" class="group bg-white rounded-xl border border-gray-100 p-6 hover:shadow-lg hover:border-indigo-100 transition-all duration-300">
        <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-2xl mb-4 group-hover:bg-indigo-100 transition">
            {{ icons[service.icon] || '⚙️' }}
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition">{{ service.title }}</h3>
        <p class="text-sm text-gray-500 line-clamp-2">{{ service.short_description }}</p>
        <span class="inline-flex items-center mt-4 text-sm font-medium text-indigo-600 group-hover:gap-2 transition-all">
            Learn More <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    </Link>
</template>
