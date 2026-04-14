<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ post: Object, readingTime: Number, relatedPosts: Array })
const formatDate = (d) => new Date(d).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
const shareUrl = (platform) => {
    const url = encodeURIComponent(window.location.href)
    const title = encodeURIComponent(document.title)
    const links = { facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`, whatsapp: `https://wa.me/?text=${title}%20${url}` }
    window.open(links[platform], '_blank', 'width=600,height=400')
}
const copyLink = () => { navigator.clipboard.writeText(window.location.href) }
</script>
<template>
    <AppLayout>
        <Head><title>{{ post.title }} - Easy IT Blog</title><meta name="description" :content="post.excerpt || post.title" /></Head>
        <article class="py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <Link href="/" class="hover:text-indigo-600">Home</Link><span>/</span>
                    <Link href="/blog" class="hover:text-indigo-600">Blog</Link><span>/</span>
                    <span class="text-gray-900 truncate">{{ post.title }}</span>
                </div>
                <!-- Meta -->
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 mb-4">
                    <Link v-if="post.category" :href="`/blog/category/${post.category.slug}`" class="text-indigo-600 font-medium hover:underline">{{ post.category.name }}</Link>
                    <span>{{ formatDate(post.published_at) }}</span>
                    <span>{{ readingTime }} min read</span>
                    <span>{{ post.views }} views</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">{{ post.title }}</h1>
                <div class="flex items-center gap-3 mt-4 text-sm text-gray-500">
                    <span>By {{ post.user?.name }}</span>
                </div>
                <!-- Featured image -->
                <div v-if="post.media?.[0]?.original_url" class="mt-8 rounded-xl overflow-hidden">
                    <img :src="post.media[0].original_url" :alt="post.title" class="w-full object-cover" />
                </div>
                <!-- Body -->
                <div class="mt-8 prose prose-lg max-w-none" v-html="post.body"></div>
                <!-- Tags -->
                <div v-if="post.tags?.length" class="mt-8 flex flex-wrap gap-2">
                    <span v-for="tag in post.tags" :key="tag.id" class="bg-gray-100 text-gray-600 rounded-full px-3 py-1 text-sm">#{{ tag.name }}</span>
                </div>
                <!-- Share -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">Share this article</p>
                    <div class="flex gap-2">
                        <button @click="shareUrl('facebook')" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition text-sm">f</button>
                        <button @click="shareUrl('whatsapp')" class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center hover:bg-green-600 transition text-sm">W</button>
                        <button @click="copyLink" class="w-10 h-10 bg-gray-200 text-gray-700 rounded-lg flex items-center justify-center hover:bg-gray-300 transition text-sm">🔗</button>
                    </div>
                </div>
                <!-- Related -->
                <div v-if="relatedPosts?.length" class="mt-12 pt-8 border-t border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Related Posts</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <Link v-for="rp in relatedPosts" :key="rp.id" :href="`/blog/${rp.slug}`" class="block p-4 rounded-lg border border-gray-100 hover:shadow-md transition">
                            <p class="text-xs text-gray-500">{{ formatDate(rp.published_at) }}</p>
                            <p class="font-medium text-gray-900 mt-1 line-clamp-2">{{ rp.title }}</p>
                        </Link>
                    </div>
                </div>
            </div>
        </article>
    </AppLayout>
</template>
