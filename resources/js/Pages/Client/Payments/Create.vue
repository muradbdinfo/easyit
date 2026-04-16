<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
const props = defineProps({ invoice: Object, paymentMethods: Array })
const form = useForm({ amount: props.invoice.total, method: 'bkash', transaction_id: '', proof_image: null })
const submit = () => form.post(`/client/invoices/${props.invoice.id}/pay`)
</script>
<template>
    <ClientLayout>
        <div class="flex items-center justify-between mb-6">
            <div>
                <Link :href="`/client/invoices/${invoice.id}`" class="text-xs text-gray-400 hover:text-gray-600">← Back to Invoice</Link>
                <h1 class="text-lg font-bold text-gray-900 mt-1">Pay Invoice {{ invoice.invoice_number }}</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-4xl">
            <!-- Payment Methods -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 text-sm">Send payment to:</h3>
                <div v-for="pm in paymentMethods" :key="pm.value" @click="form.method = pm.value"
                    :class="[form.method === pm.value ? 'border-brand-500 bg-brand-50 ring-1 ring-brand-500' : 'border-gray-200 hover:border-gray-300', 'border rounded-xl p-4 cursor-pointer transition']">
                    <p class="font-semibold text-gray-900">{{ pm.label }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ pm.number || pm.details || 'Contact us for details' }}</p>
                </div>
            </div>

            <!-- Payment Form -->
            <form @submit.prevent="submit" class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-sm text-gray-500">Amount Due</p>
                    <p class="text-3xl font-bold text-gray-900">৳{{ Number(invoice.total).toLocaleString() }}</p>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Amount *</label>
                    <input v-model="form.amount" type="number" step="0.01" class="w-full rounded-lg border-gray-300 text-sm" />
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                    <input v-model="form.transaction_id" class="w-full rounded-lg border-gray-300 text-sm" placeholder="e.g. TXN123456" />
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Payment Proof (Screenshot) *</label>
                    <input type="file" accept="image/*" @change="e => form.proof_image = e.target.files[0]" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />
                    <p v-if="form.errors.proof_image" class="text-xs text-red-600 mt-1">{{ form.errors.proof_image }}</p>
                </div>
                <button type="submit" :disabled="form.processing" class="w-full rounded-xl bg-brand-600 py-3 text-sm font-bold text-white hover:bg-brand-700 disabled:opacity-50 transition">
                    {{ form.processing ? 'Submitting...' : 'Submit Payment Proof' }}
                </button>
            </form>
        </div>
    </ClientLayout>
</template>
