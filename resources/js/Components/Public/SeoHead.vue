<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    title: String,
    description: String,
    image: { type: String, default: null },
    type: { type: String, default: 'website' },
    url: { type: String, default: null },
    article: { type: Object, default: null },
})

const page = usePage()
const settings = computed(() => page.props.settings || {})
const currentUrl = computed(() => props.url || (typeof window !== 'undefined' ? window.location.href : ''))
const siteName = computed(() => settings.value.company_name || 'Easy IT')
const pageTitle = computed(() => props.title ? `${props.title} - ${siteName.value}` : settings.value.meta_title || siteName.value)
const pageDesc = computed(() => props.description || settings.value.meta_description || '')
const pageImage = computed(() => props.image || '/images/og-default.jpg')
</script>

<template>
    <Head>
        <title>{{ pageTitle }}</title>
        <meta name="description" :content="pageDesc" />
        <meta name="robots" content="index, follow" />
        <link rel="canonical" :href="currentUrl" />

        <!-- Open Graph -->
        <meta property="og:type" :content="type" />
        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="pageDesc" />
        <meta property="og:image" :content="pageImage" />
        <meta property="og:url" :content="currentUrl" />
        <meta property="og:site_name" :content="siteName" />
        <meta property="og:locale" content="en_US" />

        <!-- Article specific -->
        <meta v-if="article?.published_at" property="article:published_time" :content="article.published_at" />
        <meta v-if="article?.author" property="article:author" :content="article.author" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="pageTitle" />
        <meta name="twitter:description" :content="pageDesc" />
        <meta name="twitter:image" :content="pageImage" />
    </Head>
</template>
