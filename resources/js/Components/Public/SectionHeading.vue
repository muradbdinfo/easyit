<script setup>
defineProps({
    title: String,
    subtitle: String,
    center: { type: Boolean, default: true },
    light: { type: Boolean, default: false },
    tag: { type: String, default: '' },
})
</script>
<template>
    <div :class="[center ? 'text-center' : '', 'mb-12 sm:mb-16']">
        <span v-if="tag" :class="[light ? 'text-brand-300 bg-brand-500/10' : 'text-brand-600 bg-brand-50', 'inline-block text-xs font-bold uppercase tracking-[0.2em] px-4 py-1.5 rounded-full mb-4']">{{ tag }}</span>
        <h2 :class="[light ? 'text-white' : 'text-navy-900', 'text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight']">{{ title }}</h2>
        <p v-if="subtitle" :class="[light ? 'text-gray-400' : 'text-gray-500', 'mt-3 text-base sm:text-lg max-w-2xl leading-relaxed', center ? 'mx-auto' : '']">{{ subtitle }}</p>
    </div>
</template>
