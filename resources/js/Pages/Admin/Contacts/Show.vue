<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatusBadge from '@/Components/Admin/StatusBadge.vue'
defineProps({ contact: Object })
const updateStatus = (id, status) => { router.patch(`/admin/contacts/${id}/status`, { status }) }
</script>
<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-gray-900">Contact Details</h1><Link href="/admin/contacts" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link></div>
        <div class="bg-white shadow rounded-lg p-6 max-w-3xl">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div><p class="text-sm text-gray-500">Name</p><p class="font-medium">{{ contact.name }}</p></div>
                <div><p class="text-sm text-gray-500">Email</p><p class="font-medium">{{ contact.email }}</p></div>
                <div><p class="text-sm text-gray-500">Phone</p><p class="font-medium">{{ contact.phone || '—' }}</p></div>
                <div><p class="text-sm text-gray-500">Status</p><StatusBadge :status="contact.status" /></div>
            </div>
            <div class="mb-6"><p class="text-sm text-gray-500 mb-1">Subject</p><p class="font-medium">{{ contact.subject }}</p></div>
            <div class="mb-6"><p class="text-sm text-gray-500 mb-1">Message</p><p class="text-gray-700 whitespace-pre-wrap">{{ contact.message }}</p></div>
            <div class="flex gap-2">
                <button v-for="s in ['new', 'read', 'replied', 'archived']" :key="s" @click="updateStatus(contact.id, s)"
                    :class="[contact.status === s ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'rounded-md px-3 py-1 text-sm capitalize']">
                    {{ s }}
                </button>
            </div>
        </div>
    </AdminLayout>
</template>
