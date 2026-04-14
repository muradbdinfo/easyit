<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
const show = ref(false)
const onScroll = () => { show.value = window.scrollY > 400 }
const scrollTop = () => { window.scrollTo({ top: 0, behavior: 'smooth' }) }
onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>
<template>
    <button v-show="show" @click="scrollTop" class="fixed bottom-6 left-6 z-40 w-10 h-10 bg-gray-800 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
    </button>
</template>
