<script setup>
import { computed } from 'vue'

const props = defineProps({ status: String })

const colors = {
    new: 'bg-blue-100 text-blue-800',
    read: 'bg-gray-100 text-gray-800',
    replied: 'bg-green-100 text-green-800',
    archived: 'bg-yellow-100 text-yellow-800',
    pending: 'bg-orange-100 text-orange-800',
    reviewing: 'bg-blue-100 text-blue-800',
    quoted: 'bg-purple-100 text-purple-800',
    accepted: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
    draft: 'bg-gray-100 text-gray-800',
    published: 'bg-green-100 text-green-800',
}

const colorClass = computed(() => colors[props.status] || 'bg-gray-100 text-gray-800')
</script>

<template>
    <span :class="[colorClass, 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize']">
        {{ status }}
    </span>
</template>
