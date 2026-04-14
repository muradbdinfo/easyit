<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SectionHeading from '@/Components/Public/SectionHeading.vue'
import PricingCard from '@/Components/Public/PricingCard.vue'
import FaqAccordion from '@/Components/Public/FaqAccordion.vue'
import ProjectCard from '@/Components/Public/ProjectCard.vue'

defineProps({ service: Object, relatedProjects: Array })

const icons = { network: '🌐', globe: '🔗', server: '🖥️', code: '💻', database: '🗄️', users: '👥', shield: '🛡️', message: '💬' }
</script>
<template>
    <AppLayout>
        <Head><title>{{ service.title }} - Easy IT</title><meta name="description" :content="service.short_description" /></Head>

        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-4">
                    <Link href="/" class="hover:text-white">Home</Link><span>/</span>
                    <Link href="/services" class="hover:text-white">Services</Link><span>/</span>
                    <span class="text-white">{{ service.title }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-5xl">{{ icons[service.icon] || '⚙️' }}</div>
                    <div>
                        <h1 class="text-4xl font-bold">{{ service.title }}</h1>
                        <p class="mt-2 text-lg text-gray-300">{{ service.short_description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <div class="lg:col-span-2 prose prose-lg max-w-none" v-html="service.description"></div>
                    <div>
                        <div class="bg-gray-50 rounded-xl p-6 sticky top-24">
                            <h3 class="font-semibold text-gray-900 mb-4">Key Features</h3>
                            <ul class="space-y-3">
                                <li v-for="feature in service.features" :key="feature.id" class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <div><p class="text-sm font-medium text-gray-900">{{ feature.title }}</p><p v-if="feature.description" class="text-xs text-gray-500">{{ feature.description }}</p></div>
                                </li>
                            </ul>
                            <Link href="/service-request" class="mt-6 block text-center bg-indigo-600 text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-indigo-700 transition">Request This Service</Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Packages -->
        <section v-if="service.packages?.length" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Pricing Packages" />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <PricingCard v-for="pkg in service.packages" :key="pkg.id" :pkg="pkg" />
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section v-if="service.faqs?.length" class="py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Frequently Asked Questions" />
                <FaqAccordion :faqs="service.faqs" />
            </div>
        </section>

        <!-- Related Projects -->
        <section v-if="relatedProjects?.length" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Related Projects" />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <ProjectCard v-for="p in relatedProjects" :key="p.id" :project="p" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
