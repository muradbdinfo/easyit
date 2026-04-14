<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ project: Object })
</script>
<template>
    <Link :href="`/projects/${project.slug}`" class="group block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition">
        <div class="aspect-video bg-gray-100 overflow-hidden">
            <img v-if="project.media?.[0]?.original_url" :src="project.media[0].original_url" :alt="project.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="p-4">
            <p v-if="project.service" class="text-xs text-indigo-600 font-medium mb-1">{{ project.service.title }}</p>
            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">{{ project.title }}</h3>
            <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ project.short_description }}</p>
        </div>
    </Link>
</template>
