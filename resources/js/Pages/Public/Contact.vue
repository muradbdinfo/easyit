<script setup>
import { computed } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const settings = computed(() => usePage().props.settings || {})
const form = useForm({ name: '', email: '', phone: '', subject: '', message: '' })
const submit = () => form.post('/contact', { onSuccess: () => form.reset() })
</script>
<template>
    <AppLayout>
        <Head><title>Contact Us - Easy IT</title></Head>
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold">Contact Us</h1>
                <p class="mt-4 text-lg text-gray-300">We'd love to hear from you. Get in touch today.</p>
            </div>
        </section>
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Contact Info -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Get In Touch</h2>
                        <div class="space-y-6">
                            <div v-if="settings.company_address" class="flex gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 shrink-0">📍</div>
                                <div><p class="text-sm text-gray-500">Address</p><p class="font-medium text-gray-900">{{ settings.company_address }}</p></div>
                            </div>
                            <div v-if="settings.company_phone" class="flex gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 shrink-0">📞</div>
                                <div><p class="text-sm text-gray-500">Phone</p><a :href="`tel:${settings.company_phone}`" class="font-medium text-gray-900 hover:text-indigo-600">{{ settings.company_phone }}</a></div>
                            </div>
                            <div v-if="settings.company_email" class="flex gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 shrink-0">✉️</div>
                                <div><p class="text-sm text-gray-500">Email</p><a :href="`mailto:${settings.company_email}`" class="font-medium text-gray-900 hover:text-indigo-600">{{ settings.company_email }}</a></div>
                            </div>
                        </div>
                    </div>
                    <!-- Form -->
                    <div class="lg:col-span-2">
                        <form @submit.prevent="submit" class="bg-white rounded-xl border border-gray-100 p-6 sm:p-8 shadow-sm space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Name *</label><input v-model="form.name" type="text" :class="['block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', form.errors.name ? 'border-red-300' : '']" /><p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Email *</label><input v-model="form.email" type="email" :class="['block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', form.errors.email ? 'border-red-300' : '']" /><p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone</label><input v-model="form.phone" type="tel" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" /></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Subject *</label><input v-model="form.subject" type="text" :class="['block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', form.errors.subject ? 'border-red-300' : '']" /><p v-if="form.errors.subject" class="mt-1 text-sm text-red-600">{{ form.errors.subject }}</p></div>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Message *</label><textarea v-model="form.message" rows="5" :class="['block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', form.errors.message ? 'border-red-300' : '']"></textarea><p v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</p></div>
                            <button type="submit" :disabled="form.processing" class="w-full sm:w-auto rounded-lg bg-indigo-600 px-8 py-3 text-sm font-medium text-white hover:bg-indigo-700 transition disabled:opacity-50">
                                {{ form.processing ? 'Sending...' : 'Send Message' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
