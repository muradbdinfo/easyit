<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
import DeleteModal from '@/Components/Admin/DeleteModal.vue'
defineProps({ testimonials: Object })
const deleteId = ref(null)
const confirmDelete = () => { router.delete(`/admin/testimonials/${deleteId.value}`, { onSuccess: () => deleteId.value = null }) }
</script>
<template>
    <AdminLayout>
        <div class="flex justify-between items-center mb-6"><h1 class="text-2xl font-bold text-gray-900">Testimonials</h1><Link href="/admin/testimonials/create" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add Testimonial</Link></div>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="t in testimonials.data" :key="t.id">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ t.client_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ t.company || '—' }}</td>
                        <td class="px-6 py-4 text-sm text-yellow-500">{{ '★'.repeat(t.rating) }}</td>
                        <td class="px-6 py-4"><span :class="[t.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800', 'inline-flex rounded-full px-2 text-xs font-semibold']">{{ t.is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="px-6 py-4 text-right text-sm space-x-2"><Link :href="`/admin/testimonials/${t.id}/edit`" class="text-indigo-600 hover:text-indigo-900">Edit</Link><button @click="deleteId = t.id" class="text-red-600 hover:text-red-900">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="testimonials.links" /><DeleteModal :show="!!deleteId" @confirm="confirmDelete" @cancel="deleteId = null" />
    </AdminLayout>
</template>
