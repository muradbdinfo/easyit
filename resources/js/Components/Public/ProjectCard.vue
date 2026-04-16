<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ project: Object })
</script>
<template>
    <Link :href="`/projects/${project.slug}`" class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 hover:-translate-y-1">
        <div class="aspect-video bg-gray-100 overflow-hidden relative">
            <img v-if="project.featured_image_url || project.media?.[0]?.original_url" :src="project.featured_image_url || project.media[0].original_url" :alt="project.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
            <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 text-gray-300">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>
        <div class="p-5">
            <p v-if="project.service" class="text-xs text-brand-600 font-bold uppercase tracking-wider mb-1">{{ project.service.title }}</p>
            <h3 class="font-bold text-navy-900 group-hover:text-brand-600 transition-colors">{{ project.title }}</h3>
            <p class="text-sm text-gray-500 mt-1.5 line-clamp-2 leading-relaxed">{{ project.short_description }}</p>
        </div>
    </Link>
</template>
