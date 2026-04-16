<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
defineProps({ categories: Array, services: Array })
const form = useForm({ ticket_category_id:'', client_service_id:'', subject:'', priority:'medium', message:'', attachments:[] })
const submit = () => form.post('/client/tickets')
</script>
<template>
    <ClientLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-gray-900">New Ticket</h1>
            <Link href="/client/tickets" class="text-sm text-gray-500">← Back</Link>
        </div>
        <form @submit.prevent="submit" class="bg-white rounded-2xl border border-gray-100 p-6 max-w-2xl space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select v-model="form.ticket_category_id" class="w-full rounded-lg border-gray-300 text-sm"><option value="">Select</option><option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option></select>
                    <p v-if="form.errors.ticket_category_id" class="text-xs text-red-600 mt-1">{{ form.errors.ticket_category_id }}</p>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Related Service</label>
                    <select v-model="form.client_service_id" class="w-full rounded-lg border-gray-300 text-sm"><option value="">None</option><option v-for="s in services" :key="s.id" :value="s.id">{{ s.label }}</option></select>
                </div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Subject *</label>
                <input v-model="form.subject" class="w-full rounded-lg border-gray-300 text-sm" /><p v-if="form.errors.subject" class="text-xs text-red-600 mt-1">{{ form.errors.subject }}</p>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <div class="flex gap-3">
                    <label v-for="p in ['low','medium','high','urgent']" :key="p" class="flex items-center gap-1.5">
                        <input type="radio" v-model="form.priority" :value="p" class="text-brand-600" /><span class="text-sm capitalize">{{ p }}</span>
                    </label>
                </div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                <textarea v-model="form.message" rows="6" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Describe your issue in detail..."></textarea>
                <p v-if="form.errors.message" class="text-xs text-red-600 mt-1">{{ form.errors.message }}</p>
            </div>
            <button type="submit" :disabled="form.processing" class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-50 transition">
                {{ form.processing ? 'Submitting...' : 'Submit Ticket' }}
            </button>
        </form>
    </ClientLayout>
</template>
