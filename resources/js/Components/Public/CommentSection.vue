<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import CommentItem from './CommentItem.vue'
import Pagination from '@/Components/Admin/Pagination.vue'

const props = defineProps({
    postSlug: String,
    comments: Object,       // paginated
    commentsCount: Number,
    settings: Object,        // { enabled, allow_guests, require_approval, max_depth }
})

const page = usePage()
const auth = page.props.auth
const isGuest = computed(() => !auth?.user)

const form = useForm({
    body: '',
    parent_id: null,
    author_name: '',
    author_email: '',
    website: '',        // Honeypot — must stay empty
    _loaded_at: '',     // Time trap — records when form loaded
})

// Set timestamp when component mounts
onMounted(() => {
    form._loaded_at = Math.floor(Date.now() / 1000).toString()
})

const submitComment = () => {
    form.post(`/blog/${props.postSlug}/comments`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            // Reset timestamp for next comment
            form._loaded_at = Math.floor(Date.now() / 1000).toString()
        },
    })
}
</script>

<template>
    <section id="comments" class="mt-12 pt-10 border-t border-gray-100">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                Comments
                <span v-if="commentsCount > 0" class="text-base font-normal text-gray-400">({{ commentsCount }})</span>
            </h2>
        </div>

        <!-- Comments disabled message -->
        <div v-if="!settings.enabled" class="text-center py-8 text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            <p class="text-sm">Comments are disabled for this post.</p>
        </div>

        <template v-else>
            <!-- New Comment Form -->
            <div class="mb-10">
                <div v-if="isGuest && !settings.allow_guests" class="bg-gray-50 rounded-2xl p-6 text-center">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-sm text-gray-500 mb-3">Please log in to leave a comment.</p>
                    <Link href="/login" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Log In
                    </Link>
                </div>

                <form v-else @submit.prevent="submitComment" class="bg-white rounded-2xl border border-gray-100 p-5 sm:p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        Leave a Comment
                    </h3>

                    <!-- ═══ HONEYPOT — invisible to humans, bots fill it ═══ -->
                    <div class="absolute opacity-0 -z-50 h-0 w-0 overflow-hidden" aria-hidden="true" tabindex="-1">
                        <label for="website">Website</label>
                        <input v-model="form.website" type="text" id="website" name="website" autocomplete="off" tabindex="-1" />
                    </div>

                    <!-- Hidden timestamp for time trap -->
                    <input type="hidden" v-model="form._loaded_at" />

                    <!-- Logged-in user info -->
                    <div v-if="auth?.user" class="flex items-center gap-2 mb-4 text-sm text-gray-500">
                        <div class="w-6 h-6 rounded-full bg-brand-100 flex items-center justify-center">
                            <span class="text-[10px] font-bold text-brand-600">{{ auth.user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        Commenting as <span class="font-medium text-gray-700">{{ auth.user.name }}</span>
                    </div>

                    <!-- Guest fields -->
                    <div v-if="isGuest" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Name *</label>
                            <input v-model="form.author_name" type="text" placeholder="Your name"
                                class="w-full rounded-lg border-gray-200 text-sm px-3 py-2.5 focus:border-brand-500 focus:ring-brand-500" />
                            <p v-if="form.errors.author_name" class="text-xs text-red-500 mt-1">{{ form.errors.author_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Email *</label>
                            <input v-model="form.author_email" type="email" placeholder="your@email.com"
                                class="w-full rounded-lg border-gray-200 text-sm px-3 py-2.5 focus:border-brand-500 focus:ring-brand-500" />
                            <p v-if="form.errors.author_email" class="text-xs text-red-500 mt-1">{{ form.errors.author_email }}</p>
                            <p class="text-[11px] text-gray-400 mt-1">Your email won't be published.</p>
                        </div>
                    </div>

                    <!-- Comment body -->
                    <div class="mb-4">
                        <textarea v-model="form.body" rows="4" placeholder="Share your thoughts..."
                            class="w-full rounded-lg border-gray-200 text-sm px-3 py-2.5 focus:border-brand-500 focus:ring-brand-500 resize-none"></textarea>
                        <p v-if="form.errors.body" class="text-xs text-red-500 mt-1">{{ form.errors.body }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <p v-if="settings.require_approval && !auth?.user?.is_admin" class="text-[11px] text-gray-400">
                            Comments are moderated and may take a moment to appear.
                        </p>
                        <span v-else></span>
                        <button type="submit" :disabled="form.processing || !form.body.trim()"
                            class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-50 transition flex items-center gap-2">
                            <svg v-if="form.processing" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            {{ form.processing ? 'Posting...' : 'Post Comment' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Comments List -->
            <div v-if="comments?.data?.length" class="divide-y divide-gray-50">
                <CommentItem
                    v-for="comment in comments.data"
                    :key="comment.id"
                    :comment="comment"
                    :post-slug="postSlug"
                    :depth="0"
                    :max-depth="settings.max_depth"
                    :allow-guests="settings.allow_guests"
                    :require-approval="settings.require_approval"
                />
            </div>

            <!-- Empty state -->
            <div v-else-if="commentsCount === 0" class="text-center py-10">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-sm text-gray-400">No comments yet. Be the first to share your thoughts!</p>
            </div>

            <!-- Pagination -->
            <Pagination v-if="comments?.links?.length > 3" :links="comments.links" class="mt-6" />
        </template>
    </section>
</template>