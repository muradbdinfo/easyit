<script setup>
import { useForm } from '@inertiajs/vue3'
import ClientLayout from '@/Layouts/ClientLayout.vue'
const props = defineProps({ user: Object })
const form = useForm({ name: props.user.name, phone: props.user.phone||'', company: props.user.company||'', company_address: props.user.company_address||'', avatar: null })
const submit = () => form.post('/client/profile', { _method: 'PUT' })
</script>
<template>
    <ClientLayout>
        <h1 class="text-xl font-bold text-gray-900 mb-6">My Profile</h1>
        <form @submit.prevent="submit" class="bg-white rounded-2xl border border-gray-100 p-6 max-w-xl space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input v-model="form.name" class="w-full rounded-lg border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input :value="user.email" disabled class="w-full rounded-lg border-gray-300 text-sm bg-gray-50 text-gray-500" /></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input v-model="form.phone" class="w-full rounded-lg border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                <input v-model="form.company" class="w-full rounded-lg border-gray-300 text-sm" /></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Company Address</label>
                <textarea v-model="form.company_address" rows="2" class="w-full rounded-lg border-gray-300 text-sm"></textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Avatar</label>
                <input type="file" accept="image/*" @change="e => form.avatar = e.target.files[0]" class="text-sm" /></div>
            <button type="submit" :disabled="form.processing" class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-50 transition">Save Changes</button>
        </form>
    </ClientLayout>
</template>
