<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ pkg: Object })
</script>
<template>
    <div :class="[pkg.is_featured ? 'border-brand-500 ring-1 ring-brand-500 shadow-xl shadow-brand-500/10 scale-[1.02]' : 'border-gray-100', 'relative bg-white rounded-2xl border p-7 flex flex-col transition-all hover:shadow-lg']">
        <div v-if="pkg.is_featured" class="absolute -top-3 left-1/2 -translate-x-1/2">
            <span class="bg-gradient-to-r from-brand-500 to-brand-600 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-1 rounded-full shadow-lg shadow-brand-500/20">Most Popular</span>
        </div>
        <h3 class="text-lg font-bold text-navy-900">{{ pkg.name }}</h3>
        <div class="mt-4 flex items-baseline gap-1">
            <span class="text-3xl font-extrabold text-navy-900">৳{{ Number(pkg.price).toLocaleString() }}</span>
            <span class="text-sm text-gray-400 font-medium">/{{ pkg.billing_cycle }}</span>
        </div>
        <ul class="mt-6 space-y-3 flex-1">
            <li v-for="f in pkg.features" :key="f.id" class="flex items-start gap-2.5 text-sm">
                <svg v-if="f.is_included" class="w-5 h-5 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <svg v-else class="w-5 h-5 text-gray-200 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span :class="f.is_included ? 'text-gray-700' : 'text-gray-400'">{{ f.feature }}</span>
            </li>
        </ul>
        <Link href="/service-request" :class="[pkg.is_featured ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-lg shadow-brand-500/20 hover:shadow-brand-500/40' : 'bg-gray-50 text-navy-900 hover:bg-gray-100', 'mt-7 block text-center rounded-xl px-4 py-3 text-sm font-bold transition-all hover:-translate-y-0.5']">
            Get Started
        </Link>
    </div>
</template>
