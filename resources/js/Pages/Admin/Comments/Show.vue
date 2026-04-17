<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ comment: Object })

const replyForm = useForm({ body: '' })

const submitReply = () => {
    replyForm.post(`/admin/comments/${props.comment.id}/reply`, {
        preserveScroll: true,
        onSuccess: () => replyForm.reset(),
    })
}

const quickAction = (action) => {
    router.patch(`/admin/comments/${props.comment.id}/${action}`, {}, { preserveScroll: true })
}

const deleteComment = () => {
    if (!confirm('Permanently delete this comment?')) return
    router.delete(`/admin/comments/${props.comment.id}`, {
        onSuccess: () => router.visit('/admin/comments'),
    })
}

const formatDate = (d) => new Date(d).toLocaleString('en-US', {
    month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'
})

const statusColors = {
    pending: 'bg-amber-100 text-amber-700 border-amber-200',
    approved: 'bg-emerald-100 text-emerald-700 border-emerald-200',
    spam: 'bg-red-100 text-red-700 border-red-200',
    trash: 'bg-gray-100 text-gray-500 border-gray-200',
}
</script>

<template>
    <AdminLayout>
        <Head><title>Comment #{{ comment.id }} - Admin</title></Head>

        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <Link href="/admin/comments" class="p-1.5 rounded-lg hover:bg-gray-100 transition text-gray-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </Link>
            <h1 class="text-xl font-bold text-gray-900">Comment #{{ comment.id }}</h1>
            <span :class="[statusColors[comment.status], 'text-xs px-2.5 py-1 rounded-full font-semibold capitalize border']">
                {{ comment.status }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Comment Body -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <div :class="[comment.is_admin_reply ? 'bg-indigo-600' : 'bg-gray-400', 'w-10 h-10 rounded-full flex items-center justify-center shrink-0']">
                            <span class="text-white text-sm font-bold">{{ (comment.user?.name || comment.author_name || 'A').charAt(0).toUpperCase() }}</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-900">{{ comment.user?.name || comment.author_name || 'Anonymous' }}</span>
                                <span v-if="comment.is_admin_reply" class="text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-bold">ADMIN</span>
                            </div>
                            <div class="text-xs text-gray-400">{{ formatDate(comment.created_at) }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap bg-gray-50 rounded-xl p-4">{{ comment.body }}</div>
                </div>

                <!-- In Reply To -->
                <div v-if="comment.parent" class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        In Reply To
                    </h3>
                    <div class="text-sm text-gray-600 bg-gray-50 rounded-xl p-4 border-l-4 border-gray-200">
                        <span class="text-xs font-medium text-gray-400 block mb-1">{{ comment.parent.user?.name || comment.parent.author_name || 'Anonymous' }}</span>
                        {{ comment.parent.body }}
                    </div>
                </div>

                <!-- Replies -->
                <div v-if="comment.replies?.length" class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Replies ({{ comment.replies.length }})</h3>
                    <div class="space-y-4">
                        <div v-for="reply in comment.replies" :key="reply.id" class="flex gap-3 p-3 rounded-xl bg-gray-50">
                            <div :class="[reply.is_admin_reply ? 'bg-indigo-600' : 'bg-gray-400', 'w-7 h-7 rounded-full flex items-center justify-center shrink-0']">
                                <span class="text-white text-[10px] font-bold">{{ (reply.user?.name || 'A').charAt(0).toUpperCase() }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-gray-900">{{ reply.user?.name || reply.author_name || 'Anonymous' }}</span>
                                    <span v-if="reply.is_admin_reply" class="text-[9px] bg-indigo-100 text-indigo-700 px-1 py-0.5 rounded font-bold">ADMIN</span>
                                    <span class="text-[10px] text-gray-400">{{ formatDate(reply.created_at) }}</span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">{{ reply.body }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Reply -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Reply as Admin</h3>
                    <form @submit.prevent="submitReply">
                        <textarea v-model="replyForm.body" rows="3" placeholder="Write your reply..."
                            class="w-full rounded-lg border-gray-200 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                        <p v-if="replyForm.errors.body" class="text-xs text-red-500 mt-1">{{ replyForm.errors.body }}</p>
                        <div class="flex justify-end mt-3">
                            <button type="submit" :disabled="replyForm.processing || !replyForm.body.trim()"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                                {{ replyForm.processing ? 'Posting...' : 'Post Reply' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-2">
                        <button v-if="comment.status !== 'approved'" @click="quickAction('approve')"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Approve
                        </button>
                        <button v-if="comment.status !== 'pending'" @click="quickAction('restore')"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Set Pending
                        </button>
                        <button v-if="comment.status !== 'spam'" @click="quickAction('spam')"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Mark Spam
                        </button>
                        <button v-if="comment.status !== 'trash'" @click="quickAction('trash')"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Trash
                        </button>
                        <button @click="deleteComment"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete Forever
                        </button>
                    </div>
                </div>

                <!-- Meta Info -->
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Details</h3>
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-gray-400 font-medium">Post</dt>
                            <dd>
                                <Link v-if="comment.post" :href="`/blog/${comment.post.slug}`" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                    {{ comment.post.title }}
                                </Link>
                            </dd>
                        </div>
                        <div v-if="comment.author_email">
                            <dt class="text-xs text-gray-400 font-medium">Email</dt>
                            <dd class="text-gray-700">{{ comment.author_email }}</dd>
                        </div>
                        <div v-if="comment.user?.email">
                            <dt class="text-xs text-gray-400 font-medium">User Email</dt>
                            <dd class="text-gray-700">{{ comment.user.email }}</dd>
                        </div>
                        <div v-if="comment.ip_address">
                            <dt class="text-xs text-gray-400 font-medium">IP Address</dt>
                            <dd class="text-gray-700 font-mono text-xs">{{ comment.ip_address }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-400 font-medium">Submitted</dt>
                            <dd class="text-gray-700">{{ formatDate(comment.created_at) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
