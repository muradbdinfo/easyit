<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import CommentSection from '@/Components/Public/CommentSection.vue'
import PostCard from '@/Components/Public/PostCard.vue'

const props = defineProps({
    post: Object,
    readingTime: Number,
    relatedPosts: Array,
    comments: Object,
    commentsCount: Number,
    commentSettings: Object,
})

const page = usePage()
const settings = computed(() => page.props.settings)

const formatDate = (d) => new Date(d).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })

const shareUrl = computed(() => typeof window !== 'undefined' ? window.location.href : '')
const shareFb = computed(() => `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl.value)}`)
const shareWa = computed(() => `https://wa.me/?text=${encodeURIComponent(props.post.title + ' ' + shareUrl.value)}`)

const copyLink = () => {
    navigator.clipboard?.writeText(shareUrl.value)
}
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ post.title }} - {{ settings?.company_name || 'Easy IT' }}</title>
            <meta name="description" :content="post.excerpt || post.title" />
            <meta property="og:title" :content="post.title" />
            <meta property="og:description" :content="post.excerpt || post.title" />
            <meta property="og:image" :content="post.featured_image_url" />
            <meta property="og:type" content="article" />
        </Head>

        <!-- Hero -->
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-16 sm:py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex items-center justify-center gap-3 text-sm text-gray-300 mb-4">
                    <Link v-if="post.category" :href="`/blog/category/${post.category.slug}`" class="bg-white/10 px-3 py-1 rounded-full hover:bg-white/20 transition">
                        {{ post.category.name }}
                    </Link>
                    <span>{{ formatDate(post.published_at) }}</span>
                    <span>{{ readingTime }} min read</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight">{{ post.title }}</h1>
                <div class="mt-4 flex items-center justify-center gap-3 text-sm text-gray-300">
                    <span>By {{ post.user?.name }}</span>
                    <span>&middot;</span>
                    <span>{{ post.views?.toLocaleString() }} views</span>
                    <span v-if="commentsCount > 0">&middot;</span>
                    <a v-if="commentsCount > 0" href="#comments" class="hover:text-white transition">{{ commentsCount }} comment{{ commentsCount !== 1 ? 's' : '' }}</a>
                </div>
            </div>
        </section>

        <!-- Content -->
        <article class="py-12 sm:py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Featured Image -->
                <img v-if="post.featured_image_url" :src="post.featured_image_url" :alt="post.title"
                    class="w-full rounded-2xl mb-10 shadow-lg object-cover max-h-[500px]" />

                <!-- Body -->
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-a:text-brand-600 prose-img:rounded-xl" v-html="post.body"></div>

                <!-- Tags -->
                <div v-if="post.tags?.length" class="mt-10 flex flex-wrap gap-2">
                    <span v-for="tag in post.tags" :key="tag.id" class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-medium">
                        #{{ tag.name }}
                    </span>
                </div>

                <!-- Share -->
                <div class="mt-10 flex items-center gap-3 pt-6 border-t border-gray-100">
                    <span class="text-sm font-medium text-gray-500">Share:</span>
                    <a :href="shareFb" target="_blank" class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                    </a>
                    <a :href="shareWa" target="_blank" class="w-9 h-9 rounded-full bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-200 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <button @click="copyLink" class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-200 transition" title="Copy link">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </button>
                </div>

                <!-- ═══ COMMENTS SECTION ═══ -->
                <CommentSection
                    :post-slug="post.slug"
                    :comments="comments"
                    :comments-count="commentsCount"
                    :settings="commentSettings"
                />
            </div>
        </article>

        <!-- Related Posts -->
        <section v-if="relatedPosts?.length" class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <PostCard v-for="rp in relatedPosts" :key="rp.id" :post="rp" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
