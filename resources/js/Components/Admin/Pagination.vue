<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ links: Array })
</script>

<template>
    <nav v-if="links && links.length > 3" class="flex items-center justify-center gap-1 mt-6">
        <template v-for="(link, i) in links" :key="i">
            <Link v-if="link.url"
                :href="link.url"
                v-html="link.label"
                :class="[link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50', 'relative inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-medium']"
            />
            <span v-else v-html="link.label" class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-400" />
        </template>
    </nav>
</template>
