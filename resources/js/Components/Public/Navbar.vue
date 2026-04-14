<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const mobileOpen = ref(false)
const servicesOpen = ref(false)
const settings = computed(() => page.props.settings || {})
const services = computed(() => page.props.navigation?.services || [])
const isActive = (path) => page.url.startsWith(path)
</script>

<template>
    <header class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">E</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Easy <span class="text-indigo-600">IT</span></span>
                </Link>

                <!-- Desktop nav -->
                <nav class="hidden lg:flex items-center gap-1">
                    <Link href="/" :class="[isActive('/') && page.url === '/' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'px-3 py-2 rounded-md text-sm font-medium transition']">Home</Link>
                    <Link href="/about" :class="[isActive('/about') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'px-3 py-2 rounded-md text-sm font-medium transition']">About</Link>

                    <!-- Services dropdown -->
                    <div class="relative" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                        <Link href="/services" :class="[isActive('/services') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'px-3 py-2 rounded-md text-sm font-medium transition inline-flex items-center gap-1']">
                            Services
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </Link>
                        <div v-show="servicesOpen" class="absolute left-0 mt-0 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-50">
                            <Link v-for="service in services" :key="service.id" :href="`/services/${service.slug}`"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                {{ service.title }}
                            </Link>
                        </div>
                    </div>

                    <Link href="/projects" :class="[isActive('/projects') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'px-3 py-2 rounded-md text-sm font-medium transition']">Portfolio</Link>
                    <Link href="/blog" :class="[isActive('/blog') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'px-3 py-2 rounded-md text-sm font-medium transition']">Blog</Link>
                    <Link href="/faq" :class="[isActive('/faq') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'px-3 py-2 rounded-md text-sm font-medium transition']">FAQ</Link>
                    <Link href="/contact" :class="[isActive('/contact') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'px-3 py-2 rounded-md text-sm font-medium transition']">Contact</Link>
                </nav>

                <!-- CTA + Mobile toggle -->
                <div class="flex items-center gap-3">
                    <Link href="/service-request" class="hidden sm:inline-flex rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">Get a Quote</Link>
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-gray-700">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile nav -->
        <div v-show="mobileOpen" class="lg:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-3 space-y-1">
                <Link href="/" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" @click="mobileOpen = false">Home</Link>
                <Link href="/about" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" @click="mobileOpen = false">About</Link>
                <Link href="/services" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" @click="mobileOpen = false">Services</Link>
                <Link v-for="service in services" :key="service.id" :href="`/services/${service.slug}`" class="block pl-6 py-1 text-sm text-gray-500 hover:text-indigo-600" @click="mobileOpen = false">{{ service.title }}</Link>
                <Link href="/projects" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" @click="mobileOpen = false">Portfolio</Link>
                <Link href="/blog" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" @click="mobileOpen = false">Blog</Link>
                <Link href="/faq" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" @click="mobileOpen = false">FAQ</Link>
                <Link href="/contact" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" @click="mobileOpen = false">Contact</Link>
                <Link href="/service-request" class="block px-3 py-2 mt-2 rounded-md bg-indigo-600 text-white text-sm font-medium text-center" @click="mobileOpen = false">Get a Quote</Link>
            </div>
        </div>
    </header>
</template>
