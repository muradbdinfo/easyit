<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const settings = computed(() => page.props.settings || {})
const services = computed(() => page.props.navigation?.services || [])
const totalPageViews = computed(() => page.props.totalPageViews || 0)

const logoUrl = computed(() => {
    const logo = settings.value.company_logo
    if (!logo) return null
    if (logo.startsWith('http')) return logo
    return `/storage/${logo}`
})

const formattedViews = computed(() => {
    const count = Number(totalPageViews.value)
    if (count >= 1_000_000) return (count / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M'
    if (count >= 1_000) return (count / 1_000).toFixed(1).replace(/\.0$/, '') + 'K'
    return count.toLocaleString()
})
</script>

<template>
    <footer class="bg-navy-950 text-gray-400 relative overflow-hidden">
        <!-- Top Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-500/50 to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-brand-500/5 rounded-full blur-3xl -translate-y-1/2"></div>

        <!-- ═══ Main Footer Content ═══ -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12">

                <!-- Brand Column -->
                <div class="lg:col-span-4">
                    <Link href="/" class="inline-block mb-5">
                        <template v-if="logoUrl">
                            <img
                                :src="logoUrl"
                                :alt="settings.company_name || 'Easy IT'"
                                class="h-12 w-auto max-w-[200px] object-contain rounded-xl drop-shadow-[0_2px_8px_rgba(0,0,0,0.4)]"
                            />
                        </template>
                        <template v-else>
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                                    <span class="text-white font-extrabold text-sm">E</span>
                                </div>
                                <span class="text-lg font-bold text-white tracking-tight">Easy <span class="text-brand-400">IT</span></span>
                            </div>
                        </template>
                    </Link>
                    <p class="text-sm leading-relaxed text-gray-500 mb-6 max-w-xs">
                        {{ settings.company_description || 'Professional IT solutions for businesses across Bangladesh.' }}
                    </p>

                    <!-- Social Icons -->
                    <div class="flex gap-2.5">
                        <a v-if="settings.facebook_url" :href="settings.facebook_url" target="_blank" rel="noopener"
                           class="w-9 h-9 bg-white/[0.04] border border-white/[0.06] rounded-full flex items-center justify-center hover:bg-brand-500 hover:border-brand-500 hover:text-white text-gray-500 transition-all duration-300 group">
                            <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                        </a>
                        <a v-if="settings.youtube_url" :href="settings.youtube_url" target="_blank" rel="noopener"
                           class="w-9 h-9 bg-white/[0.04] border border-white/[0.06] rounded-full flex items-center justify-center hover:bg-red-500 hover:border-red-500 hover:text-white text-gray-500 transition-all duration-300 group">
                            <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.38.55A3.02 3.02 0 0 0 .5 6.19 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.81 3.02 3.02 0 0 0 2.12 2.14c1.88.55 9.38.55 9.38.55s7.5 0 9.38-.55a3.02 3.02 0 0 0 2.12-2.14A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.81zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                        </a>
                        <a v-if="settings.linkedin_url" :href="settings.linkedin_url" target="_blank" rel="noopener"
                           class="w-9 h-9 bg-white/[0.04] border border-white/[0.06] rounded-full flex items-center justify-center hover:bg-blue-600 hover:border-blue-600 hover:text-white text-gray-500 transition-all duration-300 group">
                            <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Services Column -->
                <div class="lg:col-span-3">
                    <h4 class="text-[11px] font-bold text-white uppercase tracking-[0.2em] mb-5">Services</h4>
                    <ul class="space-y-2.5">
                        <li v-for="s in services" :key="s.id">
                            <Link :href="`/services/${s.slug}`"
                                  class="text-[13px] text-gray-500 hover:text-brand-400 transition-colors duration-200 inline-flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full bg-gray-700 group-hover:bg-brand-400 transition-colors"></span>
                                {{ s.title }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Company Column -->
                <div class="lg:col-span-2">
                    <h4 class="text-[11px] font-bold text-white uppercase tracking-[0.2em] mb-5">Company</h4>
                    <ul class="space-y-2.5">
                        <li v-for="item in [
                            { label: 'About Us', href: '/about' },
                            { label: 'Portfolio', href: '/projects' },
                            { label: 'Blog', href: '/blog' },
                            { label: 'FAQ', href: '/faq' },
                            { label: 'Contact', href: '/contact' },
                            { label: 'Get a Quote', href: '/service-request' },
                        ]" :key="item.href">
                            <Link :href="item.href" class="text-[13px] text-gray-500 hover:text-brand-400 transition-colors duration-200">
                                {{ item.label }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="lg:col-span-3">
                    <h4 class="text-[11px] font-bold text-white uppercase tracking-[0.2em] mb-5">Get in Touch</h4>
                    <ul class="space-y-3.5">
                        <li v-if="settings.company_address" class="flex gap-3 text-[13px]">
                            <div class="w-8 h-8 bg-white/[0.04] border border-white/[0.06] rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="text-gray-500 leading-relaxed">{{ settings.company_address }}</span>
                        </li>
                        <li v-if="settings.company_phone" class="flex gap-3 text-[13px]">
                            <div class="w-8 h-8 bg-white/[0.04] border border-white/[0.06] rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <a :href="`tel:${settings.company_phone}`" class="text-gray-500 hover:text-brand-400 transition-colors duration-200">{{ settings.company_phone }}</a>
                        </li>
                        <li v-if="settings.company_email" class="flex gap-3 text-[13px]">
                            <div class="w-8 h-8 bg-white/[0.04] border border-white/[0.06] rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <a :href="`mailto:${settings.company_email}`" class="text-gray-500 hover:text-brand-400 transition-colors duration-200">{{ settings.company_email }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ═══ Bottom Bar ═══ -->
        <div class="relative border-t border-white/[0.06]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <!-- Left: Copyright -->
                    <p class="text-[11px] text-gray-600 order-2 lg:order-1 text-center lg:text-left">
                        &copy; {{ new Date().getFullYear() }} {{ settings.company_name || 'Easy IT' }}. All rights reserved.
                    </p>

                    <!-- Center: Legal Links + Visit Counter -->
                    <div class="flex flex-wrap items-center justify-center gap-x-1 gap-y-2 order-1 lg:order-2">
                        <Link href="/privacy-policy"
                              class="text-[11px] text-gray-600 hover:text-gray-400 transition-colors duration-200 px-2.5 py-0.5">
                            Privacy Policy
                        </Link>
                        <span class="text-gray-800 text-[10px]">&middot;</span>
                        <Link href="/terms"
                              class="text-[11px] text-gray-600 hover:text-gray-400 transition-colors duration-200 px-2.5 py-0.5">
                            Terms of Service
                        </Link>
                        <span class="text-gray-800 text-[10px]">&middot;</span>
                        <Link href="/disclaimer"
                              class="text-[11px] text-gray-600 hover:text-gray-400 transition-colors duration-200 px-2.5 py-0.5">
                            Disclaimer
                        </Link>

                        <!-- Visit Counter -->
                        <template v-if="totalPageViews > 0">
                            <span class="text-gray-800 text-[10px]">&middot;</span>
                            <span class="inline-flex items-center gap-1 text-[11px] text-gray-600 px-2.5 py-0.5">
                                <svg class="w-3 h-3 text-brand-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ formattedViews }} visits
                            </span>
                        </template>
                    </div>

                    <!-- Right: Made in Bangladesh -->
                    <p class="text-[11px] text-gray-700 order-3 text-center lg:text-right">
                        Crafted with
                        <svg class="w-3 h-3 inline-block text-red-500/60 -mt-px" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                        in Bangladesh
                    </p>
                </div>
            </div>
        </div>
    </footer>
</template>