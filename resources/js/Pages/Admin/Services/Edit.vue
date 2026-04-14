<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'
import ImageUpload from '@/Components/Admin/ImageUpload.vue'

const props = defineProps({ service: Object })

const form = useForm({
    _method: 'PUT',
    title: props.service.title,
    short_description: props.service.short_description,
    description: props.service.description,
    icon: props.service.icon || '',
    is_featured: props.service.is_featured,
    is_active: props.service.is_active,
    sort_order: props.service.sort_order,
    image: null,
    features: props.service.features?.map(f => ({ id: f.id, title: f.title, description: f.description || '' })) || [],
})

const addFeature = () => form.features.push({ id: null, title: '', description: '' })
const removeFeature = (i) => form.features.splice(i, 1)

const submit = () => {
    form.post(`/admin/services/${props.service.id}`)
}
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Service: {{ service.title }}</h1>
            <Link href="/admin/services" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link>
        </div>

        <form @submit.prevent="submit" class="space-y-6 max-w-3xl">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <FormInput v-model="form.title" label="Title" :error="form.errors.title" required />
                <FormTextarea v-model="form.short_description" label="Short Description" :error="form.errors.short_description" :rows="2" required />
                <FormTextarea v-model="form.description" label="Full Description (HTML)" :error="form.errors.description" :rows="8" required />
                <FormInput v-model="form.icon" label="Icon Name" :error="form.errors.icon" />
                <ImageUpload label="Service Image" :current-image="service.media?.[0]?.original_url" :error="form.errors.image" @file-selected="file => form.image = file" />

                <div class="grid grid-cols-2 gap-4">
                    <FormInput v-model="form.sort_order" label="Sort Order" type="number" />
                    <div class="flex items-center gap-6 pt-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-indigo-600" />
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.is_featured" class="rounded border-gray-300 text-indigo-600" />
                            <span class="text-sm text-gray-700">Featured</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Service Features</h3>
                    <button type="button" @click="addFeature" class="text-sm text-indigo-600 hover:text-indigo-800">+ Add Feature</button>
                </div>
                <div v-for="(feature, i) in form.features" :key="i" class="flex gap-3 mb-3 items-start">
                    <div class="flex-1">
                        <input v-model="feature.title" placeholder="Feature title" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                    </div>
                    <div class="flex-1">
                        <input v-model="feature.description" placeholder="Description (optional)" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                    </div>
                    <button type="button" @click="removeFeature(i)" class="text-red-500 hover:text-red-700 pt-2">✕</button>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                    {{ form.processing ? 'Saving...' : 'Update Service' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
