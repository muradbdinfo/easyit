<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatusBadge from '@/Components/Admin/StatusBadge.vue'

const props = defineProps({
    stats: Object,
    contactsChart: Array,
    requestsChart: Array,
    requestsByStatus: Array,
    contactsByStatus: Array,
    recentContacts: Array,
    recentRequests: Array,
    popularServices: Array,
    recentPosts: Array,
    totalViews: Number,
    recentComments: Array,
})

const user = computed(() => usePage().props.auth?.user)

// Animated counters
const animatedStats = ref({})
const animateCount = (key, target) => {
    animatedStats.value[key] = 0
    const num = parseInt(target) || 0
    if (num === 0) { animatedStats.value[key] = 0; return }
    const steps = 25
    const stepTime = 600 / steps
    const stepValue = num / steps
    let current = 0
    const timer = setInterval(() => {
        current += stepValue
        if (current >= num) {
            animatedStats.value[key] = num
            clearInterval(timer)
        } else {
            animatedStats.value[key] = Math.floor(current)
        }
    }, stepTime)
}

onMounted(() => {
    Object.keys(props.stats).forEach(key => animateCount(key, props.stats[key]))
})

// Chart
const chartMonths = computed(() => {
    const m = []
    for (let i = 5; i >= 0; i--) {
        const d = new Date(); d.setMonth(d.getMonth() - i)
        m.push(d.toLocaleDateString('en', { month: 'short' }))
    }
    return m
})
const chartMonthKeys = computed(() => {
    const k = []
    for (let i = 5; i >= 0; i--) {
        const d = new Date(); d.setMonth(d.getMonth() - i)
        k.push(`${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`)
    }
    return k
})
const getVal = (data, key) => data?.find(d => d.month === key)?.count || 0
const maxChart = computed(() => {
    let m = 1
    chartMonthKeys.value.forEach(k => { m = Math.max(m, getVal(props.contactsChart, k), getVal(props.requestsChart, k)) })
    return m
})
const barH = (v) => Math.max(3, (v / maxChart.value) * 100)

// Status
const statusColors = {
    new: 'bg-sky-500', read: 'bg-slate-400', replied: 'bg-emerald-500', archived: 'bg-amber-500',
    pending: 'bg-orange-500', reviewing: 'bg-sky-500', quoted: 'bg-violet-500', accepted: 'bg-emerald-500', rejected: 'bg-rose-500',
}
const totalSC = (d) => d?.reduce((s, x) => s + x.count, 0) || 1
const sPct = (d, s) => { const i = d?.find(x => x.status === s); return i ? Math.round((i.count / totalSC(d)) * 100) : 0 }
const sCount = (d, s) => d?.find(x => x.status === s)?.count || 0

const timeAgo = (d) => {
    const diff = Math.floor((Date.now() - new Date(d)) / 1000)
    if (diff < 60) return 'now'
    if (diff < 3600) return `${Math.floor(diff / 60)}m`
    if (diff < 86400) return `${Math.floor(diff / 3600)}h`
    if (diff < 604800) return `${Math.floor(diff / 86400)}d`
    return new Date(d).toLocaleDateString('en', { month: 'short', day: 'numeric' })
}
const formatDate = (d) => new Date(d).toLocaleDateString('en', { month: 'short', day: 'numeric' })

const activeTab = ref('contacts')

// Greeting based on time
const greeting = computed(() => {
    const h = new Date().getHours()
    if (h < 12) return 'Good morning'
    if (h < 17) return 'Good afternoon'
    return 'Good evening'
})

// Comment status colors
const commentStatusColor = {
    pending: 'bg-amber-100 text-amber-700',
    approved: 'bg-emerald-100 text-emerald-700',
    spam: 'bg-red-100 text-red-700',
    trash: 'bg-gray-100 text-gray-500',
}
</script>

<template>
    <AdminLayout>
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">{{ greeting }}, {{ user?.name?.split(' ')[0] }} 👋</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Here's your business overview</p>
                </div>
                <div class="flex gap-2">
                    <!-- Pending Comments Alert -->
                    <Link v-if="stats.pending_comments > 0" href="/admin/comments?status=pending"
                        class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100 transition">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        {{ stats.pending_comments }} pending comment{{ stats.pending_comments > 1 ? 's' : '' }}
                    </Link>
                    <Link v-if="stats.new_contacts > 0" href="/admin/contacts"
                        class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 border border-sky-200 px-3 py-1.5 text-xs font-medium text-sky-700 hover:bg-sky-100 transition">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                        </span>
                        {{ stats.new_contacts }} new message{{ stats.new_contacts > 1 ? 's' : '' }}
                    </Link>
                    <Link href="/" target="_blank"
                        class="inline-flex items-center gap-1 rounded-full bg-gray-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-gray-800 transition">
                        View Site
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 sm:gap-4 mb-6 sm:mb-8">
            <!-- Services -->
            <Link href="/admin/services" class="group bg-white rounded-2xl border border-gray-100 p-3 sm:p-4 hover:shadow-lg hover:border-gray-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-50 flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-indigo-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-none">{{ animatedStats.total_services ?? 0 }}</p>
                <p class="text-[10px] sm:text-xs text-gray-400 mt-1 font-medium uppercase tracking-wider">Services</p>
            </Link>

            <!-- Projects -->
            <Link href="/admin/projects" class="group bg-white rounded-2xl border border-gray-100 p-3 sm:p-4 hover:shadow-lg hover:border-gray-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-violet-50 flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-violet-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-none">{{ animatedStats.total_projects ?? 0 }}</p>
                <p class="text-[10px] sm:text-xs text-gray-400 mt-1 font-medium uppercase tracking-wider">Projects</p>
            </Link>

            <!-- Posts -->
            <Link href="/admin/posts" class="group bg-white rounded-2xl border border-gray-100 p-3 sm:p-4 hover:shadow-lg hover:border-gray-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-amber-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-none">{{ animatedStats.total_posts ?? 0 }}</p>
                <div class="flex gap-1.5 mt-1">
                    <span class="text-[10px] text-emerald-600 font-medium">{{ stats.published_posts }} live</span>
                    <span class="text-[10px] text-gray-300">·</span>
                    <span class="text-[10px] text-gray-400">{{ stats.draft_posts }} draft</span>
                </div>
            </Link>

            <!-- Comments -->
            <Link href="/admin/comments" class="group bg-white rounded-2xl border border-gray-100 p-3 sm:p-4 hover:shadow-lg hover:border-gray-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-pink-50 flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-pink-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-none">{{ animatedStats.total_comments ?? 0 }}</p>
                <div class="mt-1">
                    <span v-if="stats.pending_comments > 0" class="inline-flex items-center gap-1 text-[10px] text-amber-600 font-medium">
                        <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span> {{ stats.pending_comments }} pending
                    </span>
                    <span v-else class="text-[10px] text-gray-400">{{ stats.approved_comments }} approved</span>
                </div>
            </Link>

            <!-- Contacts -->
            <Link href="/admin/contacts" class="group bg-white rounded-2xl border border-gray-100 p-3 sm:p-4 hover:shadow-lg hover:border-gray-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-sky-50 flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-sky-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-none">{{ animatedStats.total_contacts ?? 0 }}</p>
                <div class="mt-1">
                    <span v-if="stats.new_contacts > 0" class="inline-flex items-center gap-1 text-[10px] text-sky-600 font-medium">
                        <span class="w-1 h-1 rounded-full bg-sky-500 animate-pulse"></span> {{ stats.new_contacts }} unread
                    </span>
                    <span v-else class="text-[10px] text-gray-400">All read</span>
                </div>
            </Link>

            <!-- Requests -->
            <Link href="/admin/service-requests" class="group bg-white rounded-2xl border border-gray-100 p-3 sm:p-4 hover:shadow-lg hover:border-gray-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-orange-50 flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-orange-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-none">{{ animatedStats.total_requests ?? 0 }}</p>
                <div class="mt-1">
                    <span v-if="stats.pending_requests > 0" class="inline-flex items-center gap-1 text-[10px] text-orange-600 font-medium">
                        <span class="w-1 h-1 rounded-full bg-orange-500 animate-pulse"></span> {{ stats.pending_requests }} pending
                    </span>
                    <span v-else class="text-[10px] text-gray-400">None pending</span>
                </div>
            </Link>

            <!-- Blog Views -->
            <div class="group bg-white rounded-2xl border border-gray-100 p-3 sm:p-4 hover:shadow-lg hover:border-gray-200 transition-all duration-300 hover:-translate-y-0.5 cursor-default">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-2 sm:mb-3 group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-none">{{ totalViews?.toLocaleString() ?? 0 }}</p>
                <p class="text-[10px] sm:text-xs text-gray-400 mt-1 font-medium uppercase tracking-wider">Blog Views</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Bar Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-4 sm:mb-6">
                    <h3 class="text-sm font-semibold text-gray-900">Monthly Activity</h3>
                    <div class="flex items-center gap-3 sm:gap-4 text-[10px] sm:text-xs">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-indigo-500"></span> Contacts</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span> Requests</span>
                    </div>
                </div>
                <div class="flex items-end gap-1.5 sm:gap-3 h-36 sm:h-48">
                    <div v-for="(key, i) in chartMonthKeys" :key="key" class="flex-1 flex flex-col items-center gap-1">
                        <div class="flex gap-0.5 sm:gap-1 items-end w-full justify-center h-28 sm:h-40">
                            <div class="relative group flex-1 max-w-[20px] sm:max-w-[24px]">
                                <div class="bg-indigo-500 rounded-t-sm sm:rounded-t w-full transition-all duration-700 ease-out cursor-pointer hover:bg-indigo-600"
                                    :style="{ height: barH(getVal(contactsChart, key)) + '%', minHeight: getVal(contactsChart, key) > 0 ? '3px' : '0' }">
                                </div>
                                <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap z-10">
                                    {{ getVal(contactsChart, key) }}
                                </div>
                            </div>
                            <div class="relative group flex-1 max-w-[20px] sm:max-w-[24px]">
                                <div class="bg-emerald-500 rounded-t-sm sm:rounded-t w-full transition-all duration-700 ease-out cursor-pointer hover:bg-emerald-600"
                                    :style="{ height: barH(getVal(requestsChart, key)) + '%', minHeight: getVal(requestsChart, key) > 0 ? '3px' : '0' }">
                                </div>
                                <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap z-10">
                                    {{ getVal(requestsChart, key) }}
                                </div>
                            </div>
                        </div>
                        <span class="text-[10px] sm:text-xs text-gray-400 font-medium">{{ chartMonths[i] }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6">
                <div class="flex bg-gray-100 rounded-xl p-0.5 mb-4 sm:mb-6">
                    <button @click="activeTab = 'contacts'" :class="[activeTab === 'contacts' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700', 'flex-1 rounded-lg px-3 py-2 text-xs font-medium transition-all']">Contacts</button>
                    <button @click="activeTab = 'requests'" :class="[activeTab === 'requests' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700', 'flex-1 rounded-lg px-3 py-2 text-xs font-medium transition-all']">Requests</button>
                </div>

                <div v-if="activeTab === 'contacts'" class="space-y-3 sm:space-y-4">
                    <div v-for="status in ['new', 'read', 'replied', 'archived']" :key="status" class="group">
                        <div class="flex justify-between text-xs sm:text-sm mb-1.5">
                            <span class="capitalize text-gray-600 font-medium">{{ status }}</span>
                            <span class="font-semibold text-gray-900 tabular-nums">{{ sCount(contactsByStatus, status) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 sm:h-2 overflow-hidden">
                            <div :class="statusColors[status]" class="h-full rounded-full transition-all duration-1000 ease-out group-hover:opacity-80"
                                :style="{ width: sPct(contactsByStatus, status) + '%' }"></div>
                        </div>
                    </div>
                </div>

                <div v-if="activeTab === 'requests'" class="space-y-3 sm:space-y-4">
                    <div v-for="status in ['pending', 'reviewing', 'quoted', 'accepted', 'rejected']" :key="status" class="group">
                        <div class="flex justify-between text-xs sm:text-sm mb-1.5">
                            <span class="capitalize text-gray-600 font-medium">{{ status }}</span>
                            <span class="font-semibold text-gray-900 tabular-nums">{{ sCount(requestsByStatus, status) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 sm:h-2 overflow-hidden">
                            <div :class="statusColors[status]" class="h-full rounded-full transition-all duration-1000 ease-out group-hover:opacity-80"
                                :style="{ width: sPct(requestsByStatus, status) + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Row: Lists -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Recent Contacts -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="flex justify-between items-center px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">Recent Contacts</h3>
                    <Link href="/admin/contacts" class="text-[10px] sm:text-xs text-indigo-600 hover:text-indigo-800 font-medium">View All →</Link>
                </div>
                <div class="divide-y divide-gray-50 max-h-72 sm:max-h-80 overflow-y-auto">
                    <Link v-for="c in recentContacts" :key="c.id" :href="`/admin/contacts/${c.id}`"
                        class="flex items-start gap-2.5 sm:gap-3 px-4 sm:px-5 py-2.5 sm:py-3 hover:bg-gray-50/80 transition group">
                        <div class="mt-1.5 shrink-0">
                            <span :class="[c.status === 'new' ? 'bg-sky-500 ring-2 ring-sky-200' : 'bg-gray-300', 'block w-2 h-2 rounded-full transition-all group-hover:scale-125']"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between items-center gap-2">
                                <p :class="[c.status === 'new' ? 'font-semibold' : 'font-medium', 'text-xs sm:text-sm text-gray-900 truncate']">{{ c.name }}</p>
                                <span class="text-[10px] text-gray-400 shrink-0 tabular-nums">{{ timeAgo(c.created_at) }}</span>
                            </div>
                            <p class="text-[11px] sm:text-xs text-gray-500 truncate mt-0.5">{{ c.subject }}</p>
                        </div>
                    </Link>
                    <div v-if="!recentContacts?.length" class="flex flex-col items-center justify-center py-8 sm:py-10 text-gray-400">
                        <svg class="w-8 h-8 mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <p class="text-xs">No contacts yet</p>
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="flex justify-between items-center px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">Service Requests</h3>
                    <Link href="/admin/service-requests" class="text-[10px] sm:text-xs text-indigo-600 hover:text-indigo-800 font-medium">View All →</Link>
                </div>
                <div class="divide-y divide-gray-50 max-h-72 sm:max-h-80 overflow-y-auto">
                    <Link v-for="r in recentRequests" :key="r.id" :href="`/admin/service-requests/${r.id}`"
                        class="flex items-start gap-2.5 sm:gap-3 px-4 sm:px-5 py-2.5 sm:py-3 hover:bg-gray-50/80 transition group">
                        <div class="mt-1.5 shrink-0">
                            <span :class="[r.urgency === 'high' ? 'bg-rose-500 ring-2 ring-rose-200' : r.urgency === 'medium' ? 'bg-amber-500' : 'bg-emerald-500', 'block w-2 h-2 rounded-full transition-all group-hover:scale-125']"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between items-center gap-2">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ r.name }}</p>
                                <StatusBadge :status="r.status" />
                            </div>
                            <p class="text-[11px] sm:text-xs text-gray-500 truncate mt-0.5">{{ r.service?.title || 'General' }}</p>
                        </div>
                    </Link>
                    <div v-if="!recentRequests?.length" class="flex flex-col items-center justify-center py-8 sm:py-10 text-gray-400">
                        <svg class="w-8 h-8 mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-xs">No requests yet</p>
                    </div>
                </div>
            </div>

            <!-- Recent Comments -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="flex justify-between items-center px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">Recent Comments</h3>
                    <Link href="/admin/comments" class="text-[10px] sm:text-xs text-indigo-600 hover:text-indigo-800 font-medium">View All →</Link>
                </div>
                <div class="divide-y divide-gray-50 max-h-72 sm:max-h-80 overflow-y-auto">
                    <Link v-for="cm in recentComments" :key="cm.id" :href="`/admin/comments/${cm.id}`"
                        class="flex items-start gap-2.5 sm:gap-3 px-4 sm:px-5 py-2.5 sm:py-3 hover:bg-gray-50/80 transition group">
                        <div class="mt-0.5 shrink-0">
                            <div :class="[cm.is_admin_reply ? 'bg-indigo-500' : 'bg-gray-400', 'w-6 h-6 rounded-full flex items-center justify-center']">
                                <span class="text-white text-[9px] font-bold">{{ (cm.user?.name || cm.author_name || 'A').charAt(0).toUpperCase() }}</span>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between items-center gap-2">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ cm.user?.name || cm.author_name || 'Anonymous' }}</p>
                                <span :class="[commentStatusColor[cm.status], 'text-[9px] px-1.5 py-0.5 rounded-full font-semibold capitalize shrink-0']">{{ cm.status }}</span>
                            </div>
                            <p class="text-[11px] sm:text-xs text-gray-500 line-clamp-1 mt-0.5">{{ cm.body }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[10px] text-gray-400 truncate">{{ cm.post?.title }}</span>
                                <span class="text-[10px] text-gray-300">·</span>
                                <span class="text-[10px] text-gray-400 shrink-0">{{ timeAgo(cm.created_at) }}</span>
                            </div>
                        </div>
                    </Link>
                    <div v-if="!recentComments?.length" class="flex flex-col items-center justify-center py-8 sm:py-10 text-gray-400">
                        <svg class="w-8 h-8 mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        <p class="text-xs">No comments yet</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Top Services + Posts -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Popular Services -->
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900">Top Services</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <div v-for="(s, i) in popularServices" :key="s.id" class="flex items-center gap-2.5 sm:gap-3 px-4 sm:px-5 py-2 sm:py-2.5 hover:bg-gray-50/80 transition">
                            <span :class="[
                                i === 0 ? 'bg-amber-100 text-amber-700 ring-1 ring-amber-200' :
                                i === 1 ? 'bg-gray-100 text-gray-600 ring-1 ring-gray-200' :
                                i === 2 ? 'bg-orange-50 text-orange-600 ring-1 ring-orange-200' :
                                'bg-gray-50 text-gray-500',
                                'w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center text-[10px] sm:text-xs font-bold shrink-0'
                            ]">{{ i + 1 }}</span>
                            <span class="text-xs sm:text-sm text-gray-700 flex-1 truncate">{{ s.title }}</span>
                            <span class="text-[10px] sm:text-xs text-gray-400 font-medium tabular-nums">{{ s.service_requests_count }}</span>
                        </div>
                        <div v-if="!popularServices?.length" class="px-5 py-6 text-center text-xs text-gray-400">No data yet</div>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="flex justify-between items-center px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900">Recent Posts</h3>
                        <Link href="/admin/posts" class="text-[10px] sm:text-xs text-indigo-600 hover:text-indigo-800 font-medium">All →</Link>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <Link v-for="p in recentPosts" :key="p.id" :href="`/admin/posts/${p.id}/edit`"
                            class="flex items-center gap-2.5 sm:gap-3 px-4 sm:px-5 py-2 sm:py-2.5 hover:bg-gray-50/80 transition">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm text-gray-900 truncate font-medium">{{ p.title }}</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span class="text-[10px] text-gray-400">{{ p.views }}</span>
                                    <span class="text-[10px] text-gray-300">·</span>
                                    <span class="text-[10px] text-gray-400">{{ formatDate(p.created_at) }}</span>
                                </div>
                            </div>
                            <StatusBadge :status="p.status" />
                        </Link>
                        <div v-if="!recentPosts?.length" class="px-5 py-6 text-center text-xs text-gray-400">No posts yet</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 sm:mb-4">Quick Actions</h3>
            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-7 gap-2 sm:gap-3">
                <Link v-for="action in [
                    { href: '/admin/services/create', icon: '⚙️', label: 'Add Service' },
                    { href: '/admin/projects/create', icon: '🗂️', label: 'Add Project' },
                    { href: '/admin/posts/create', icon: '📝', label: 'New Post' },
                    { href: '/admin/comments?status=pending', icon: '💬', label: 'Moderate' },
                    { href: '/admin/packages/create', icon: '📦', label: 'Add Package' },
                    { href: '/admin/team-members/create', icon: '👤', label: 'Add Member' },
                    { href: '/admin/settings', icon: '🔧', label: 'Settings' },
                ]" :key="action.href"
                    :href="action.href"
                    class="flex flex-col items-center gap-1.5 sm:gap-2 p-3 sm:p-4 rounded-xl bg-gray-50 hover:bg-indigo-50 transition-all duration-200 text-center group hover:shadow-sm active:scale-95">
                    <span class="text-xl sm:text-2xl group-hover:scale-110 transition-transform duration-200">{{ action.icon }}</span>
                    <span class="text-[10px] sm:text-xs font-medium text-gray-600 group-hover:text-indigo-700 leading-tight">{{ action.label }}</span>
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>