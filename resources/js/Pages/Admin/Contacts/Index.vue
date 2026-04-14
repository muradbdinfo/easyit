<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
import StatusBadge from '@/Components/Admin/StatusBadge.vue'
defineProps({ contacts: Object, filters: Object })
const filterStatus = (status) => { router.get('/admin/contacts', status ? { status } : {}, { preserveState: true }) }
</script>
<template>
    <AdminLayout>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Contacts</h1>
        <div class="flex gap-2 mb-4">
            <button v-for="s in ['', 'new', 'read', 'replied', 'archived']" :key="s" @click="filterStatus(s)"
                :class="[(filters?.status || '') === s ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700', 'rounded-full px-3 py-1 text-sm capitalize']">
                {{ s || 'All' }}
            </button>
        </div>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="c in contacts.data" :key="c.id" :class="c.status === 'new' ? 'bg-blue-50' : ''">
                        <td class="px-6 py-4"><div class="text-sm font-medium text-gray-900">{{ c.name }}</div><div class="text-sm text-gray-500">{{ c.email }}</div></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ c.subject }}</td>
                        <td class="px-6 py-4"><StatusBadge :status="c.status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ new Date(c.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4 text-right text-sm"><Link :href="`/admin/contacts/${c.id}`" class="text-indigo-600 hover:text-indigo-900">View</Link></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="contacts.links" />
    </AdminLayout>
</template>
