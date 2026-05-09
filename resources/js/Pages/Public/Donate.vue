<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import SeoHead from '@/Components/Public/SeoHead.vue'

defineProps({ seo: Object })

const bankInfo = [
    { icon: '🏦', label: 'A/C Number', value: '2171212013021' },
    { icon: '👤', label: 'Account Name', value: 'MURAD HOSEN' },
    { icon: '🏛️', label: 'Bank Name', value: 'Prime Bank' },
    { icon: '📍', label: 'Branch', value: 'Probortok Branch, Chattogram, Bangladesh' },
]

const mobilePayments = [
    { name: 'bKash', number: '01827517700', color: 'from-pink-500 to-pink-600', bg: 'bg-pink-50', border: 'border-pink-200', text: 'text-pink-700' },
    { name: 'Nagad', number: '01827517700', color: 'from-orange-500 to-orange-600', bg: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-700' },
]

function copy(text) {
    navigator.clipboard.writeText(text)
}
</script>

<template>
    <AppLayout>
        <SeoHead :title="seo?.title" :description="seo?.description" />

        <!-- Hero -->
        <section class="relative bg-gradient-to-br from-navy-900 via-navy-800 to-brand-900 pt-32 pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-1/4 w-96 h-96 bg-brand-400 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-pink-400 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-2xl mx-auto px-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur border border-white/20 mb-6">
                    <svg class="w-8 h-8 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-extrabold text-white mb-4">Support EasyIT Development</h1>
                <p class="text-gray-300 text-lg leading-relaxed">
                    Your support helps maintain, improve, and create more open-source tools for the
                    <span class="text-brand-400 font-semibold">developer community</span>.
                </p>
            </div>
        </section>

        <!-- Cards -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-5xl mx-auto px-4 grid lg:grid-cols-2 gap-8">

                <!-- Bank Info -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-navy-800 px-6 py-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h2 class="text-white font-bold text-lg">Bank Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-for="item in bankInfo" :key="item.label" class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center shrink-0 text-lg">{{ item.icon }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 font-medium mb-0.5">{{ item.label }}</p>
                                <p class="text-gray-900 font-bold text-base break-words">{{ item.value }}</p>
                            </div>
                            <button v-if="item.label === 'A/C Number'" @click="copy(item.value)"
                                class="shrink-0 p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-brand-600 transition"
                                title="Copy">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right column -->
                <div class="space-y-6">
                    <!-- Mobile Payments -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-emerald-600 px-6 py-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <h2 class="text-white font-bold text-lg">Mobile Payment (Bangladesh)</h2>
                        </div>
                        <div class="p-6 grid grid-cols-2 gap-4">
                            <div v-for="m in mobilePayments" :key="m.name" :class="[m.bg, m.border, 'border rounded-2xl p-4 text-center']">
                                <p class="font-extrabold text-gray-800 text-lg mb-2">{{ m.name }}</p>
                                <p :class="[m.text, 'font-bold text-xl tracking-wider mb-3']">{{ m.number }}</p>
                                <button @click="copy(m.number)" :class="['w-full py-2 rounded-xl text-xs font-semibold text-white bg-gradient-to-r', m.color, 'hover:opacity-90 transition flex items-center justify-center gap-1.5']">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    Copy Number
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Connect -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-purple-600 px-6 py-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h2 class="text-white font-bold text-lg">Connect With Me</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="https://wa.me/8801827517700" target="_blank"
                                class="flex items-center gap-4 p-3 rounded-2xl hover:bg-gray-50 transition group">
                                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">WhatsApp</p>
                                    <p class="font-bold text-gray-800">+8801827517700</p>
                                </div>
                            </a>
                            <a href="https://www.facebook.com/muradhosenofficial" target="_blank"
                                class="flex items-center gap-4 p-3 rounded-2xl hover:bg-gray-50 transition group">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Facebook</p>
                                    <p class="font-bold text-blue-600 text-sm">muradhosenofficial</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thank you banner -->
            <div class="max-w-5xl mx-auto px-4 mt-8">
                <div class="bg-gradient-to-r from-emerald-50 to-brand-50 border border-emerald-200 rounded-3xl p-6 flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                    <div class="w-14 h-14 rounded-2xl bg-white border border-emerald-200 flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-7 h-7 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-lg">Thank You! 🙏</p>
                        <p class="text-gray-600 text-sm">Every contribution, big or small, makes a huge difference. Thank you for being a part of this journey!</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>