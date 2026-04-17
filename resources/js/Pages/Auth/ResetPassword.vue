<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    email: { type: String, required: true },
    token: { type: String, required: true },
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <div class="min-h-screen flex">
        <Head><title>Reset Password - Easy IT</title></Head>

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
                    Set Your New<br/><span class="text-brand-400">Password</span>
                </h1>
                <p class="text-gray-400 leading-relaxed max-w-md mb-8">
                    Choose a strong password to keep your account secure. Once updated, you'll be signed in automatically.
                </p>

                <div class="bg-white/5 rounded-2xl border border-white/10 p-6">
                    <h3 class="text-sm font-bold text-white mb-3">Password tips:</h3>
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-brand-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Use at least 8 characters
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-brand-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Mix letters, numbers & symbols
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-brand-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Avoid reusing old passwords
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Reset Password Form -->
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

                <h2 class="text-2xl font-extrabold text-navy-900 mb-1">Reset your password</h2>
                <p class="text-sm text-gray-500 mb-8">
                    Enter your new password below to regain access.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input v-model="form.email" type="email" required autofocus autocomplete="username"
                            :class="[form.errors.email ? 'border-red-300 ring-red-100' : 'border-gray-200 focus:border-brand-500 focus:ring-brand-100', 'block w-full rounded-xl border px-4 py-3 text-sm shadow-sm ring-2 ring-transparent transition-all']"
                            placeholder="you@company.com" />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                        <input v-model="form.password" type="password" required autocomplete="new-password"
                            :class="[form.errors.password ? 'border-red-300 ring-red-100' : 'border-gray-200 focus:border-brand-500 focus:ring-brand-100', 'block w-full rounded-xl border px-4 py-3 text-sm shadow-sm ring-2 ring-transparent transition-all']"
                            placeholder="Min 8 characters" />
                        <p v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                        <input v-model="form.password_confirmation" type="password" required autocomplete="new-password"
                            :class="[form.errors.password_confirmation ? 'border-red-300 ring-red-100' : 'border-gray-200 focus:border-brand-500 focus:ring-brand-100', 'block w-full rounded-xl border px-4 py-3 text-sm shadow-sm ring-2 ring-transparent transition-all']"
                            placeholder="••••••••" />
                        <p v-if="form.errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-3.5 text-sm font-bold text-white hover:from-brand-600 hover:to-brand-700 transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 disabled:opacity-50">
                        {{ form.processing ? 'Resetting...' : 'Reset Password' }}
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <Link href="/login" class="text-xs text-gray-400 hover:text-gray-600 transition">← Back to sign in</Link>
                </div>
            </div>
        </div>
    </div>
</template>