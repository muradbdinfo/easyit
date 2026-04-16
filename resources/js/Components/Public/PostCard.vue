<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ post: Object })
const formatDate = (d) => new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })

const patterns = [
    'from-navy-900 via-brand-900 to-navy-950',
    'from-slate-800 via-indigo-900 to-slate-900',
    'from-gray-800 via-emerald-900 to-gray-900',
    'from-zinc-800 via-violet-900 to-zinc-900',
]
const getPattern = (id) => patterns[(id || 0) % patterns.length]
</script>

<template>
    <Link :href="`/blog/${post.slug}`" class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 hover:-translate-y-1">
        <div class="aspect-[16/10] overflow-hidden relative">
            <!-- Has image -->
            <img v-if="post.featured_image_url" :src="post.featured_image_url" :alt="post.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy" />

            <!-- No image - styled placeholder -->
            <div v-else :class="['w-full h-full bg-gradient-to-br flex flex-col items-center justify-center p-6 relative', getPattern(post.id)]">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20px 20px, rgba(255,255,255,.15) 1px, transparent 0); background-size: 30px 30px;"></div>
                <div class="relative">
                    <span v-if="post.category" class="text-[10px] font-bold uppercase tracking-[0.2em] text-brand-300 bg-brand-500/20 px-3 py-1 rounded-full mb-3 inline-block">{{ post.category.name }}</span>
                    <p class="text-white/90 font-bold text-sm leading-snug line-clamp-3 text-center">{{ post.title }}</p>
                </div>
            </div>

            <!-- Category badge on image -->
            <div v-if="post.featured_image_url && post.category" class="absolute top-3 left-3">
                <span class="bg-white/90 backdrop-blur-sm text-navy-900 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg">{{ post.category.name }}</span>
            </div>
        </div>

        <div class="p-5">
            <div class="flex items-center gap-3 text-xs text-gray-400 mb-2.5">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ formatDate(post.published_at) }}
                </span>
                <span v-if="post.views" class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ post.views }}
                </span>
            </div>
            <h3 class="font-bold text-navy-900 group-hover:text-brand-600 transition-colors line-clamp-2 leading-snug text-[15px]">{{ post.title }}</h3>
            <p v-if="post.excerpt" class="text-sm text-gray-500 mt-2 line-clamp-2 leading-relaxed">{{ post.excerpt }}</p>
            <div class="mt-4 flex items-center text-xs font-bold text-brand-600 group-hover:gap-2 transition-all">
                Read Article
                <svg class="w-3.5 h-3.5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </div>
    </Link>
</template>