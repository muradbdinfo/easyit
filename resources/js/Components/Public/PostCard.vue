<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ post: Object })
const formatDate = (d) => new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
</script>
<template>
    <Link :href="`/blog/${post.slug}`" class="group block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition">
        <div class="aspect-video bg-gray-100 overflow-hidden">
            <img v-if="post.media?.[0]?.original_url" :src="post.media[0].original_url" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-300 text-4xl">📝</div>
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
