<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'
import FormSelect from '@/Components/Admin/FormSelect.vue'
import ImageUpload from '@/Components/Admin/ImageUpload.vue'

const props = defineProps({ project: Object, services: Array })
const form = useForm({
    _method: 'PUT', title: props.project.title, service_id: props.project.service_id || '',
    short_description: props.project.short_description, description: props.project.description,
    client_name: props.project.client_name || '', project_url: props.project.project_url || '',
    completed_at: props.project.completed_at || '', is_featured: props.project.is_featured,
    is_active: props.project.is_active, sort_order: props.project.sort_order, thumbnail: null,
})
const submit = () => form.post(`/admin/projects/${props.project.id}`)
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Project: {{ project.title }}</h1>
            <Link href="/admin/projects" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link>
        </div>
        <form @submit.prevent="submit" class="bg-white shadow rounded-lg p-6 max-w-3xl space-y-4">
            <FormInput v-model="form.title" label="Title" :error="form.errors.title" required />
            <FormSelect v-model="form.service_id" label="Service" :options="services" value-key="id" label-key="title" placeholder="Select service" />
            <FormTextarea v-model="form.short_description" label="Short Description" :rows="2" required />
            <FormTextarea v-model="form.description" label="Full Description" :rows="8" required />
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
            <ImageUpload label="Thumbnail" :current-image="project.media?.find(m => m.collection_name === 'project_thumbnail')?.original_url" @file-selected="f => form.thumbnail = f" />
            <div class="flex justify-end"><button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Update</button></div>
        </form>
    </AdminLayout>
</template>
