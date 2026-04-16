<script setup>
import { Link, router } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
defineProps({ tickets: Object, filters: Object, statusCounts: Object })
const filterStatus = (s) => router.get('/client/tickets', s ? { status: s } : {}, { preserveState: true })
const priorityColor = { low:'text-gray-500', medium:'text-blue-600', high:'text-orange-600', urgent:'text-red-600' }
const statusColor = { open:'bg-blue-100 text-blue-700', in_progress:'bg-yellow-100 text-yellow-700', waiting_on_client:'bg-orange-100 text-orange-700', waiting_on_admin:'bg-purple-100 text-purple-700', resolved:'bg-green-100 text-green-700', closed:'bg-gray-100 text-gray-600' }
const timeAgo = (d) => { const s = Math.floor((Date.now()-new Date(d))/1000); if(s<3600)return Math.floor(s/60)+'m ago'; if(s<86400)return Math.floor(s/3600)+'h ago'; return Math.floor(s/86400)+'d ago' }
</script>
<template>
    <ClientLayout>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h1 class="text-xl font-bold text-gray-900">Support Tickets</h1>
            <Link href="/client/tickets/create" class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 transition">+ New Ticket</Link>
        </div>
        <div class="flex gap-2 mb-4 flex-wrap">
            <button v-for="(label, key) in { '':'All', open:'Open', in_progress:'In Progress', resolved:'Resolved' }" :key="key" @click="filterStatus(key)"
                :class="[(filters?.status||'')=== key ? 'bg-brand-100 text-brand-700 border-brand-200' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50', 'border rounded-lg px-3 py-1.5 text-xs font-medium transition']">
                {{ label }} <span class="text-gray-400 ml-1">({{ key ? statusCounts[key] || 0 : statusCounts.all }})</span>
            </button>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div v-for="t in tickets.data" :key="t.id">
                <Link :href="`/client/tickets/${t.id}`" class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition border-b border-gray-50">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="text-xs text-gray-400 font-mono">{{ t.ticket_number }}</span>
                            <span :class="statusColor[t.status]" class="text-[10px] font-bold px-2 py-0.5 rounded-full capitalize">{{ t.status?.replace(/_/g,' ') }}</span>
                            <span :class="priorityColor[t.priority]" class="text-[10px] font-bold uppercase">{{ t.priority }}</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ t.subject }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 truncate">{{ t.latest_message?.body?.substring(0,80) }}</p>
                    </div>
                    <div class="text-xs text-gray-400 shrink-0">{{ timeAgo(t.updated_at) }}</div>
                </Link>
            </div>
            <div v-if="!tickets.data?.length" class="py-12 text-center text-gray-400 text-sm">No tickets found.</div>
        </div>
        <Pagination :links="tickets.links" />
    </ClientLayout>
</template>
