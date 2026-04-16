<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
const show = ref(false)
const onScroll = () => { show.value = window.scrollY > 500 }
const scrollTop = () => { window.scrollTo({ top: 0, behavior: 'smooth' }) }
onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>
<template>
    <transition enter-active-class="transition duration-200" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0 translate-y-2">
        <button v-show="show" @click="scrollTop" class="fixed bottom-6 left-6 z-40 w-10 h-10 bg-navy-900/80 backdrop-blur text-white rounded-xl flex items-center justify-center shadow-lg hover:bg-navy-800 active:scale-90 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
        </button>
    </transition>
</template>
