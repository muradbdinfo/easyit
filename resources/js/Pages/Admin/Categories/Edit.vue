<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'

const props = defineProps({ category: Object })
const form = useForm({ name: props.category.name, description: props.category.description || '' })
const submit = () => form.put(`/admin/categories/${props.category.id}`)
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Category: {{ category.name }}</h1>
            <Link href="/admin/categories" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link>
        </div>
        <form @submit.prevent="submit" class="bg-white shadow rounded-lg p-6 max-w-xl space-y-4">
            <FormInput v-model="form.name" label="Name" :error="form.errors.name" required />
            <FormTextarea v-model="form.description" label="Description" :error="form.errors.description" :rows="3" />
            <div class="flex justify-end">
                <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Update</button>
            </div>
        </form>
    </AdminLayout>
</template>
