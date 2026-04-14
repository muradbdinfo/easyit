<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    items: {
        type: Array,
        default: () => [],
        // Format: [{ label: 'Home', href: '/' }, { label: 'Services' }]
    }
})
</script>

<template>
    <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-4">
        <ol class="flex items-center gap-2" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li v-for="(item, i) in items" :key="i" class="flex items-center gap-2"
                itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span v-if="i > 0" class="text-gray-400">/</span>
                <Link v-if="item.href" :href="item.href" itemprop="item" class="hover:text-indigo-600 transition">
                    <span itemprop="name">{{ item.label }}</span>
                </Link>
                <span v-else itemprop="name" class="text-gray-900 font-medium">{{ item.label }}</span>
                <meta itemprop="position" :content="String(i + 1)" />
            </li>
        </ol>
    </nav>
</template>
