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
const copyLink = () => {
    navigator.clipboard.writeText(window.location.href)
    alert('Link copied!')
}
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ post.title }} - Easy IT Blog</title>
            <meta name="description" :content="post.excerpt || post.title" />
            <meta property="og:title" :content="post.title" />
            <meta property="og:description" :content="post.excerpt || post.title" />
            <meta property="og:image" :content="post.featured_image_url || ''" />
            <meta property="og:type" content="article" />
        </Head>

        <article>
            <!-- Hero with featured image -->
            <div v-if="post.featured_image_url" class="relative bg-gray-900">
                <img :src="post.featured_image_url" :alt="post.title" class="w-full h-64 sm:h-80 lg:h-96 object-cover opacity-40" />
                <div class="absolute inset-0 flex items-end">
                    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 sm:pb-12 w-full">
                        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-300 mb-3">
                            <Link href="/blog" class="hover:text-white">Blog</Link>
                            <span>/</span>
                            <Link v-if="post.category" :href="`/blog/category/${post.category.slug}`" class="text-indigo-300 hover:text-white">{{ post.category.name }}</Link>
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight">{{ post.title }}</h1>
                        <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-gray-300">
                            <span>By {{ post.user?.name }}</span>
                            <span>·</span>
                            <span>{{ formatDate(post.published_at) }}</span>
                            <span>·</span>
                            <span>{{ readingTime }} min read</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Without featured image -->
            <div v-else class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-12 sm:py-16">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap items-center gap-2 text-xs text-gray-300 mb-3">
                        <Link href="/" class="hover:text-white">Home</Link><span>/</span>
                        <Link href="/blog" class="hover:text-white">Blog</Link><span>/</span>
                        <span class="text-white truncate">{{ post.title }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight">{{ post.title }}</h1>
                    <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-gray-300">
                        <span>By {{ post.user?.name }}</span>
                        <span>·</span>
                        <span>{{ formatDate(post.published_at) }}</span>
                        <span>·</span>
                        <span>{{ readingTime }} min read</span>
                        <span>·</span>
                        <span>{{ post.views }} views</span>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="py-8 sm:py-12">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Rendered HTML body with Tailwind prose -->
                    <div class="prose prose-lg prose-gray max-w-none
                        prose-headings:font-bold prose-headings:text-gray-900
                        prose-h2:text-2xl prose-h2:mt-8 prose-h2:mb-4 prose-h2:border-b prose-h2:border-gray-200 prose-h2:pb-2
                        prose-h3:text-xl prose-h3:mt-6 prose-h3:mb-3
                        prose-p:text-gray-700 prose-p:leading-relaxed
                        prose-a:text-indigo-600 prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-gray-900
                        prose-code:bg-gray-100 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-sm prose-code:before:content-none prose-code:after:content-none
                        prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:rounded-xl
                        prose-img:rounded-xl prose-img:shadow-md
                        prose-blockquote:border-l-4 prose-blockquote:border-indigo-500 prose-blockquote:bg-indigo-50 prose-blockquote:rounded-r-lg prose-blockquote:py-1 prose-blockquote:not-italic
                        prose-li:text-gray-700
                        prose-hr:border-gray-200"
                        v-html="post.body">
                    </div>

                    <!-- Tags -->
                    <div v-if="post.tags?.length" class="mt-8 flex flex-wrap gap-2">
                        <span v-for="tag in post.tags" :key="tag.id" class="bg-gray-100 text-gray-600 rounded-full px-3 py-1 text-sm hover:bg-indigo-50 hover:text-indigo-600 transition">
                            #{{ tag.name }}
                        </span>
                    </div>

                    <!-- Share -->
                    <div class="mt-10 pt-6 border-t border-gray-200">
                        <p class="text-sm font-semibold text-gray-900 mb-3">Share this article</p>
                        <div class="flex gap-2">
                            <button @click="shareUrl('facebook')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                                Facebook
                            </button>
                            <button @click="shareUrl('whatsapp')" class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                WhatsApp
                            </button>
                            <button @click="copyLink" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition">
                                🔗 Copy Link
                            </button>
                        </div>
                    </div>

                    <!-- Related -->
                    <div v-if="relatedPosts?.length" class="mt-12 pt-8 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Related Posts</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <Link v-for="rp in relatedPosts" :key="rp.id" :href="`/blog/${rp.slug}`"
                                class="group block rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition">
                                <div v-if="rp.featured_image_url" class="aspect-video bg-gray-100">
                                    <img :src="rp.featured_image_url" :alt="rp.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy" />
                                </div>
                                <div class="p-4">
                                    <p class="text-xs text-gray-500">{{ formatDate(rp.published_at) }}</p>
                                    <p class="font-medium text-gray-900 mt-1 line-clamp-2 group-hover:text-indigo-600 transition">{{ rp.title }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </AppLayout>
</template>
