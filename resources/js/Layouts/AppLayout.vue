<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Navbar from '@/Components/Public/Navbar.vue'
import Footer from '@/Components/Public/Footer.vue'
import WhatsAppButton from '@/Components/Public/WhatsAppButton.vue'
import BackToTop from '@/Components/Public/BackToTop.vue'

const page = usePage()
const settings = computed(() => page.props.settings || {})
const flash = computed(() => page.props.flash)
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white">
        <Navbar />

        <!-- Flash -->
        <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
            <div v-if="flash?.success" class="fixed top-20 left-1/2 -translate-x-1/2 z-50 w-full max-w-lg mx-auto px-4">
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-3 shadow-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-sm font-medium text-emerald-800">{{ flash.success }}</p>
                </div>
            </div>
        </transition>

        <main class="flex-1">
            <slot />
        </main>

        <Footer />
        <WhatsAppButton :number="settings.whatsapp_number" />
        <BackToTop />
    </div>
</template>
