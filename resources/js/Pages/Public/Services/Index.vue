<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SectionHeading from '@/Components/Public/SectionHeading.vue'
import ServiceCard from '@/Components/Public/ServiceCard.vue'
defineProps({ services: Array })
</script>
<template>
    <AppLayout>
        <Head><title>Our Services - Easy IT</title></Head>
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold">Our Services</h1>
                <p class="mt-4 text-lg text-gray-300 max-w-2xl mx-auto">Comprehensive IT solutions for businesses of all sizes</p>
            </div>
        </section>
        <section class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ServiceCard v-for="service in services" :key="service.id" :service="service" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
