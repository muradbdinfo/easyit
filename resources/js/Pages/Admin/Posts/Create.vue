<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'
import FormSelect from '@/Components/Admin/FormSelect.vue'
import ImageUpload from '@/Components/Admin/ImageUpload.vue'

defineProps({ categories: Array, tags: Array })

const form = useForm({
    title: '', category_id: '', excerpt: '', body: '',
    status: 'draft', published_at: '', featured_image: null, tags: [],
})

const toggleTag = (id) => {
    const i = form.tags.indexOf(id)
    i > -1 ? form.tags.splice(i, 1) : form.tags.push(id)
}

const submit = () => form.post('/admin/posts')
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create Post</h1>
            <Link href="/admin/posts" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link>
        </div>
        <form @submit.prevent="submit" class="space-y-6 max-w-4xl">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <FormInput v-model="form.title" label="Title" :error="form.errors.title" required />
                <div class="grid grid-cols-2 gap-4">
                    <FormSelect v-model="form.category_id" label="Category" :options="categories" placeholder="Select category" :error="form.errors.category_id" />
                    <FormSelect v-model="form.status" label="Status" :options="[{id:'draft',name:'Draft'},{id:'published',name:'Published'}]" :error="form.errors.status" />
                </div>
                <FormTextarea v-model="form.excerpt" label="Excerpt" :rows="2" :error="form.errors.excerpt" />
                <FormTextarea v-model="form.body" label="Body (HTML Content)" :rows="15" :error="form.errors.body" required />
                <ImageUpload label="Featured Image" :error="form.errors.featured_image" @file-selected="file => form.featured_image = file" />
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    <button v-for="tag in tags" :key="tag.id" type="button" @click="toggleTag(tag.id)"
                        :class="[form.tags.includes(tag.id) ? 'bg-indigo-100 text-indigo-800 border-indigo-300' : 'bg-gray-100 text-gray-700 border-gray-300', 'rounded-full border px-3 py-1 text-sm']">
                        {{ tag.name }}
                    </button>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Create Post</button>
            </div>
        </form>
    </AdminLayout>
</template>
