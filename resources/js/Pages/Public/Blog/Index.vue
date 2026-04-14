<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PostCard from '@/Components/Public/PostCard.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
defineProps({ posts: Object, categories: Array, tags: Array, currentCategory: Object, filters: Object })
const search = ref('')
const doSearch = () => router.get('/blog', search.value ? { search: search.value } : {}, { preserveState: true })
</script>
<template>
    <AppLayout>
        <Head><title>{{ currentCategory ? `${currentCategory.name} - ` : '' }}Blog - Easy IT</title></Head>
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold">{{ currentCategory ? currentCategory.name : 'Our Blog' }}</h1>
                <p class="mt-4 text-lg text-gray-300">Tips, tutorials, and industry insights</p>
            </div>
        </section>
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                    <!-- Sidebar -->
                    <div class="mb-8 lg:mb-0">
                        <div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Search</h3>
                            <form @submit.prevent="doSearch" class="flex">
                                <input v-model="search" placeholder="Search posts..." class="flex-1 rounded-l-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                <button type="submit" class="bg-indigo-600 text-white px-3 rounded-r-md hover:bg-indigo-700">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Categories</h3>
                            <ul class="space-y-2">
                                <li><Link href="/blog" :class="[!currentCategory ? 'text-indigo-600 font-medium' : 'text-gray-600 hover:text-indigo-600', 'text-sm transition']">All Posts</Link></li>
                                <li v-for="cat in categories" :key="cat.id">
                                    <Link :href="`/blog/category/${cat.slug}`" :class="[currentCategory?.id === cat.id ? 'text-indigo-600 font-medium' : 'text-gray-600 hover:text-indigo-600', 'text-sm transition flex justify-between']">
                                        {{ cat.name }} <span class="text-gray-400">({{ cat.posts_count }})</span>
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Posts grid -->
                    <div class="lg:col-span-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
                        </div>
                        <div v-if="!posts.data?.length" class="text-center py-12 text-gray-500">No posts found.</div>
                        <Pagination :links="posts.links" />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
