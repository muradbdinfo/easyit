<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    type: { type: String, default: 'Organization' },
    data: { type: Object, default: () => ({}) },
})

const page = usePage()
const settings = computed(() => page.props.settings || {})

const organizationSchema = computed(() => ({
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": settings.value.company_name || "Easy IT",
    "url": typeof window !== 'undefined' ? window.location.origin : '',
    "logo": `${typeof window !== 'undefined' ? window.location.origin : ''}/images/logo.png`,
    "description": settings.value.meta_description || '',
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Sandwip",
        "addressRegion": "Chittagong",
        "addressCountry": "BD",
        "streetAddress": settings.value.company_address || ''
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": settings.value.company_phone || '',
        "contactType": "customer service",
        "email": settings.value.company_email || ''
    },
    "sameAs": [
        settings.value.facebook_url,
        settings.value.youtube_url,
        settings.value.linkedin_url
    ].filter(Boolean)
}))

const schema = computed(() => {
    if (props.type === 'Organization') return organizationSchema.value
    return { "@context": "https://schema.org", "@type": props.type, ...props.data }
})
</script>

<template>
    <component :is="'script'" type="application/ld+json" v-html="JSON.stringify(schema)" />
</template>