<script setup>
import { Link } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
defineProps({ clientService: Object })
const formatDate = (d) => d ? new Date(d).toLocaleDateString('en', { month:'short', day:'numeric', year:'numeric' }) : '—'
</script>
<template>
    <ClientLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-gray-900">{{ clientService.label }}</h1>
            <Link href="/client/services" class="text-sm text-gray-500 hover:text-gray-700">← Back</Link>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Service Details</h3>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-gray-500">Service</dt><dd class="font-medium">{{ clientService.service?.title }}</dd></div>
                        <div><dt class="text-gray-500">Package</dt><dd class="font-medium">{{ clientService.package?.name || '—' }}</dd></div>
                        <div><dt class="text-gray-500">Price</dt><dd class="font-medium">৳{{ Number(clientService.price).toLocaleString() }}/{{ clientService.billing_cycle }}</dd></div>
                        <div><dt class="text-gray-500">Status</dt><dd class="font-medium capitalize">{{ clientService.status }}</dd></div>
                        <div><dt class="text-gray-500">Start Date</dt><dd class="font-medium">{{ formatDate(clientService.start_date) }}</dd></div>
                        <div><dt class="text-gray-500">Next Renewal</dt><dd class="font-medium">{{ formatDate(clientService.next_renewal_date) }}</dd></div>
                    </dl>
                </div>
                <div v-if="clientService.specs" class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Specifications</h3>
                    <dl class="grid grid-cols-2 gap-3 text-sm">
                        <div v-for="(val, key) in clientService.specs" :key="key"><dt class="text-gray-500 capitalize">{{ key }}</dt><dd class="font-medium">{{ val }}</dd></div>
                    </dl>
                </div>
            </div>
            <div class="space-y-6">
                <div v-if="clientService.invoices?.length" class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Recent Invoices</h3>
                    <div v-for="inv in clientService.invoices" :key="inv.id" class="flex justify-between text-sm py-1.5">
                        <Link :href="`/client/invoices/${inv.id}`" class="text-brand-600 hover:underline">{{ inv.invoice_number }}</Link>
                        <span class="capitalize text-gray-500">{{ inv.status }}</span>
                    </div>
                </div>
                <div v-if="clientService.tickets?.length" class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Recent Tickets</h3>
                    <div v-for="t in clientService.tickets" :key="t.id" class="text-sm py-1.5">
                        <Link :href="`/client/tickets/${t.id}`" class="text-brand-600 hover:underline">{{ t.subject }}</Link>
                    </div>
                </div>
            </div>
        </div>
    </ClientLayout>
</template>
