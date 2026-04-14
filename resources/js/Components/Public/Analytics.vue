<script setup>
import { computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

const settings = computed(() => usePage().props.settings || {})
const gaId = computed(() => settings.value.google_analytics_id)
const fbPixelId = computed(() => settings.value.facebook_pixel_id)

onMounted(() => {
    // Google Analytics 4
    if (gaId.value) {
        const script = document.createElement('script')
        script.async = true
        script.src = `https://www.googletagmanager.com/gtag/js?id=${gaId.value}`
        document.head.appendChild(script)

        window.dataLayer = window.dataLayer || []
        function gtag() { window.dataLayer.push(arguments) }
        gtag('js', new Date())
        gtag('config', gaId.value)
        window.gtag = gtag
    }

    // Facebook Pixel
    if (fbPixelId.value) {
        !function(f,b,e,v,n,t,s){
            if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;
            s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)
        }(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
        window.fbq('init', fbPixelId.value);
        window.fbq('track', 'PageView');
    }
})
</script>

<template>
    <!-- Facebook Pixel noscript fallback -->
    <noscript v-if="fbPixelId">
        <img height="1" width="1" style="display:none"
            :src="`https://www.facebook.com/tr?id=${fbPixelId}&ev=PageView&noscript=1`" />
    </noscript>
</template>
