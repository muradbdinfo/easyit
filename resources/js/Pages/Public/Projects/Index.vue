<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ProjectCard from '@/Components/Public/ProjectCard.vue'
import Pagination from '@/Components/Admin/Pagination.vue'
defineProps({ projects: Object, services: Array, filters: Object })
const filterByService = (slug) => router.get('/projects', slug ? { service: slug } : {}, { preserveState: true })
</script>
<template>
    <AppLayout>
        <Head><title>Our Portfolio - Easy IT</title></Head>
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold">Our Portfolio</h1>
                <p class="mt-4 text-lg text-gray-300">Explore our completed projects and success stories</p>
            </div>
        </section>
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap gap-2 mb-8 justify-center">
                    <button @click="filterByService('')" :class="[!filters?.service ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'rounded-full px-4 py-2 text-sm font-medium transition']">All</button>
                    <button v-for="s in services" :key="s.id" @click="filterByService(s.slug)"
                        :class="[filters?.service === s.slug ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'rounded-full px-4 py-2 text-sm font-medium transition']">
                        {{ s.title }}
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <ProjectCard v-for="p in projects.data" :key="p.id" :project="p" />
                </div>
                <div v-if="!projects.data?.length" class="text-center py-12 text-gray-500">No projects found.</div>
                <Pagination :links="projects.links" />
            </div>
        </section>
    </AppLayout>
</template>
