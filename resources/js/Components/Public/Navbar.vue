<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const mobileOpen = ref(false)
const servicesOpen = ref(false)
const scrolled = ref(false)
const settings = computed(() => page.props.settings || {})
const services = computed(() => page.props.navigation?.services || [])
const user = computed(() => page.props.auth?.user)
const isActive = (path) => page.url === path || (path !== '/' && page.url.startsWith(path))

const logoUrl = computed(() => {
    const logo = settings.value.company_logo
    if (!logo) return null
    if (logo.startsWith('http')) return logo
    return `/storage/${logo}`
})

const onScroll = () => { scrolled.value = window.scrollY > 20 }
onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<template>
    <header :class="[scrolled ? 'bg-white/95 backdrop-blur-xl shadow-sm border-b border-gray-100' : 'bg-transparent', 'fixed top-0 left-0 right-0 z-50 transition-all duration-300']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-20">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2.5 group shrink-0">
                    <template v-if="logoUrl">
                        <div class="bg-white rounded-2xl shadow-md p-1.5 flex items-center justify-center">
                            <img
                                :src="logoUrl"
                                :alt="settings.company_name || 'Easy IT'"
                                class="h-10 w-10 lg:h-12 lg:w-12 object-contain rounded-xl"
                            />
                        </div>
                    </template>
                    <template v-else>
                        <div class="w-9 h-9 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                            <span class="text-white font-extrabold text-sm">E</span>
                        </div>
                        <div>
                            <span :class="[scrolled ? 'text-navy-900' : 'text-white', 'text-lg font-bold tracking-tight transition-colors']">Easy</span>
                            <span class="text-brand-500 text-lg font-bold tracking-tight">IT</span>
                        </div>
                    </template>
                </Link>

                <!-- Desktop nav -->
                <nav class="hidden lg:flex items-center gap-1">
                    <Link v-for="item in [
                        { label: 'Home', href: '/' },
                        { label: 'About', href: '/about' },
                    ]" :key="item.href" :href="item.href"
                        :class="[isActive(item.href) ? 'text-brand-500' : scrolled ? 'text-gray-600 hover:text-navy-900' : 'text-gray-200 hover:text-white', 'px-3 py-2 text-[13px] font-semibold tracking-wide transition-colors relative']">
                        {{ item.label }}
                        <span v-if="isActive(item.href)" class="absolute bottom-0 left-3 right-3 h-0.5 bg-brand-500 rounded-full"></span>
                    </Link>

                    <!-- Services dropdown -->
                    <div class="relative" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                        <Link href="/services" :class="[isActive('/services') ? 'text-brand-500' : scrolled ? 'text-gray-600 hover:text-navy-900' : 'text-gray-200 hover:text-white', 'px-3 py-2 text-[13px] font-semibold tracking-wide transition-colors inline-flex items-center gap-1 relative']">
                            Services
                            <svg :class="[servicesOpen ? 'rotate-180' : '', 'w-3.5 h-3.5 transition-transform']" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </Link>
                        <transition enter-active-class="transition duration-200" enter-from-class="opacity-0 -translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
                            <div v-show="servicesOpen" class="absolute left-0 mt-1 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                                <Link v-for="service in services" :key="service.id" :href="`/services/${service.slug}`"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-brand-50 hover:text-brand-700 transition group">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-brand-500 transition"></span>
                                    {{ service.title }}
                                </Link>
                            </div>
                        </transition>
                    </div>

                    <Link v-for="item in [
                        { label: 'Portfolio', href: '/projects' },
                        { label: 'Blog', href: '/blog' },
                        { label: 'FAQ', href: '/faq' },
                        { label: 'Contact', href: '/contact' },
                    ]" :key="item.href" :href="item.href"
                        :class="[isActive(item.href) ? 'text-brand-500' : scrolled ? 'text-gray-600 hover:text-navy-900' : 'text-gray-200 hover:text-white', 'px-3 py-2 text-[13px] font-semibold tracking-wide transition-colors relative']">
                        {{ item.label }}
                        <span v-if="isActive(item.href)" class="absolute bottom-0 left-3 right-3 h-0.5 bg-brand-500 rounded-full"></span>
                    </Link>
                </nav>

                <!-- Right side: Auth + CTA -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <template v-if="user">
                        <Link :href="user.is_admin ? '/admin/dashboard' : '/client/dashboard'"
                            :class="[scrolled ? 'text-gray-600 hover:text-brand-600' : 'text-gray-200 hover:text-white', 'hidden sm:inline-flex items-center gap-2 px-3 py-2 text-[13px] font-semibold transition-colors']">
                            <div class="w-6 h-6 rounded-full bg-brand-100 flex items-center justify-center">
                                <span class="text-[10px] font-bold text-brand-700">{{ user.name?.charAt(0) }}</span>
                            </div>
                            {{ user.is_admin ? 'Admin Panel' : 'My Portal' }}
                        </Link>
                    </template>
                    <template v-else>
                        <Link href="/login" :class="[scrolled ? 'text-gray-600 hover:text-brand-600' : 'text-gray-200 hover:text-white', 'hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-[13px] font-semibold transition-colors']">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Client Login
                        </Link>
                    </template>

                    <Link href="/service-request" class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:from-brand-600 hover:to-brand-700 transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-0.5">
                        Get a Quote
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </Link>

                    <button @click="mobileOpen = !mobileOpen" :class="[scrolled ? 'text-gray-700' : 'text-white', 'lg:hidden p-2 rounded-lg hover:bg-white/10 transition']">
                        <svg v-if="!mobileOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg v-else class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <transition enter-active-class="transition duration-200" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="mobileOpen" class="lg:hidden bg-white border-t border-gray-100 shadow-xl">
                <div class="px-4 py-4 space-y-1">
                    <Link v-for="item in [{label:'Home',href:'/'},{label:'About',href:'/about'},{label:'Services',href:'/services'},{label:'Portfolio',href:'/projects'},{label:'Blog',href:'/blog'},{label:'FAQ',href:'/faq'},{label:'Contact',href:'/contact'}]"
                        :key="item.href" :href="item.href" @click="mobileOpen = false"
                        :class="[isActive(item.href) ? 'text-brand-600 bg-brand-50' : 'text-gray-700 hover:bg-gray-50', 'block px-4 py-2.5 rounded-xl text-sm font-semibold transition']">
                        {{ item.label }}
                    </Link>

                    <div class="pt-2 border-t border-gray-100 mt-2">
                        <template v-if="user">
                            <Link :href="user.is_admin ? '/admin/dashboard' : '/client/dashboard'" @click="mobileOpen = false"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-brand-600 bg-brand-50">
                                <div class="w-6 h-6 rounded-full bg-brand-200 flex items-center justify-center">
                                    <span class="text-[10px] font-bold text-brand-700">{{ user.name?.charAt(0) }}</span>
                                </div>
                                {{ user.is_admin ? 'Admin Panel' : 'My Portal' }}
                            </Link>
                        </template>
                        <template v-else>
                            <Link href="/login" @click="mobileOpen = false" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                Client Login
                            </Link>
                            <Link href="/register" @click="mobileOpen = false" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                Create Account
                            </Link>
                        </template>
                    </div>

                    <Link href="/service-request" @click="mobileOpen = false"
                        class="block mt-2 text-center rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-3 text-sm font-semibold text-white">
                        Get a Quote →
                    </Link>
                </div>
            </div>
        </transition>
    </header>
</template>