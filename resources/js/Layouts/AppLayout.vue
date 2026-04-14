<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Navbar from '@/Components/Public/Navbar.vue'
import Footer from '@/Components/Public/Footer.vue'
import WhatsAppButton from '@/Components/Public/WhatsAppButton.vue'
import BackToTop from '@/Components/Public/BackToTop.vue'
import Analytics from '@/Components/Public/Analytics.vue'
import JsonLd from '@/Components/Public/JsonLd.vue'

const page = usePage()
const settings = computed(() => page.props.settings || {})
const flash = computed(() => page.props.flash)
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white">
        <!-- Analytics (GA4 + FB Pixel) -->
        <Analytics />

        <!-- Organization Schema -->
        <JsonLd type="Organization" />

        <Navbar />

        <!-- Flash messages -->
        <div v-if="flash?.success" class="bg-green-50 border-b border-green-200">
            <div class="max-w-7xl mx-auto px-4 py-3">
                <p class="text-sm text-green-800">{{ flash.success }}</p>
            </div>
        </div>
        <div v-if="flash?.error" class="bg-red-50 border-b border-red-200">
            <div class="max-w-7xl mx-auto px-4 py-3">
                <p class="text-sm text-red-800">{{ flash.error }}</p>
            </div>
        </div>

        <main class="flex-1">
            <slot />
        </main>

        <Footer />
        <WhatsAppButton :number="settings.whatsapp_number" />
        <BackToTop />
    </div>
</template>
