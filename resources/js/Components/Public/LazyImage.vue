<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    src: String,
    alt: { type: String, default: '' },
    cls: { type: String, default: '' },
})

const loaded = ref(false)
const imgRef = ref(null)

onMounted(() => {
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loaded.value = true
                    observer.unobserve(entry.target)
                }
            })
        }, { rootMargin: '100px' })

        if (imgRef.value) observer.observe(imgRef.value)
    } else {
        loaded.value = true
    }
})
</script>

<template>
    <div ref="imgRef" :class="cls">
        <img v-if="loaded && src" :src="src" :alt="alt" loading="lazy" :class="cls"
            @load="$el.classList.add('opacity-100')"
            class="transition-opacity duration-300 opacity-0" />
        <div v-else :class="[cls, 'bg-gray-100 animate-pulse']"></div>
    </div>
</template>
