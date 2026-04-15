<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormTextarea from '@/Components/Admin/FormTextarea.vue'
import FormSelect from '@/Components/Admin/FormSelect.vue'
import ImageUpload from '@/Components/Admin/ImageUpload.vue'
import RichTextEditor from '@/Components/Admin/RichTextEditor.vue'

const props = defineProps({ post: Object, categories: Array, tags: Array })

const form = useForm({
    _method: 'PUT', title: props.post.title, category_id: props.post.category_id || '',
    excerpt: props.post.excerpt || '', body: props.post.body, status: props.post.status,
    published_at: props.post.published_at || '', featured_image: null,
    tags: props.post.tags?.map(t => t.id) || [],
})

const toggleTag = (id) => {
    const i = form.tags.indexOf(id)
    i > -1 ? form.tags.splice(i, 1) : form.tags.push(id)
}

const submit = () => form.post(`/admin/posts/${props.post.id}`)
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Post: {{ post.title }}</h1>
            <Link href="/admin/posts" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link>
        </div>
        <form @submit.prevent="submit" class="space-y-6 max-w-4xl">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <FormInput v-model="form.title" label="Title" :error="form.errors.title" required />
                <div class="grid grid-cols-2 gap-4">
                    <FormSelect v-model="form.category_id" label="Category" :options="categories" placeholder="Select category" />
                    <FormSelect v-model="form.status" label="Status" :options="[{id:'draft',name:'Draft'},{id:'published',name:'Published'}]" />
                </div>
                <FormTextarea v-model="form.excerpt" label="Excerpt" :rows="2" />
                <RichTextEditor v-model="form.body" label="Body" :error="form.errors.body" required />
                <ImageUpload label="Featured Image" :current-image="post.featured_image_url" :error="form.errors.featured_image" @file-selected="file => form.featured_image = file" />
            </div>

            <div v-if="tags?.length" class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    <button v-for="tag in tags" :key="tag.id" type="button" @click="toggleTag(tag.id)"
                        :class="[form.tags.includes(tag.id) ? 'bg-indigo-100 text-indigo-800 border-indigo-300' : 'bg-gray-100 text-gray-700 border-gray-300', 'rounded-full border px-3 py-1 text-sm']">
                        {{ tag.name }}
                    </button>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                    {{ form.processing ? 'Saving...' : 'Update Post' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
