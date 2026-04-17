<script setup>
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
    comment: Object,
    postSlug: String,
    depth: { type: Number, default: 0 },
    maxDepth: { type: Number, default: 3 },
    allowGuests: { type: Boolean, default: true },
    requireApproval: { type: Boolean, default: true },
})

const page = usePage()
const auth = page.props.auth
const showReply = ref(false)
const showReplies = ref(true)

const replyForm = useForm({
    body: '',
    parent_id: props.comment.id,
    author_name: '',
    author_email: '',
    website: '',        // Honeypot
    _loaded_at: '',     // Time trap
})

const openReply = () => {
    showReply.value = !showReply.value
    if (showReply.value) {
        // Set timestamp when reply form opens
        replyForm._loaded_at = Math.floor(Date.now() / 1000).toString()
    }
}

const submitReply = () => {
    replyForm.post(`/blog/${props.postSlug}/comments`, {
        preserveScroll: true,
        onSuccess: () => {
            replyForm.reset()
            replyForm.parent_id = props.comment.id
            showReply.value = false
        },
    })
}

const timeAgo = (date) => {
    const seconds = Math.floor((new Date() - new Date(date)) / 1000)
    if (seconds < 60) return 'just now'
    const minutes = Math.floor(seconds / 60)
    if (minutes < 60) return `${minutes}m ago`
    const hours = Math.floor(minutes / 60)
    if (hours < 24) return `${hours}h ago`
    const days = Math.floor(hours / 24)
    if (days < 30) return `${days}d ago`
    return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const avatarColors = [
    'bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500',
    'bg-cyan-500', 'bg-violet-500', 'bg-pink-500', 'bg-teal-500',
]
const getColor = (name) => avatarColors[(name || '').charCodeAt(0) % avatarColors.length]
const displayName = props.comment.user?.name || props.comment.author_name || 'Anonymous'
const initial = displayName.charAt(0).toUpperCase()
</script>

<template>
    <div :class="['group', depth > 0 ? 'ml-6 sm:ml-10 pl-4 sm:pl-5 border-l-2 border-gray-100' : '']">
        <div class="py-4">
            <!-- Header -->
            <div class="flex items-start gap-3">
                <div :class="[comment.is_admin_reply ? 'bg-brand-600' : getColor(displayName), 'w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center shrink-0']">
                    <span class="text-white text-xs sm:text-sm font-bold">{{ initial }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-semibold text-gray-900">{{ displayName }}</span>
                        <span v-if="comment.is_admin_reply" class="text-[10px] bg-brand-100 text-brand-700 px-1.5 py-0.5 rounded font-bold">ADMIN</span>
                        <span class="text-xs text-gray-400">{{ timeAgo(comment.created_at) }}</span>
                    </div>

                    <!-- Body -->
                    <div class="mt-1.5 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ comment.body }}</div>

                    <!-- Actions -->
                    <div class="mt-2 flex items-center gap-3">
                        <button
                            v-if="depth < maxDepth - 1 && (auth?.user || allowGuests)"
                            @click="openReply"
                            class="text-xs font-medium text-gray-400 hover:text-brand-600 transition flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            {{ showReply ? 'Cancel' : 'Reply' }}
                        </button>
                    </div>

                    <!-- Reply Form -->
                    <transition enter-active-class="transition duration-200" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                        <div v-if="showReply" class="mt-3">
                            <form @submit.prevent="submitReply" class="space-y-3">
                                <!-- Honeypot -->
                                <div class="absolute opacity-0 -z-50 h-0 w-0 overflow-hidden" aria-hidden="true" tabindex="-1">
                                    <input v-model="replyForm.website" type="text" name="website" autocomplete="off" tabindex="-1" />
                                </div>
                                <input type="hidden" v-model="replyForm._loaded_at" />

                                <!-- Guest fields -->
                                <div v-if="!auth?.user" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <input v-model="replyForm.author_name" type="text" placeholder="Your name *"
                                            class="w-full rounded-lg border-gray-200 text-sm px-3 py-2 focus:border-brand-500 focus:ring-brand-500" />
                                        <p v-if="replyForm.errors.author_name" class="text-xs text-red-500 mt-1">{{ replyForm.errors.author_name }}</p>
                                    </div>
                                    <div>
                                        <input v-model="replyForm.author_email" type="email" placeholder="Your email *"
                                            class="w-full rounded-lg border-gray-200 text-sm px-3 py-2 focus:border-brand-500 focus:ring-brand-500" />
                                        <p v-if="replyForm.errors.author_email" class="text-xs text-red-500 mt-1">{{ replyForm.errors.author_email }}</p>
                                    </div>
                                </div>
                                <div>
                                    <textarea v-model="replyForm.body" rows="3" placeholder="Write a reply..."
                                        class="w-full rounded-lg border-gray-200 text-sm px-3 py-2 focus:border-brand-500 focus:ring-brand-500 resize-none"></textarea>
                                    <p v-if="replyForm.errors.body" class="text-xs text-red-500 mt-1">{{ replyForm.errors.body }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="submit" :disabled="replyForm.processing || !replyForm.body.trim()"
                                        class="rounded-lg bg-brand-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-brand-700 disabled:opacity-50 transition">
                                        {{ replyForm.processing ? 'Posting...' : 'Post Reply' }}
                                    </button>
                                    <button type="button" @click="showReply = false" class="text-xs text-gray-400 hover:text-gray-600">Cancel</button>
                                </div>
                                <p v-if="requireApproval && !auth?.user?.is_admin" class="text-[11px] text-gray-400">Your reply will appear after approval.</p>
                            </form>
                        </div>
                    </transition>
                </div>
            </div>
        </div>

        <!-- Nested Replies -->
        <div v-if="comment.approved_replies?.length">
            <button v-if="comment.approved_replies.length > 0" @click="showReplies = !showReplies"
                class="text-xs text-gray-400 hover:text-brand-600 transition mb-1 flex items-center gap-1">
                <svg :class="['w-3 h-3 transition-transform', showReplies ? 'rotate-90' : '']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                {{ showReplies ? 'Hide' : 'Show' }} {{ comment.approved_replies.length }} {{ comment.approved_replies.length === 1 ? 'reply' : 'replies' }}
            </button>
            <transition enter-active-class="transition duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100">
                <div v-if="showReplies">
                    <CommentItem
                        v-for="reply in comment.approved_replies"
                        :key="reply.id"
                        :comment="reply"
                        :post-slug="postSlug"
                        :depth="depth + 1"
                        :max-depth="maxDepth"
                        :allow-guests="allowGuests"
                        :require-approval="requireApproval"
                    />
                </div>
            </transition>
        </div>
    </div>
</template>