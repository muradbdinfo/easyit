<script setup>
import { ref } from 'vue'
defineProps({ faqs: Array })
const openIndex = ref(null)
const toggle = (i) => { openIndex.value = openIndex.value === i ? null : i }
</script>
<template>
    <div class="space-y-3">
        <div v-for="(faq, i) in faqs" :key="faq.id"
            :class="[openIndex === i ? 'bg-brand-50/50 border-brand-200' : 'bg-white border-gray-100 hover:border-gray-200', 'border rounded-2xl overflow-hidden transition-all duration-300']">
            <button @click="toggle(i)" class="w-full flex justify-between items-center px-6 py-4 text-left group">
                <span :class="[openIndex === i ? 'text-brand-700' : 'text-navy-900', 'font-semibold text-sm sm:text-base pr-4 transition-colors']">{{ faq.question }}</span>
                <div :class="[openIndex === i ? 'bg-brand-500 rotate-180' : 'bg-gray-100 group-hover:bg-gray-200', 'w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-all duration-300']">
                    <svg :class="[openIndex === i ? 'text-white' : 'text-gray-500', 'w-3.5 h-3.5 transition-colors']" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </button>
            <transition enter-active-class="transition-all duration-300" enter-from-class="max-h-0 opacity-0" enter-to-class="max-h-96 opacity-100"
                leave-active-class="transition-all duration-200" leave-from-class="max-h-96 opacity-100" leave-to-class="max-h-0 opacity-0">
                <div v-show="openIndex === i" class="overflow-hidden">
                    <div class="px-6 pb-5 text-sm text-gray-600 leading-relaxed">{{ faq.answer }}</div>
                </div>
            </transition>
        </div>
    </div>
</template>
