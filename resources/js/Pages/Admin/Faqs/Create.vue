<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'
import FormSelect from '@/Components/Admin/FormSelect.vue'
defineProps({ services: Array })
const form = useForm({ service_id: '', question: '', answer: '', sort_order: 0, is_active: true })
const submit = () => form.post('/admin/faqs')
</script>
<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-gray-900">Create FAQ</h1><Link href="/admin/faqs" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link></div>
        <form @submit.prevent="submit" class="bg-white shadow rounded-lg p-6 max-w-3xl space-y-4">
            <FormSelect v-model="form.service_id" label="Related Service (optional)" :options="services" value-key="id" label-key="title" placeholder="General (no service)" />
            <FormInput v-model="form.question" label="Question" :error="form.errors.question" required />
            <FormTextarea v-model="form.answer" label="Answer" :rows="5" :error="form.errors.answer" required />
            <div class="flex items-center gap-4"><FormInput v-model="form.sort_order" label="Sort Order" type="number" /><label class="flex items-center gap-2 pt-6"><input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-indigo-600" /><span class="text-sm">Active</span></label></div>
            <div class="flex justify-end"><button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Create</button></div>
        </form>
    </AdminLayout>
</template>
