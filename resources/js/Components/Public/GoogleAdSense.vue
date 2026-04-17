<script setup>
import { computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

const settings = computed(() => usePage().props.settings || {})
const adsenseId = computed(() => settings.value.google_adsense_id)

onMounted(() => {
    if (!adsenseId.value) return

    // Set default consent to denied (will be updated by CookieConsent)
    window.dataLayer = window.dataLayer || []
    function gtag() { window.dataLayer.push(arguments) }
    window.gtag = window.gtag || gtag

    // Default consent mode (denied until user accepts)
    gtag('consent', 'default', {
        ad_user_data: 'denied',
        ad_personalization: 'denied',
        ad_storage: 'denied',
        analytics_storage: 'denied',
    })

    // Check if user already consented
    const consent = localStorage.getItem('cookie_consent')
    if (consent === 'accepted') {
        gtag('consent', 'update', {
            ad_user_data: 'granted',
            ad_personalization: 'granted',
            ad_storage: 'granted',
            analytics_storage: 'granted',
        })
    }

    // Load AdSense script
    const script = document.createElement('script')
    script.async = true
    script.crossOrigin = 'anonymous'
    script.src = `https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=${adsenseId.value}`
    document.head.appendChild(script)
})
</script>

<template>
    <!-- AdSense is loaded via script injection, no visible template needed -->
</template>
