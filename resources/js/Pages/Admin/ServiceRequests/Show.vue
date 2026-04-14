<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatusBadge from '@/Components/Admin/StatusBadge.vue'
const props = defineProps({ serviceRequest: Object })
const adminNotes = ref(props.serviceRequest.admin_notes || '')
const updateStatus = (status) => { router.patch(`/admin/service-requests/${props.serviceRequest.id}/status`, { status, admin_notes: adminNotes.value }) }
</script>
<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-gray-900">Service Request Details</h1><Link href="/admin/service-requests" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link></div>
        <div class="bg-white shadow rounded-lg p-6 max-w-3xl space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div><p class="text-sm text-gray-500">Name</p><p class="font-medium">{{ serviceRequest.name }}</p></div>
                <div><p class="text-sm text-gray-500">Email</p><p class="font-medium">{{ serviceRequest.email }}</p></div>
                <div><p class="text-sm text-gray-500">Phone</p><p class="font-medium">{{ serviceRequest.phone }}</p></div>
                <div><p class="text-sm text-gray-500">Company</p><p class="font-medium">{{ serviceRequest.company || '—' }}</p></div>
                <div><p class="text-sm text-gray-500">Service</p><p class="font-medium">{{ serviceRequest.service?.title || 'General' }}</p></div>
                <div><p class="text-sm text-gray-500">Budget</p><p class="font-medium">{{ serviceRequest.budget_range || '—' }}</p></div>
                <div><p class="text-sm text-gray-500">Urgency</p><p class="font-medium capitalize">{{ serviceRequest.urgency }}</p></div>
                <div><p class="text-sm text-gray-500">Status</p><StatusBadge :status="serviceRequest.status" /></div>
            </div>
            <div><p class="text-sm text-gray-500 mb-1">Requirements</p><p class="text-gray-700 whitespace-pre-wrap">{{ serviceRequest.requirements }}</p></div>
            <div><label class="block text-sm text-gray-500 mb-1">Admin Notes</label><textarea v-model="adminNotes" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea></div>
            <div class="flex gap-2">
                <button v-for="s in ['pending', 'reviewing', 'quoted', 'accepted', 'rejected']" :key="s" @click="updateStatus(s)"
                    :class="[serviceRequest.status === s ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'rounded-md px-3 py-1 text-sm capitalize']">
                    {{ s }}
                </button>
            </div>
        </div>
    </AdminLayout>
</template>
