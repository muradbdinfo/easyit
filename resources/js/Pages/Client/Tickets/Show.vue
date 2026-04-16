<script setup>
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
const props = defineProps({ ticket: Object })
const reply = useForm({ body: '' })
const rate = useForm({ rating: 5, feedback: '' })
const submitReply = () => reply.post(`/client/tickets/${props.ticket.id}/reply`, { onSuccess: () => reply.reset() })
const submitRate = () => rate.post(`/client/tickets/${props.ticket.id}/rate`)
const formatTime = (d) => new Date(d).toLocaleString('en', { month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' })
</script>
<template>
    <ClientLayout>
        <div class="flex items-center justify-between mb-6">
            <div>
                <Link href="/client/tickets" class="text-xs text-gray-400 hover:text-gray-600">← Tickets</Link>
                <h1 class="text-lg font-bold text-gray-900 mt-1">{{ ticket.subject }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-400 font-mono">{{ ticket.ticket_number }}</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full capitalize" :class="{'bg-blue-100 text-blue-700':ticket.status==='open','bg-yellow-100 text-yellow-700':ticket.status==='in_progress','bg-green-100 text-green-700':ticket.status==='resolved','bg-gray-100 text-gray-600':ticket.status==='closed'}">{{ ticket.status?.replace(/_/g,' ') }}</span>
                    <span class="text-[10px] font-bold uppercase" :class="{'text-red-600':ticket.priority==='urgent','text-orange-600':ticket.priority==='high'}">{{ ticket.priority }}</span>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="space-y-4 mb-6 max-w-3xl">
            <div v-for="msg in ticket.messages" :key="msg.id"
                :class="[msg.is_admin_reply ? 'bg-brand-50 border-brand-100' : 'bg-white border-gray-100', 'rounded-2xl border p-5']">
                <div class="flex items-center gap-2 mb-2">
                    <div :class="[msg.is_admin_reply ? 'bg-brand-600' : 'bg-gray-400', 'w-7 h-7 rounded-full flex items-center justify-center']">
                        <span class="text-white text-xs font-bold">{{ msg.user?.name?.charAt(0) }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-gray-900">{{ msg.user?.name }}</span>
                        <span v-if="msg.is_admin_reply" class="text-[10px] bg-brand-100 text-brand-700 px-1.5 py-0.5 rounded ml-1 font-bold">STAFF</span>
                    </div>
                    <span class="text-xs text-gray-400 ml-auto">{{ formatTime(msg.created_at) }}</span>
                </div>
                <div class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ msg.body }}</div>
                <div v-if="msg.attachments?.length" class="mt-3 flex flex-wrap gap-2">
                    <a v-for="(att, i) in msg.attachments" :key="i" :href="`/storage/${att}`" target="_blank" class="text-xs text-brand-600 bg-brand-50 px-2 py-1 rounded hover:underline">📎 Attachment {{ i+1 }}</a>
                </div>
            </div>
        </div>

        <!-- Reply form -->
        <div v-if="ticket.status !== 'closed'" class="max-w-3xl">
            <form @submit.prevent="submitReply" class="bg-white rounded-2xl border border-gray-100 p-5">
                <label class="block text-sm font-semibold text-gray-900 mb-2">Reply</label>
                <textarea v-model="reply.body" rows="4" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Type your reply..."></textarea>
                <div class="flex justify-end mt-3">
                    <button type="submit" :disabled="reply.processing || !reply.body.trim()" class="rounded-xl bg-brand-600 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-50 transition">Send Reply</button>
                </div>
            </form>
        </div>

        <!-- Rating (resolved tickets) -->
        <div v-if="ticket.status === 'resolved' && !ticket.satisfaction_rating" class="max-w-3xl mt-6">
            <form @submit.prevent="submitRate" class="bg-amber-50 rounded-2xl border border-amber-200 p-5">
                <h3 class="font-semibold text-gray-900 mb-3">Rate this support experience</h3>
                <div class="flex gap-1 mb-3">
                    <button v-for="i in 5" :key="i" type="button" @click="rate.rating = i" class="text-2xl transition hover:scale-110" :class="i <= rate.rating ? 'text-amber-400' : 'text-gray-300'">★</button>
                </div>
                <textarea v-model="rate.feedback" rows="2" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Feedback (optional)"></textarea>
                <button type="submit" class="mt-3 rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white hover:bg-amber-600 transition">Submit Rating</button>
            </form>
        </div>
    </ClientLayout>
</template>
