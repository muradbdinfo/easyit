<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'
import FormSelect from '@/Components/Admin/FormSelect.vue'
import ImageUpload from '@/Components/Admin/ImageUpload.vue'

defineProps({ services: Array })
const form = useForm({
    title: '', service_id: '', short_description: '', description: '',
    client_name: '', project_url: '', completed_at: '',
    is_featured: false, is_active: true, sort_order: 0, thumbnail: null,
})
const submit = () => form.post('/admin/projects')
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create Project</h1>
            <Link href="/admin/projects" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link>
        </div>
        <form @submit.prevent="submit" class="bg-white shadow rounded-lg p-6 max-w-3xl space-y-4">
            <FormInput v-model="form.title" label="Title" :error="form.errors.title" required />
            <FormSelect v-model="form.service_id" label="Service" :options="services" value-key="id" label-key="title" placeholder="Select service" />
            <FormTextarea v-model="form.short_description" label="Short Description" :rows="2" :error="form.errors.short_description" required />
            <FormTextarea v-model="form.description" label="Full Description" :rows="8" :error="form.errors.description" required />
            <div class="grid grid-cols-2 gap-4">
                <FormInput v-model="form.client_name" label="Client Name" />
                <FormInput v-model="form.project_url" label="Project URL" type="url" />
            </div>
            <div class="grid grid-cols-3 gap-4">
                <FormInput v-model="form.completed_at" label="Completed Date" type="date" />
                <FormInput v-model="form.sort_order" label="Sort Order" type="number" />
                <div class="flex items-center gap-4 pt-6">
                    <label class="flex items-center gap-2"><input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-indigo-600" /><span class="text-sm">Active</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" v-model="form.is_featured" class="rounded border-gray-300 text-indigo-600" /><span class="text-sm">Featured</span></label>
                </div>
            </div>
            <ImageUpload label="Thumbnail" :error="form.errors.thumbnail" @file-selected="f => form.thumbnail = f" />
            <div class="flex justify-end"><button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Create</button></div>
        </form>
    </AdminLayout>
</template>
