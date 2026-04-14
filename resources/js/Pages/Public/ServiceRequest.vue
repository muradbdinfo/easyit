<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ services: Array })
const form = useForm({ service_id: '', name: '', email: '', phone: '', company: '', requirements: '', budget_range: '', urgency: 'medium' })
const submit = () => form.post('/service-request', { onSuccess: () => form.reset() })
const budgetOptions = ['Under ৳10,000', '৳10,000 - ৳25,000', '৳25,000 - ৳50,000', '৳50,000 - ৳1,00,000', 'Above ৳1,00,000', 'Flexible / Not Sure']
</script>
<template>
    <AppLayout>
        <Head><title>Request a Service - Easy IT</title></Head>
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold">Request a Service</h1>
                <p class="mt-4 text-lg text-gray-300">Tell us about your project and we'll get back to you with a quote</p>
            </div>
        </section>
        <section class="py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8 shadow-sm space-y-5">
                    <!-- Service -->
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Service Needed</label>
                        <select v-model="form.service_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select a service (optional)</option>
                            <option v-for="s in services" :key="s.id" :value="s.id">{{ s.title }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Your Name *</label><input v-model="form.name" type="text" :class="['block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', form.errors.name ? 'border-red-300' : '']" /><p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Email *</label><input v-model="form.email" type="email" :class="['block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', form.errors.email ? 'border-red-300' : '']" /><p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label><input v-model="form.phone" type="tel" :class="['block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', form.errors.phone ? 'border-red-300' : '']" /><p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Company</label><input v-model="form.company" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" /></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Requirements *</label><textarea v-model="form.requirements" rows="5" placeholder="Describe your project requirements in detail..." :class="['block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', form.errors.requirements ? 'border-red-300' : '']"></textarea><p v-if="form.errors.requirements" class="mt-1 text-sm text-red-600">{{ form.errors.requirements }}</p></div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Budget Range</label>
                            <select v-model="form.budget_range" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select budget range</option>
                                <option v-for="b in budgetOptions" :key="b" :value="b">{{ b }}</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Urgency *</label>
                            <select v-model="form.urgency" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="low">Low — No rush</option>
                                <option value="medium">Medium — Within a few weeks</option>
                                <option value="high">High — ASAP</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="w-full rounded-lg bg-indigo-600 px-8 py-3 text-sm font-medium text-white hover:bg-indigo-700 transition disabled:opacity-50">
                        {{ form.processing ? 'Submitting...' : 'Submit Request' }}
                    </button>
                </form>
            </div>
        </section>
    </AppLayout>
</template>
