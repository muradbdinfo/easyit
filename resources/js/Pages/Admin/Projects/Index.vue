<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
import DeleteModal from '@/Components/Admin/DeleteModal.vue'

defineProps({ projects: Object })
const deleteId = ref(null)
const confirmDelete = () => { router.delete(`/admin/projects/${deleteId.value}`, { onSuccess: () => deleteId.value = null }) }
</script>

<template>
    <AdminLayout>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
            <Link href="/admin/projects/create" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add Project</Link>
        </div>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="p in projects.data" :key="p.id">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ p.title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ p.service?.title || '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ p.client_name || '—' }}</td>
                        <td class="px-6 py-4"><span :class="[p.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800', 'inline-flex rounded-full px-2 text-xs font-semibold']">{{ p.is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="px-6 py-4 text-right text-sm space-x-2">
                            <Link :href="`/admin/projects/${p.id}/edit`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                            <button @click="deleteId = p.id" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="projects.links" />
        <DeleteModal :show="!!deleteId" @confirm="confirmDelete" @cancel="deleteId = null" />
    </AdminLayout>
</template>
