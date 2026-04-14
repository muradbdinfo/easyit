<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
import DeleteModal from '@/Components/Admin/DeleteModal.vue'
defineProps({ packages: Object })
const deleteId = ref(null)
const confirmDelete = () => { router.delete(`/admin/packages/${deleteId.value}`, { onSuccess: () => deleteId.value = null }) }
</script>
<template>
    <AdminLayout>
        <div class="flex justify-between items-center mb-6"><h1 class="text-2xl font-bold text-gray-900">Packages</h1><Link href="/admin/packages/create" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add Package</Link></div>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cycle</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Features</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="p in packages.data" :key="p.id">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ p.name }} <span v-if="p.is_featured" class="text-xs text-yellow-600">(Featured)</span></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ p.service?.title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-semibold">৳{{ p.price }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 capitalize">{{ p.billing_cycle }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ p.features_count }}</td>
                        <td class="px-6 py-4 text-right text-sm space-x-2"><Link :href="`/admin/packages/${p.id}/edit`" class="text-indigo-600 hover:text-indigo-900">Edit</Link><button @click="deleteId = p.id" class="text-red-600 hover:text-red-900">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="packages.links" /><DeleteModal :show="!!deleteId" @confirm="confirmDelete" @cancel="deleteId = null" />
    </AdminLayout>
</template>
