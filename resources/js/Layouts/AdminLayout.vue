<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const page = usePage()
const sidebarOpen = ref(false)
const user = computed(() => page.props.auth?.user)

const navigation = [
    { name: 'Dashboard', href: '/admin/dashboard', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>` },
    { name: 'Services', href: '/admin/services', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>` },
    { name: 'Projects', href: '/admin/projects', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>` },
    { name: 'Packages', href: '/admin/packages', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>` },
    { name: 'Team', href: '/admin/team-members', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>` },
    { name: 'Testimonials', href: '/admin/testimonials', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>` },
    { name: 'Blog Posts', href: '/admin/posts', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>` },
    { name: 'Categories', href: '/admin/categories', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>` },
    { name: 'FAQs', href: '/admin/faqs', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>` },
    { name: 'Contacts', href: '/admin/contacts', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>` },
    { name: 'Requests', href: '/admin/service-requests', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>` },
    { name: 'Pages', href: '/admin/pages', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>` },
    { name: 'Settings', href: '/admin/settings', icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>` },
]

const isActive = (href) => page.url.startsWith(href)
const logout = () => router.post('/logout')
const flash = computed(() => page.props.flash)
const newContacts = computed(() => page.props.stats?.new_contacts || 0)
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Mobile sidebar overlay -->
        <transition enter-active-class="transition-opacity duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" @click="sidebarOpen = false">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            </div>
        </transition>

        <!-- Mobile sidebar -->
        <transition enter-active-class="transition-transform duration-300 ease-out" enter-from-class="-translate-x-full" enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-300 ease-in" leave-from-class="translate-x-0" leave-to-class="-translate-x-full">
            <div v-if="sidebarOpen" class="fixed inset-y-0 left-0 z-50 w-72 lg:hidden">
                <div class="flex h-full flex-col bg-white shadow-2xl">
                    <div class="flex h-14 items-center justify-between px-4 border-b border-gray-100">
                        <Link href="/admin/dashboard" class="flex items-center gap-2" @click="sidebarOpen = false">
                            <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xs">E</span>
                            </div>
                            <span class="text-base font-bold text-gray-900">Easy <span class="text-indigo-600">IT</span></span>
                        </Link>
                        <button @click="sidebarOpen = false" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
                        <Link v-for="item in navigation" :key="item.name" :href="item.href"
                            :class="[isActive(item.href) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition-all']"
                            @click="sidebarOpen = false">
                            <svg class="w-[18px] h-[18px] shrink-0" :class="isActive(item.href) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-html="item.icon"></svg>
                            {{ item.name }}
                        </Link>
                    </nav>
                    <div class="border-t border-gray-100 p-3">
                        <Link href="/" target="_blank" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-500 hover:text-indigo-600 rounded-lg hover:bg-gray-50 transition" @click="sidebarOpen = false">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Website
                        </Link>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Desktop sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-60 lg:flex-col">
            <div class="flex min-h-0 flex-1 flex-col bg-white border-r border-gray-100">
                <div class="flex h-14 items-center px-4 border-b border-gray-100">
                    <Link href="/admin/dashboard" class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xs">E</span>
                        </div>
                        <span class="text-base font-bold text-gray-900">Easy <span class="text-indigo-600">IT</span></span>
                    </Link>
                </div>
                <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
                    <Link v-for="item in navigation" :key="item.name" :href="item.href"
                        :class="[isActive(item.href) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center gap-2.5 rounded-xl px-3 py-2 text-[13px] font-medium transition-all']">
                        <svg class="w-[18px] h-[18px] shrink-0" :class="isActive(item.href) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-html="item.icon"></svg>
                        {{ item.name }}
                    </Link>
                </nav>
                <div class="border-t border-gray-100 p-3">
                    <Link href="/" target="_blank" class="flex items-center gap-2 px-3 py-2 text-[13px] text-gray-500 hover:text-indigo-600 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        View Website
                    </Link>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="lg:pl-60">
            <!-- Top bar -->
            <div class="sticky top-0 z-10 flex h-14 items-center gap-3 border-b border-gray-100 bg-white/95 backdrop-blur-sm px-4 sm:px-6">
                <button @click="sidebarOpen = true" class="lg:hidden p-1.5 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="flex-1"></div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-50 text-xs text-gray-500">
                        <div class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-[10px] font-bold text-indigo-600">{{ user?.name?.charAt(0) }}</span>
                        </div>
                        {{ user?.name }}
                    </div>
                    <button @click="logout" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Logout">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </div>
            </div>

            <!-- Flash messages -->
            <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
                <div v-if="flash?.success" class="mx-4 sm:mx-6 mt-4">
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm text-emerald-800">{{ flash.success }}</p>
                    </div>
                </div>
            </transition>
            <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
                <div v-if="flash?.error" class="mx-4 sm:mx-6 mt-4">
                    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-red-800">{{ flash.error }}</p>
                    </div>
                </div>
            </transition>

            <!-- Page content -->
            <main class="p-4 sm:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
