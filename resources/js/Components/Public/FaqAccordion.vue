<script setup>
import { ref } from 'vue'
defineProps({ faqs: Array })
const openIndex = ref(null)
const toggle = (i) => { openIndex.value = openIndex.value === i ? null : i }
</script>
<template>
    <div class="space-y-3">
        <div v-for="(faq, i) in faqs" :key="faq.id" class="border border-gray-200 rounded-lg overflow-hidden">
            <button @click="toggle(i)" class="w-full flex justify-between items-center px-5 py-4 text-left hover:bg-gray-50 transition">
                <span class="font-medium text-gray-900">{{ faq.question }}</span>
                <svg :class="[openIndex === i ? 'rotate-180' : '', 'w-5 h-5 text-gray-500 transition-transform']" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div v-show="openIndex === i" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed">{{ faq.answer }}</div>
        </div>
    </div>
</template>
