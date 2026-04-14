<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'

const props = defineProps({ page: Object })
const form = useForm({
    title: props.page.title,
    content: props.page.content || {},
    meta_title: props.page.meta_title || '',
    meta_description: props.page.meta_description || '',
})
const submit = () => form.put(`/admin/pages/${props.page.id}`)
</script>
<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-gray-900">Edit Page: {{ page.title }}</h1><Link href="/admin/pages" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link></div>
        <form @submit.prevent="submit" class="space-y-6 max-w-3xl">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <FormInput v-model="form.title" label="Page Title" required />
                <FormInput v-model="form.meta_title" label="SEO Title" />
                <FormTextarea v-model="form.meta_description" label="SEO Description" :rows="2" />
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Page Content (JSON)</h3>
                <textarea v-model="form.content" rows="15" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm" :value="JSON.stringify(form.content, null, 2)" @input="e => { try { form.content = JSON.parse(e.target.value) } catch {} }" />
                <p class="mt-1 text-xs text-gray-400">Edit JSON content for dynamic page sections</p>
            </div>
            <div class="flex justify-end"><button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Update Page</button></div>
        </form>
    </AdminLayout>
</template>
