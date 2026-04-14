<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'
import ImageUpload from '@/Components/Admin/ImageUpload.vue'
const form = useForm({ name: '', designation: '', bio: '', email: '', phone: '', facebook: '', linkedin: '', sort_order: 0, is_active: true, photo: null })
const submit = () => form.post('/admin/team-members')
</script>
<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-gray-900">Add Team Member</h1><Link href="/admin/team-members" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link></div>
        <form @submit.prevent="submit" class="bg-white shadow rounded-lg p-6 max-w-3xl space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <FormInput v-model="form.name" label="Name" :error="form.errors.name" required />
                <FormInput v-model="form.designation" label="Designation" :error="form.errors.designation" required />
            </div>
            <FormTextarea v-model="form.bio" label="Bio" :rows="3" />
            <div class="grid grid-cols-2 gap-4">
                <FormInput v-model="form.email" label="Email" type="email" />
                <FormInput v-model="form.phone" label="Phone" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <FormInput v-model="form.facebook" label="Facebook URL" />
                <FormInput v-model="form.linkedin" label="LinkedIn URL" />
            </div>
            <ImageUpload label="Photo" @file-selected="f => form.photo = f" />
            <div class="flex items-center gap-4">
                <FormInput v-model="form.sort_order" label="Sort Order" type="number" />
                <label class="flex items-center gap-2 pt-6"><input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-indigo-600" /><span class="text-sm">Active</span></label>
            </div>
            <div class="flex justify-end"><button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Create</button></div>
        </form>
    </AdminLayout>
</template>
