<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
defineProps({ pages: Array })
</script>
<template>
    <AdminLayout>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Pages</h1>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="page in pages" :key="page.id">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ page.title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">/{{ page.slug }}</td>
                        <td class="px-6 py-4 text-right text-sm"><Link :href="`/admin/pages/${page.id}/edit`" class="text-indigo-600 hover:text-indigo-900">Edit</Link></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
