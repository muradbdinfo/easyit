<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
const page = usePage()
const sidebarOpen = ref(false)
const user = computed(() => page.props.auth?.user)
const flash = computed(() => page.props.flash)

const nav = [
    { name:'Dashboard', href:'/client/dashboard', icon:'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name:'My Services', href:'/client/services', icon:'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2' },
    { name:'Support Tickets', href:'/client/tickets', icon:'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z' },
    { name:'Invoices', href:'/client/invoices', icon:'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { name:'Profile', href:'/client/profile', icon:'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
]
const isActive = (href) => page.url.startsWith(href)
const logout = () => router.post('/logout')
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Mobile overlay -->
        <transition enter-active-class="transition-opacity duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-opacity duration-300" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" @click="sidebarOpen = false"><div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div></div>
        </transition>

        <!-- Mobile sidebar -->
        <transition enter-active-class="transition-transform duration-300" enter-from-class="-translate-x-full" enter-to-class="translate-x-0" leave-active-class="transition-transform duration-300" leave-from-class="translate-x-0" leave-to-class="-translate-x-full">
            <div v-if="sidebarOpen" class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl lg:hidden">
                <div class="flex h-14 items-center justify-between px-4 border-b border-gray-100">
                    <span class="font-bold text-gray-900">Client Portal</span>
                    <button @click="sidebarOpen = false" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <nav class="px-3 py-3 space-y-0.5">
                    <Link v-for="item in nav" :key="item.name" :href="item.href" @click="sidebarOpen = false"
                        :class="[isActive(item.href) ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50', 'flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition']">
                        <svg class="w-5 h-5 shrink-0" :class="isActive(item.href) ? 'text-brand-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"/></svg>
                        {{ item.name }}
                    </Link>
                </nav>
            </div>
        </transition>

        <!-- Desktop sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-60 lg:flex-col">
            <div class="flex min-h-0 flex-1 flex-col bg-white border-r border-gray-100">
                <div class="flex h-14 items-center px-4 border-b border-gray-100">
                    <Link href="/client/dashboard" class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-gradient-to-br from-brand-500 to-brand-700 rounded-lg flex items-center justify-center"><span class="text-white font-bold text-xs">E</span></div>
                        <span class="text-sm font-bold text-gray-900">Client Portal</span>
                    </Link>
                </div>
                <nav class="flex-1 px-3 py-3 space-y-0.5">
                    <Link v-for="item in nav" :key="item.name" :href="item.href"
                        :class="[isActive(item.href) ? 'bg-brand-50 text-brand-700' : 'text-gray-600 hover:bg-gray-50', 'flex items-center gap-2.5 rounded-xl px-3 py-2 text-[13px] font-medium transition']">
                        <svg class="w-[18px] h-[18px] shrink-0" :class="isActive(item.href) ? 'text-brand-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"/></svg>
                        {{ item.name }}
                    </Link>
                </nav>
                <div class="border-t border-gray-100 p-3">
                    <Link href="/" class="flex items-center gap-2 px-3 py-2 text-[13px] text-gray-500 hover:text-brand-600 rounded-lg hover:bg-gray-50 transition">
                        ← Back to Website
                    </Link>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="lg:pl-60">
            <div class="sticky top-0 z-10 flex h-14 items-center gap-3 border-b border-gray-100 bg-white/95 backdrop-blur-sm px-4 sm:px-6">
                <button @click="sidebarOpen = true" class="lg:hidden p-1.5 text-gray-500 rounded-lg hover:bg-gray-100"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                <div class="flex-1"></div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 hidden sm:block">{{ user?.name }}</span>
                    <button @click="logout" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Logout">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </div>
            </div>
            <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
                <div v-if="flash?.success" class="mx-4 sm:mx-6 mt-4"><div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">{{ flash.success }}</div></div>
            </transition>
            <main class="p-4 sm:p-6"><slot /></main>
        </div>
    </div>
</template>
