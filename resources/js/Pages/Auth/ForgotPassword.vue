<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({ status: String })

const form = useForm({ email: '' })
const submit = () => form.post(route('password.email'))
</script>

<template>
    <div class="min-h-screen flex">
        <Head><title>Forgot Password - Easy IT</title></Head>

        <!-- Left: Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-navy-950 via-navy-900 to-brand-950 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
            <div class="absolute top-1/4 -left-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 -right-20 w-80 h-80 bg-brand-500/5 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col justify-center px-12 lg:px-16">
                <Link href="/" class="flex items-center gap-3 mb-12">
                    <div class="w-10 h-10 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center">
                        <span class="text-white font-extrabold">E</span>
                    </div>
                    <span class="text-2xl font-bold text-white">Easy <span class="text-brand-400">IT</span></span>
                </Link>

                <h1 class="text-3xl lg:text-4xl font-extrabold text-white leading-tight mb-4">
                    Reset Your<br/><span class="text-brand-400">Password</span>
                </h1>
                <p class="text-gray-400 leading-relaxed max-w-md mb-8">
                    No worries — it happens to everyone. We'll send you a secure link to reset your password and get you back into your account.
                </p>

                <div class="bg-white/5 rounded-2xl border border-white/10 p-6">
                    <h3 class="text-sm font-bold text-white mb-3">How it works:</h3>
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <div class="w-6 h-6 bg-brand-500/20 rounded-full flex items-center justify-center shrink-0">
                                <span class="text-brand-400 text-xs font-bold">1</span>
                            </div>
                            Enter your registered email address
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <div class="w-6 h-6 bg-brand-500/20 rounded-full flex items-center justify-center shrink-0">
                                <span class="text-brand-400 text-xs font-bold">2</span>
                            </div>
                            Check your inbox for the reset link
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <div class="w-6 h-6 bg-brand-500/20 rounded-full flex items-center justify-center shrink-0">
                                <span class="text-brand-400 text-xs font-bold">3</span>
                            </div>
                            Choose a new password and sign in
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Forgot Password Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-8 py-12 bg-gray-50">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <div class="lg:hidden flex items-center gap-2.5 mb-8">
                    <Link href="/" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center">
                            <span class="text-white font-extrabold text-sm">E</span>
                        </div>
                        <span class="text-lg font-bold text-navy-900">Easy <span class="text-brand-500">IT</span></span>
                    </Link>
                </div>

                <h2 class="text-2xl font-extrabold text-navy-900 mb-1">Forgot your password?</h2>
                <p class="text-sm text-gray-500 mb-8">
                    Enter your email and we'll send you a reset link.
                </p>

                <div v-if="status" class="mb-4 text-sm text-emerald-600 bg-emerald-50 rounded-xl px-4 py-3 font-medium">{{ status }}</div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input v-model="form.email" type="email" required autofocus autocomplete="username"
                            :class="[form.errors.email ? 'border-red-300 ring-red-100' : 'border-gray-200 focus:border-brand-500 focus:ring-brand-100', 'block w-full rounded-xl border px-4 py-3 text-sm shadow-sm ring-2 ring-transparent transition-all']"
                            placeholder="you@company.com" />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-3.5 text-sm font-bold text-white hover:from-brand-600 hover:to-brand-700 transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 disabled:opacity-50">
                        {{ form.processing ? 'Sending link...' : 'Email Password Reset Link' }}
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <Link href="/login" class="text-xs text-gray-400 hover:text-gray-600 transition">← Back to sign in</Link>
                </div>
            </div>
        </div>
    </div>
</template>