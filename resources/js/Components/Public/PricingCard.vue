<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ pkg: Object })
</script>
<template>
    <div :class="[pkg.is_featured ? 'border-indigo-600 ring-2 ring-indigo-600 scale-105' : 'border-gray-200', 'relative bg-white rounded-xl border p-6 flex flex-col']">
        <div v-if="pkg.is_featured" class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full">Popular</div>
        <h3 class="text-lg font-semibold text-gray-900">{{ pkg.name }}</h3>
        <div class="mt-3 flex items-baseline gap-1">
            <span class="text-3xl font-bold text-gray-900">৳{{ pkg.price }}</span>
            <span class="text-sm text-gray-500">/{{ pkg.billing_cycle }}</span>
        </div>
        <ul class="mt-6 space-y-3 flex-1">
            <li v-for="f in pkg.features" :key="f.id" class="flex items-start gap-2 text-sm">
                <svg v-if="f.is_included" class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <svg v-else class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span :class="f.is_included ? 'text-gray-700' : 'text-gray-400 line-through'">{{ f.feature }}</span>
            </li>
        </ul>
        <Link href="/service-request" :class="[pkg.is_featured ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-900 hover:bg-gray-200', 'mt-6 block text-center rounded-lg px-4 py-2.5 text-sm font-medium transition']">
            Get Started
        </Link>
    </div>
</template>
