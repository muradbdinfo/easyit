<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
import StatusBadge from '@/Components/Admin/StatusBadge.vue'
defineProps({ requests: Object, filters: Object })
const filterStatus = (status) => { router.get('/admin/service-requests', status ? { status } : {}, { preserveState: true }) }
</script>
<template>
    <AdminLayout>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Service Requests</h1>
        <div class="flex gap-2 mb-4">
            <button v-for="s in ['', 'pending', 'reviewing', 'quoted', 'accepted', 'rejected']" :key="s" @click="filterStatus(s)"
                :class="[(filters?.status || '') === s ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700', 'rounded-full px-3 py-1 text-sm capitalize']">
                {{ s || 'All' }}
            </button>
        </div>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urgency</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="r in requests.data" :key="r.id">
                        <td class="px-6 py-4"><div class="text-sm font-medium text-gray-900">{{ r.name }}</div><div class="text-sm text-gray-500">{{ r.email }}</div></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ r.service?.title || 'General' }}</td>
                        <td class="px-6 py-4"><span :class="{'text-red-600': r.urgency === 'high', 'text-yellow-600': r.urgency === 'medium', 'text-green-600': r.urgency === 'low'}" class="text-sm font-medium capitalize">{{ r.urgency }}</span></td>
                        <td class="px-6 py-4"><StatusBadge :status="r.status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ new Date(r.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4 text-right text-sm"><Link :href="`/admin/service-requests/${r.id}`" class="text-indigo-600 hover:text-indigo-900">View</Link></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="requests.links" />
    </AdminLayout>
</template>
