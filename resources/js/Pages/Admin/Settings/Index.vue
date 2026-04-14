<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ settings: Object })

const allSettings = []
Object.keys(props.settings).forEach(group => {
    props.settings[group].forEach(s => allSettings.push({ key: s.key, value: s.value, group: s.group }))
})

const form = useForm({ settings: allSettings })
const submit = () => form.put('/admin/settings')

const groupLabels = { general: 'General', contact: 'Contact Info', social: 'Social Media', seo: 'SEO', stats: 'Stats Counters' }
const groups = Object.keys(props.settings)

const getGroupSettings = (group) => form.settings.filter(s => s.group === group)
const formatLabel = (key) => key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
</script>
<template>
    <AdminLayout>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Settings</h1>
        <form @submit.prevent="submit" class="space-y-6 max-w-3xl">
            <div v-for="group in groups" :key="group" class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 capitalize">{{ groupLabels[group] || group }}</h3>
                <div class="space-y-3">
                    <div v-for="setting in getGroupSettings(group)" :key="setting.key">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ formatLabel(setting.key) }}</label>
                        <textarea v-if="setting.key.includes('description') || setting.key.includes('embed')" v-model="setting.value" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        <input v-else v-model="setting.value" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end"><button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Save Settings</button></div>
        </form>
    </AdminLayout>
</template>
