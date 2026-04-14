<script setup>
defineProps({ title: String, subtitle: String, center: { type: Boolean, default: true } })
</script>
<template>
    <div :class="[center ? 'text-center' : '', 'mb-12']">
        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">{{ title }}</h2>
        <p v-if="subtitle" class="mt-3 text-lg text-gray-500 max-w-2xl" :class="center ? 'mx-auto' : ''">{{ subtitle }}</p>
        <div class="mt-4 w-20 h-1 bg-indigo-600 rounded" :class="center ? 'mx-auto' : ''"></div>
    </div>
</template>
