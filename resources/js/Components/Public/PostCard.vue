<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ post: Object })
const formatDate = (d) => new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
</script>
<template>
    <Link :href="`/blog/${post.slug}`" class="group block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition">
        <div class="aspect-video bg-gray-100 overflow-hidden">
            <img v-if="post.featured_image_url" :src="post.featured_image_url" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy" />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="p-4">
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <span v-if="post.category" class="text-indigo-600 font-medium">{{ post.category.name }}</span>
                <span v-if="post.category">·</span>
                <span>{{ formatDate(post.published_at) }}</span>
            </div>
            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition line-clamp-2">{{ post.title }}</h3>
            <p v-if="post.excerpt" class="text-sm text-gray-500 mt-1 line-clamp-2">{{ post.excerpt }}</p>
        </div>
    </Link>
</template>
