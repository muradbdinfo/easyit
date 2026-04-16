<script setup>
import { Link } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
defineProps({ invoice: Object })
const formatDate = (d) => d ? new Date(d).toLocaleDateString('en', { month:'long', day:'numeric', year:'numeric' }) : '—'
</script>
<template>
    <ClientLayout>
        <div class="flex items-center justify-between mb-6">
            <div>
                <Link href="/client/invoices" class="text-xs text-gray-400 hover:text-gray-600">← Invoices</Link>
                <h1 class="text-lg font-bold text-gray-900 mt-1">Invoice {{ invoice.invoice_number }}</h1>
            </div>
            <div class="flex gap-2">
                <Link v-if="['sent','overdue','partially_paid'].includes(invoice.status)" :href="`/client/invoices/${invoice.id}/pay`"
                    class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700 transition">
                    Pay Now
                </Link>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 max-w-3xl">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
                <div><p class="font-bold text-lg text-gray-900">Easy IT</p><p class="text-sm text-gray-500">info@easyit.com.bd</p></div>
                <div class="text-sm text-right">
                    <p><span class="text-gray-500">Invoice:</span> <strong>{{ invoice.invoice_number }}</strong></p>
                    <p><span class="text-gray-500">Date:</span> {{ formatDate(invoice.issue_date) }}</p>
                    <p><span class="text-gray-500">Due:</span> <span :class="invoice.status==='overdue' ? 'text-red-600 font-bold' : ''">{{ formatDate(invoice.due_date) }}</span></p>
                </div>
            </div>

            <!-- Bill To -->
            <div class="mb-6">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Bill To</p>
                <p class="font-semibold text-gray-900">{{ invoice.user?.name }}</p>
                <p class="text-sm text-gray-500">{{ invoice.user?.email }}</p>
            </div>

            <!-- Items -->
            <table class="w-full mb-6">
                <thead><tr class="border-b border-gray-200">
                    <th class="text-left text-xs font-semibold text-gray-500 pb-2">Description</th>
                    <th class="text-center text-xs font-semibold text-gray-500 pb-2">Qty</th>
                    <th class="text-right text-xs font-semibold text-gray-500 pb-2">Price</th>
                    <th class="text-right text-xs font-semibold text-gray-500 pb-2">Total</th>
                </tr></thead>
                <tbody>
                    <tr v-for="item in invoice.items" :key="item.id" class="border-b border-gray-50">
                        <td class="py-3 text-sm text-gray-900">{{ item.description }}</td>
                        <td class="py-3 text-sm text-gray-500 text-center">{{ item.quantity }}</td>
                        <td class="py-3 text-sm text-gray-500 text-right">৳{{ Number(item.unit_price).toLocaleString() }}</td>
                        <td class="py-3 text-sm font-medium text-gray-900 text-right">৳{{ Number(item.total).toLocaleString() }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="flex justify-end">
                <div class="w-64 space-y-1.5 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>৳{{ Number(invoice.subtotal).toLocaleString() }}</span></div>
                    <div v-if="invoice.tax > 0" class="flex justify-between"><span class="text-gray-500">Tax</span><span>৳{{ Number(invoice.tax).toLocaleString() }}</span></div>
                    <div v-if="invoice.discount > 0" class="flex justify-between"><span class="text-gray-500">Discount</span><span class="text-green-600">-৳{{ Number(invoice.discount).toLocaleString() }}</span></div>
                    <div class="flex justify-between pt-2 border-t border-gray-200 font-bold text-base">
                        <span>Total</span><span>৳{{ Number(invoice.total).toLocaleString() }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div v-if="invoice.payments?.length" class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-3 text-sm">Payment History</h3>
                <div v-for="p in invoice.payments" :key="p.id" class="flex items-center justify-between py-2 text-sm border-b border-gray-50">
                    <div>
                        <span class="font-medium capitalize">{{ p.method?.replace('_',' ') }}</span>
                        <span v-if="p.transaction_id" class="text-gray-400 ml-2">ID: {{ p.transaction_id }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold">৳{{ Number(p.amount).toLocaleString() }}</span>
                        <span :class="{'bg-green-100 text-green-700':p.status==='verified','bg-yellow-100 text-yellow-700':p.status==='pending','bg-red-100 text-red-700':p.status==='rejected'}" class="text-[10px] font-bold px-2 py-0.5 rounded-full capitalize">{{ p.status }}</span>
                    </div>
                </div>
            </div>
        </div>
    </ClientLayout>
</template>
