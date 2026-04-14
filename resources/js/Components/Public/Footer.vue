<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const settings = computed(() => page.props.settings || {})
const services = computed(() => page.props.navigation?.services || [])
</script>

<template>
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company -->
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Easy <span class="text-indigo-400">IT</span></h3>
                    <p class="text-sm text-gray-400 mb-4">{{ settings.company_description || 'Your trusted IT partner for network solutions, web hosting, and development.' }}</p>
                    <div class="flex gap-3">
                        <a v-if="settings.facebook_url" :href="settings.facebook_url" target="_blank" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-indigo-600 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                        </a>
                        <a v-if="settings.youtube_url" :href="settings.youtube_url" target="_blank" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-600 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.38.55A3.02 3.02 0 0 0 .5 6.19 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.81 3.02 3.02 0 0 0 2.12 2.14c1.88.55 9.38.55 9.38.55s7.5 0 9.38-.55a3.02 3.02 0 0 0 2.12-2.14A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.81zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li v-for="service in services" :key="service.id">
                            <Link :href="`/services/${service.slug}`" class="text-sm text-gray-400 hover:text-white transition">{{ service.title }}</Link>
                        </li>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><Link href="/about" class="text-sm text-gray-400 hover:text-white transition">About Us</Link></li>
                        <li><Link href="/projects" class="text-sm text-gray-400 hover:text-white transition">Portfolio</Link></li>
                        <li><Link href="/blog" class="text-sm text-gray-400 hover:text-white transition">Blog</Link></li>
                        <li><Link href="/faq" class="text-sm text-gray-400 hover:text-white transition">FAQ</Link></li>
                        <li><Link href="/contact" class="text-sm text-gray-400 hover:text-white transition">Contact</Link></li>
                        <li><Link href="/service-request" class="text-sm text-gray-400 hover:text-white transition">Request a Quote</Link></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Contact</h4>
                    <ul class="space-y-3">
                        <li v-if="settings.company_address" class="flex gap-2 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ settings.company_address }}
                        </li>
                        <li v-if="settings.company_phone" class="flex gap-2 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a :href="`tel:${settings.company_phone}`" class="hover:text-white">{{ settings.company_phone }}</a>
                        </li>
                        <li v-if="settings.company_email" class="flex gap-2 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a :href="`mailto:${settings.company_email}`" class="hover:text-white">{{ settings.company_email }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-sm text-gray-500">
                &copy; {{ new Date().getFullYear() }} {{ settings.company_name || 'Easy IT' }}. All rights reserved.
            </div>
        </div>
    </footer>
</template>
