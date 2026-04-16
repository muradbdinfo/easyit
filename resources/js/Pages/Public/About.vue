<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SectionHeading from '@/Components/Public/SectionHeading.vue'
import TeamCard from '@/Components/Public/TeamCard.vue'
defineProps({ page: Object, teamMembers: Array })
</script>
<template>
    <AppLayout>
        <Head><title>About Us - Easy IT</title></Head>

        <!-- Hero -->
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold">About Easy IT</h1>
                <p class="mt-4 text-lg text-gray-300 max-w-2xl mx-auto">We're a passionate team of IT professionals dedicated to providing top-notch technology solutions in Bangladesh.</p>
            </div>
        </section>

        <!-- Story -->
        <section class="py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg max-w-none">
                    <h2 class="text-2xl font-bold text-gray-900">Our Story</h2>
                    <p class="text-gray-600 mt-4">Easy IT was founded with a simple mission: to make professional IT services accessible to businesses in Katalganj, chattogram, and across Bangladesh. We started as a small network solutions provider and have grown into a full-service IT company offering everything from web hosting to custom software development.</p>
                    <h2 class="text-2xl font-bold text-gray-900 mt-10">Our Mission</h2>
                    <p class="text-gray-600 mt-4">To empower businesses with reliable, affordable, and innovative technology solutions. We believe every business, regardless of size, deserves world-class IT infrastructure and support.</p>
                    <h2 class="text-2xl font-bold text-gray-900 mt-10">Our Values</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 not-prose">
                        <div class="bg-gray-50 rounded-lg p-5"><div class="text-2xl mb-2">🎯</div><h3 class="font-semibold text-gray-900">Reliability</h3><p class="text-sm text-gray-500 mt-1">We deliver what we promise, on time, every time.</p></div>
                        <div class="bg-gray-50 rounded-lg p-5"><div class="text-2xl mb-2">💡</div><h3 class="font-semibold text-gray-900">Innovation</h3><p class="text-sm text-gray-500 mt-1">We stay ahead with the latest technologies and best practices.</p></div>
                        <div class="bg-gray-50 rounded-lg p-5"><div class="text-2xl mb-2">🤝</div><h3 class="font-semibold text-gray-900">Partnership</h3><p class="text-sm text-gray-500 mt-1">We build lasting relationships, not just projects.</p></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team -->
        <section v-if="teamMembers?.length" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Meet Our Team" subtitle="The people behind Easy IT" />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <TeamCard v-for="member in teamMembers" :key="member.id" :member="member" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
