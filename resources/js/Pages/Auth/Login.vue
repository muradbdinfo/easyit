<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({ canResetPassword: Boolean, status: String })

const form = useForm({ email: '', password: '', remember: false })
const submit = () => form.post('/login')
</script>

<template>
    <div class="min-h-screen flex">
        <Head><title>Client Login - Easy IT</title></Head>

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
                    Welcome to Your<br/><span class="text-brand-400">Client Portal</span>
                </h1>
                <p class="text-gray-400 leading-relaxed max-w-md mb-8">
                    Manage your services, track support tickets, view invoices, and stay connected with our team — all in one place.
                </p>

                <div class="space-y-4">
                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <div class="w-8 h-8 bg-brand-500/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        Track your services & renewal dates
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <div class="w-8 h-8 bg-brand-500/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        Submit & track support tickets
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <div class="w-8 h-8 bg-brand-500/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        View invoices & submit payments
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Login Form -->
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

                <h2 class="text-2xl font-extrabold text-navy-900 mb-1">Sign in to your account</h2>
                <p class="text-sm text-gray-500 mb-8">
                    Don't have an account?
                    <Link href="/register" class="text-brand-600 font-semibold hover:text-brand-700">Create one free →</Link>
                </p>

                <div v-if="status" class="mb-4 text-sm text-emerald-600 bg-emerald-50 rounded-xl px-4 py-3">{{ status }}</div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input v-model="form.email" type="email" required autofocus
                            :class="[form.errors.email ? 'border-red-300 ring-red-100' : 'border-gray-200 focus:border-brand-500 focus:ring-brand-100', 'block w-full rounded-xl border px-4 py-3 text-sm shadow-sm ring-2 ring-transparent transition-all']"
                            placeholder="you@company.com" />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="text-sm font-semibold text-gray-700">Password</label>
                            <Link v-if="canResetPassword" href="/forgot-password" class="text-xs text-brand-600 hover:text-brand-700 font-medium">Forgot?</Link>
                        </div>
                        <input v-model="form.password" type="password" required
                            :class="[form.errors.password ? 'border-red-300' : 'border-gray-200 focus:border-brand-500 focus:ring-brand-100', 'block w-full rounded-xl border px-4 py-3 text-sm shadow-sm ring-2 ring-transparent transition-all']"
                            placeholder="••••••••" />
                        <p v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <label class="flex items-center gap-2">
                        <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
                        <span class="text-sm text-gray-600">Keep me signed in</span>
                    </label>

                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-3.5 text-sm font-bold text-white hover:from-brand-600 hover:to-brand-700 transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 disabled:opacity-50">
                        {{ form.processing ? 'Signing in...' : 'Sign In' }}
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <Link href="/" class="text-xs text-gray-400 hover:text-gray-600 transition">← Back to website</Link>
                </div>
            </div>
        </div>
    </div>
</template>
