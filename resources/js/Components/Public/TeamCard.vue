<script setup>
defineProps({ member: Object })
</script>
<template>
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden text-center p-6 hover:shadow-lg transition">
        <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 overflow-hidden mb-4">
            <img v-if="member.media?.[0]?.original_url" :src="member.media[0].original_url" :alt="member.name" class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-3xl">👤</div>
        </div>
        <h3 class="font-semibold text-gray-900">{{ member.name }}</h3>
        <p class="text-sm text-indigo-600">{{ member.designation }}</p>
        <p v-if="member.bio" class="text-sm text-gray-500 mt-2 line-clamp-3">{{ member.bio }}</p>
        <div class="flex justify-center gap-3 mt-3">
            <a v-if="member.facebook" :href="member.facebook" target="_blank" class="text-gray-400 hover:text-blue-600 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
            </a>
            <a v-if="member.linkedin" :href="member.linkedin" target="_blank" class="text-gray-400 hover:text-blue-700 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
        </div>
    </div>
</template>
