<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
import DeleteModal from '@/Components/Admin/DeleteModal.vue'

const props = defineProps({
    comments: Object,
    stats: Object,
    filters: Object,
    posts: Array,
})

const search = ref(props.filters?.search || '')
const statusFilter = ref(props.filters?.status || 'all')
const postFilter = ref(props.filters?.post_id || '')
const selected = ref([])
const deleteId = ref(null)
const bulkAction = ref('')

const allSelected = computed(() => {
    return props.comments.data.length > 0 && selected.value.length === props.comments.data.length
})

const toggleAll = () => {
    selected.value = allSelected.value ? [] : props.comments.data.map(c => c.id)
}

const applyFilters = () => {
    const params = {}
    if (search.value) params.search = search.value
    if (statusFilter.value !== 'all') params.status = statusFilter.value
    if (postFilter.value) params.post_id = postFilter.value
    router.get('/admin/comments', params, { preserveState: true })
}

const quickAction = (id, action) => {
    router.patch(`/admin/comments/${id}/${action}`, {}, { preserveScroll: true })
}

const confirmDelete = () => {
    router.delete(`/admin/comments/${deleteId.value}`, {
        preserveScroll: true,
        onSuccess: () => deleteId.value = null,
    })
}

const doBulk = () => {
    if (!bulkAction.value || selected.value.length === 0) return
    if (bulkAction.value === 'delete' && !confirm(`Permanently delete ${selected.value.length} comment(s)?`)) return

    router.post('/admin/comments/bulk', {
        ids: selected.value,
        action: bulkAction.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            selected.value = []
            bulkAction.value = ''
        },
    })
}

const timeAgo = (date) => {
    const s = Math.floor((new Date() - new Date(date)) / 1000)
    if (s < 60) return 'just now'
    const m = Math.floor(s / 60)
    if (m < 60) return `${m}m ago`
    const h = Math.floor(m / 60)
    if (h < 24) return `${h}h ago`
    const d = Math.floor(h / 24)
    if (d < 30) return `${d}d ago`
    return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}

const statusColors = {
    pending: 'bg-amber-100 text-amber-700',
    approved: 'bg-emerald-100 text-emerald-700',
    spam: 'bg-red-100 text-red-700',
    trash: 'bg-gray-100 text-gray-500',
}

const tabs = [
    { key: 'all', label: 'All' },
    { key: 'pending', label: 'Pending' },
    { key: 'approved', label: 'Approved' },
    { key: 'spam', label: 'Spam' },
    { key: 'trash', label: 'Trash' },
]
</script>

<template>
    <AdminLayout>
        <Head><title>Comments - Admin</title></Head>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Comments</h1>
        </div>

        <!-- Status Tabs -->
        <div class="flex flex-wrap gap-1 mb-6 bg-gray-50 rounded-xl p-1">
            <button v-for="tab in tabs" :key="tab.key"
                @click="statusFilter = tab.key; applyFilters()"
                :class="[
                    statusFilter === tab.key ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700',
                    'px-3 py-1.5 rounded-lg text-sm font-medium transition-all'
                ]">
                {{ tab.label }}
                <span v-if="stats[tab.key]" :class="[
                    statusFilter === tab.key ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-200 text-gray-600',
                    'ml-1 text-[10px] px-1.5 py-0.5 rounded-full font-bold'
                ]">{{ stats[tab.key] }}</span>
            </button>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white rounded-xl border border-gray-100 p-4 mb-4 flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search comments, authors..."
                    class="w-full rounded-lg border-gray-200 text-sm px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
            <select v-model="postFilter" @change="applyFilters"
                class="rounded-lg border-gray-200 text-sm px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Posts</option>
                <option v-for="p in posts" :key="p.id" :value="p.id">{{ p.title }}</option>
            </select>
            <button @click="applyFilters" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                Filter
            </button>
        </div>

        <!-- Bulk Actions -->
        <div v-if="selected.length > 0" class="bg-indigo-50 rounded-xl border border-indigo-200 px-4 py-3 mb-4 flex items-center gap-3">
            <span class="text-sm font-medium text-indigo-700">{{ selected.length }} selected</span>
            <select v-model="bulkAction" class="rounded-lg border-indigo-200 text-sm px-3 py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Bulk Action...</option>
                <option value="approve">Approve</option>
                <option value="spam">Mark as Spam</option>
                <option value="trash">Trash</option>
                <option value="delete">Delete Permanently</option>
            </select>
            <button @click="doBulk" :disabled="!bulkAction" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                Apply
            </button>
            <button @click="selected = []" class="text-xs text-indigo-600 hover:text-indigo-800">Clear</button>
        </div>

        <!-- Comments Table -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-10 px-4 py-3">
                                <input type="checkbox" :checked="allSelected" @change="toggleAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="c in comments.data" :key="c.id" :class="[c.status === 'pending' ? 'bg-amber-50/30' : '', 'hover:bg-gray-50 transition']">
                            <td class="px-4 py-3">
                                <input type="checkbox" :value="c.id" v-model="selected" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ c.user?.name || c.author_name || 'Anonymous' }}</div>
                                <div class="text-xs text-gray-400">{{ c.user?.email || c.author_email || '' }}</div>
                                <span v-if="c.is_admin_reply" class="text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-bold mt-0.5 inline-block">ADMIN</span>
                                <span v-if="c.parent_id" class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded font-bold mt-0.5 inline-block ml-1">REPLY</span>
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <p class="text-sm text-gray-700 line-clamp-2">{{ c.body }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <Link v-if="c.post" :href="`/blog/${c.post.slug}`" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 line-clamp-1">
                                    {{ c.post.title }}
                                </Link>
                            </td>
                            <td class="px-4 py-3">
                                <span :class="[statusColors[c.status], 'text-[11px] px-2 py-1 rounded-full font-semibold capitalize']">
                                    {{ c.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">{{ timeAgo(c.created_at) }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button v-if="c.status !== 'approved'" @click="quickAction(c.id, 'approve')"
                                        class="p-1.5 text-emerald-500 hover:bg-emerald-50 rounded-lg transition" title="Approve">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <button v-if="c.status !== 'spam'" @click="quickAction(c.id, 'spam')"
                                        class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Spam">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </button>
                                    <Link :href="`/admin/comments/${c.id}`" class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-lg transition" title="View">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </Link>
                                    <button v-if="c.status === 'trash'" @click="deleteId = c.id"
                                        class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete forever">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <button v-else @click="quickAction(c.id, 'trash')"
                                        class="p-1.5 text-gray-400 hover:bg-gray-100 rounded-lg transition" title="Trash">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!comments.data.length">
                            <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-400">No comments found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :links="comments.links" class="mt-4" />
        <DeleteModal :show="!!deleteId" @confirm="confirmDelete" @cancel="deleteId = null" message="Permanently delete this comment? This cannot be undone." />
    </AdminLayout>
</template>
