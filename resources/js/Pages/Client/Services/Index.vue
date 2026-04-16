<script setup>
import { Link } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
defineProps({ services: Array })
const statusColors = { active:'bg-emerald-100 text-emerald-700', pending:'bg-amber-100 text-amber-700', suspended:'bg-red-100 text-red-700', expired:'bg-gray-100 text-gray-600', cancelled:'bg-gray-100 text-gray-500' }
const formatDate = (d) => d ? new Date(d).toLocaleDateString('en', { month:'short', day:'numeric', year:'numeric' }) : '—'
</script>
<template>
    <ClientLayout>
        <h1 class="text-xl font-bold text-gray-900 mb-6">My Services</h1>
        <div class="space-y-4">
            <Link v-for="s in services" :key="s.id" :href="`/client/services/${s.id}`" class="block bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-gray-900">{{ s.label }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ s.service?.title }} {{ s.package ? `— ${s.package.name}` : '' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-gray-900">৳{{ Number(s.price).toLocaleString() }}/{{ s.billing_cycle }}</span>
                        <span :class="statusColors[s.status]" class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">{{ s.status }}</span>
                    </div>
                </div>
                <div v-if="s.next_renewal_date" class="mt-3 text-xs text-gray-400">Renews: {{ formatDate(s.next_renewal_date) }}</div>
            </Link>
            <div v-if="!services?.length" class="text-center py-12 text-gray-400">No services assigned yet.</div>
        </div>
    </ClientLayout>
</template>
