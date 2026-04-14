<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ProjectCard from '@/Components/Public/ProjectCard.vue'
defineProps({ project: Object, relatedProjects: Array })
const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) : null
</script>
<template>
    <AppLayout>
        <Head><title>{{ project.title }} - Easy IT</title></Head>
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-4">
                    <Link href="/" class="hover:text-white">Home</Link><span>/</span>
                    <Link href="/projects" class="hover:text-white">Portfolio</Link><span>/</span>
                    <span class="text-white">{{ project.title }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold">{{ project.title }}</h1>
                <p class="mt-2 text-gray-300">{{ project.short_description }}</p>
            </div>
        </section>
        <section class="py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8 bg-gray-50 rounded-xl p-4">
                    <div v-if="project.service"><p class="text-xs text-gray-500">Service</p><p class="text-sm font-medium">{{ project.service.title }}</p></div>
                    <div v-if="project.client_name"><p class="text-xs text-gray-500">Client</p><p class="text-sm font-medium">{{ project.client_name }}</p></div>
                    <div v-if="project.completed_at"><p class="text-xs text-gray-500">Completed</p><p class="text-sm font-medium">{{ formatDate(project.completed_at) }}</p></div>
                    <div v-if="project.project_url"><p class="text-xs text-gray-500">Website</p><a :href="project.project_url" target="_blank" class="text-sm font-medium text-indigo-600 hover:underline">Visit →</a></div>
                </div>
                <div class="prose prose-lg max-w-none" v-html="project.description"></div>
                <!-- Image gallery -->
                <div v-if="project.media?.length" class="mt-8 grid grid-cols-2 gap-4">
                    <img v-for="img in project.media" :key="img.id" :src="img.original_url" :alt="project.title" class="rounded-lg w-full object-cover" />
                </div>
            </div>
        </section>
        <section v-if="relatedProjects?.length" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Related Projects</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <ProjectCard v-for="p in relatedProjects" :key="p.id" :project="p" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
