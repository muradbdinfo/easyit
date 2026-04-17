<script setup>
import { ref, onMounted } from 'vue'

const showBanner = ref(false)

onMounted(() => {
    const consent = localStorage.getItem('cookie_consent')
    if (!consent) {
        showBanner.value = true
    }
})

function acceptAll() {
    localStorage.setItem('cookie_consent', 'accepted')
    showBanner.value = false

    // Enable Google consent mode (for AdSense & Analytics)
    if (window.gtag) {
        window.gtag('consent', 'update', {
            ad_user_data: 'granted',
            ad_personalization: 'granted',
            ad_storage: 'granted',
            analytics_storage: 'granted',
        })
    }
}

function rejectNonEssential() {
    localStorage.setItem('cookie_consent', 'rejected')
    showBanner.value = false

    // Deny non-essential consent (AdSense will show non-personalized ads)
    if (window.gtag) {
        window.gtag('consent', 'update', {
            ad_user_data: 'denied',
            ad_personalization: 'denied',
            ad_storage: 'denied',
            analytics_storage: 'denied',
        })
    }
}
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-full opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-full opacity-0"
    >
        <div v-if="showBanner" class="fixed bottom-0 inset-x-0 z-[60] p-4">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl border border-gray-100 p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <!-- Icon -->
                    <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>

                    <!-- Text -->
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-gray-900">Cookie Consent</h3>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500 leading-relaxed">
                            We use cookies to enhance your experience, serve personalized ads, and analyze our traffic.
                            By clicking "Accept All", you consent to our use of cookies.
                            <a href="/privacy-policy" class="text-brand-600 hover:text-brand-700 underline underline-offset-2">Privacy Policy</a>
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
                        <button
                            @click="rejectNonEssential"
                            class="flex-1 sm:flex-none px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                        >
                            Reject
                        </button>
                        <button
                            @click="acceptAll"
                            class="flex-1 sm:flex-none px-4 py-2 text-xs sm:text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition-colors shadow-sm"
                        >
                            Accept All
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
