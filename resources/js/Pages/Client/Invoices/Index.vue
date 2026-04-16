<script setup>
import { Link, router } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
defineProps({ invoices: Object, filters: Object, summary: Object })
const filterStatus = (s) => router.get('/client/invoices', s ? { status: s } : {}, { preserveState: true })
const formatDate = (d) => new Date(d).toLocaleDateString('en', { month:'short', day:'numeric', year:'numeric' })
const statusColor = { draft:'bg-gray-100 text-gray-600', sent:'bg-blue-100 text-blue-700', paid:'bg-green-100 text-green-700', partially_paid:'bg-yellow-100 text-yellow-700', overdue:'bg-red-100 text-red-700', cancelled:'bg-gray-100 text-gray-500' }
</script>
<template>
    <ClientLayout>
        <h1 class="text-xl font-bold text-gray-900 mb-2">Invoices</h1>
        <div class="flex gap-4 mb-6 text-sm">
            <span class="text-gray-500">Total Due: <strong class="text-red-600">৳{{ Number(summary.totalDue).toLocaleString() }}</strong></span>
            <span class="text-gray-500">Total Paid: <strong class="text-green-600">৳{{ Number(summary.totalPaid).toLocaleString() }}</strong></span>
        </div>
        <div class="flex gap-2 mb-4 flex-wrap">
            <button v-for="(label, key) in { '':'All', sent:'Unpaid', paid:'Paid', overdue:'Overdue' }" :key="key" @click="filterStatus(key)"
                :class="[(filters?.status||'')===key ? 'bg-brand-100 text-brand-700 border-brand-200' : 'bg-white text-gray-600 border-gray-200', 'border rounded-lg px-3 py-1.5 text-xs font-medium transition']">
                {{ label }}
            </button>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50"><tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Invoice</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">Service</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Due</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Amount</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    <tr v-for="inv in invoices.data" :key="inv.id" class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ inv.invoice_number }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500 hidden sm:table-cell">{{ inv.client_service?.service?.title || '—' }}</td>
                        <td class="px-5 py-3 text-sm" :class="new Date(inv.due_date) < new Date() && inv.status !== 'paid' ? 'text-red-600 font-semibold' : 'text-gray-500'">{{ formatDate(inv.due_date) }}</td>
                        <td class="px-5 py-3 text-sm font-bold text-gray-900">৳{{ Number(inv.total).toLocaleString() }}</td>
                        <td class="px-5 py-3"><span :class="statusColor[inv.status]" class="text-[10px] font-bold px-2 py-0.5 rounded-full capitalize">{{ inv.status?.replace('_',' ') }}</span></td>
                        <td class="px-5 py-3 text-right"><Link :href="`/client/invoices/${inv.id}`" class="text-xs text-brand-600 hover:underline">View</Link></td>
                    </tr>
                </tbody>
            </table>
            <div v-if="!invoices.data?.length" class="py-12 text-center text-gray-400 text-sm">No invoices found.</div>
        </div>
        <Pagination :links="invoices.links" />
    </ClientLayout>
</template>
