<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FaqAccordion from '@/Components/Public/FaqAccordion.vue'
defineProps({ faqs: Object })
</script>
<template>
    <AppLayout>
        <Head><title>FAQ - Easy IT</title></Head>
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold">Frequently Asked Questions</h1>
                <p class="mt-4 text-lg text-gray-300">Find answers to common questions about our services</p>
            </div>
        </section>
        <section class="py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div v-for="(groupFaqs, groupName) in faqs" :key="groupName">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ groupName }}</h2>
                    <FaqAccordion :faqs="groupFaqs" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
