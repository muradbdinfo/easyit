<script setup>
import { Link } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
defineProps({ stats: Object, recentTickets: Array, unpaidInvoices: Array, expiringServices: Array })
const formatDate = (d) => new Date(d).toLocaleDateString('en', { month:'short', day:'numeric' })
</script>
<template>
    <ClientLayout>
        <h1 class="text-xl font-bold text-gray-900 mb-6">Dashboard</h1>

        <!-- Expiring alert -->
        <div v-if="expiringServices?.length" class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
            <p class="text-sm font-semibold text-amber-800 mb-1">⚠️ Services expiring soon</p>
            <p v-for="s in expiringServices" :key="s.id" class="text-sm text-amber-700">{{ s.label }} — renews {{ formatDate(s.next_renewal_date) }}</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <Link href="/client/services" class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition group">
                <p class="text-2xl font-bold text-gray-900">{{ stats.activeServices }}</p>
                <p class="text-xs text-gray-500 mt-1">Active Services</p>
            </Link>
            <Link href="/client/tickets" class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold text-gray-900">{{ stats.openTickets }}</p>
                <p class="text-xs text-gray-500 mt-1">Open Tickets</p>
            </Link>
            <Link href="/client/invoices" class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition">
                <p class="text-2xl font-bold" :class="stats.unpaidInvoices > 0 ? 'text-red-600' : 'text-gray-900'">{{ stats.unpaidInvoices }}</p>
                <p class="text-xs text-gray-500 mt-1">Unpaid Invoices</p>
            </Link>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-2xl font-bold text-gray-900">৳{{ Number(stats.totalSpent).toLocaleString() }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Spent</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Tickets -->
            <div class="bg-white rounded-2xl border border-gray-100">
                <div class="flex justify-between items-center px-5 py-4 border-b border-gray-50">
                    <h3 class="font-semibold text-gray-900 text-sm">Recent Tickets</h3>
                    <Link href="/client/tickets" class="text-xs text-brand-600 hover:underline">View All</Link>
                </div>
                <div class="divide-y divide-gray-50">
                    <Link v-for="t in recentTickets" :key="t.id" :href="`/client/tickets/${t.id}`" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ t.subject }}</p>
                            <p class="text-xs text-gray-500">{{ t.ticket_number }} · {{ t.category?.name }}</p>
                        </div>
                        <span :class="{'bg-blue-100 text-blue-700':t.status==='open','bg-yellow-100 text-yellow-700':t.status==='in_progress','bg-green-100 text-green-700':t.status==='resolved','bg-gray-100 text-gray-600':t.status==='closed'}" class="text-[10px] font-semibold px-2 py-0.5 rounded-full capitalize shrink-0 ml-2">{{ t.status?.replace('_',' ') }}</span>
                    </Link>
                    <div v-if="!recentTickets?.length" class="px-5 py-8 text-center text-sm text-gray-400">No tickets yet</div>
                </div>
            </div>

            <!-- Unpaid Invoices -->
            <div class="bg-white rounded-2xl border border-gray-100">
                <div class="flex justify-between items-center px-5 py-4 border-b border-gray-50">
                    <h3 class="font-semibold text-gray-900 text-sm">Unpaid Invoices</h3>
                    <Link href="/client/invoices" class="text-xs text-brand-600 hover:underline">View All</Link>
                </div>
                <div class="divide-y divide-gray-50">
                    <Link v-for="inv in unpaidInvoices" :key="inv.id" :href="`/client/invoices/${inv.id}`" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ inv.invoice_number }}</p>
                            <p class="text-xs" :class="new Date(inv.due_date) < new Date() ? 'text-red-500 font-semibold' : 'text-gray-500'">Due {{ formatDate(inv.due_date) }}</p>
                        </div>
                        <span class="text-sm font-bold text-gray-900">৳{{ Number(inv.total).toLocaleString() }}</span>
                    </Link>
                    <div v-if="!unpaidInvoices?.length" class="px-5 py-8 text-center text-sm text-gray-400">All invoices paid!</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 flex flex-wrap gap-3">
            <Link href="/client/tickets/create" class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 transition">
                + New Ticket
            </Link>
            <Link href="/service-request" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                Request a Service
            </Link>
        </div>
    </ClientLayout>
</template>
