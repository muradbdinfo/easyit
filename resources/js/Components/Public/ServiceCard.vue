<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ service: Object })

const iconMap = {
    network: { icon: '🌐', gradient: 'from-cyan-500/10 to-blue-500/10', border: 'group-hover:border-cyan-200' },
    globe: { icon: '🔗', gradient: 'from-violet-500/10 to-purple-500/10', border: 'group-hover:border-violet-200' },
    server: { icon: '🖥️', gradient: 'from-emerald-500/10 to-teal-500/10', border: 'group-hover:border-emerald-200' },
    code: { icon: '💻', gradient: 'from-orange-500/10 to-amber-500/10', border: 'group-hover:border-orange-200' },
    database: { icon: '🗄️', gradient: 'from-blue-500/10 to-indigo-500/10', border: 'group-hover:border-blue-200' },
    users: { icon: '👥', gradient: 'from-pink-500/10 to-rose-500/10', border: 'group-hover:border-pink-200' },
    shield: { icon: '🛡️', gradient: 'from-slate-500/10 to-gray-500/10', border: 'group-hover:border-slate-200' },
    message: { icon: '💬', gradient: 'from-green-500/10 to-emerald-500/10', border: 'group-hover:border-green-200' },
}
const getIcon = (key) => iconMap[key] || iconMap.network
</script>

<template>
    <Link :href="`/services/${service.slug}`"
        :class="['group relative bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1', getIcon(service.icon).border]">
        <!-- Gradient bg on hover -->
        <div :class="['absolute inset-0 rounded-2xl bg-gradient-to-br opacity-0 group-hover:opacity-100 transition-opacity duration-300', getIcon(service.icon).gradient]"></div>

        <div class="relative">
            <div class="text-3xl mb-4">{{ getIcon(service.icon).icon }}</div>
            <h3 class="text-base font-bold text-navy-900 mb-2 group-hover:text-brand-700 transition-colors">{{ service.title }}</h3>
            <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ service.short_description }}</p>
            <div class="mt-4 flex items-center text-sm font-semibold text-brand-600 opacity-0 group-hover:opacity-100 transform translate-y-1 group-hover:translate-y-0 transition-all duration-300">
                Learn More
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </div>
    </Link>
</template>
