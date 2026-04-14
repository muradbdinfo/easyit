<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormInput from '@/Components/Admin/FormInput.vue'
import FormSelect from '@/Components/Admin/FormSelect.vue'
const props = defineProps({ package: Object, services: Array })
const pkg = props.package
const form = useForm({ _method: 'PUT', service_id: pkg.service_id, name: pkg.name, price: pkg.price, billing_cycle: pkg.billing_cycle, is_featured: pkg.is_featured, is_active: pkg.is_active, sort_order: pkg.sort_order, features: pkg.features?.map(f => ({ id: f.id, feature: f.feature, is_included: f.is_included })) || [] })
const addFeature = () => form.features.push({ id: null, feature: '', is_included: true })
const removeFeature = (i) => form.features.splice(i, 1)
const submit = () => form.post(`/admin/packages/${pkg.id}`)
</script>
<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6"><h1 class="text-2xl font-bold text-gray-900">Edit Package: {{ pkg.name }}</h1><Link href="/admin/packages" class="text-sm text-gray-600 hover:text-gray-900">← Back</Link></div>
        <form @submit.prevent="submit" class="space-y-6 max-w-3xl">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <FormSelect v-model="form.service_id" label="Service" :options="services" value-key="id" label-key="title" required />
                <div class="grid grid-cols-3 gap-4"><FormInput v-model="form.name" label="Package Name" required /><FormInput v-model="form.price" label="Price (BDT)" type="number" required /><FormSelect v-model="form.billing_cycle" label="Billing Cycle" :options="[{id:'monthly',name:'Monthly'},{id:'yearly',name:'Yearly'},{id:'one-time',name:'One Time'}]" /></div>
                <div class="flex items-center gap-6"><label class="flex items-center gap-2"><input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-indigo-600" /><span class="text-sm">Active</span></label><label class="flex items-center gap-2"><input type="checkbox" v-model="form.is_featured" class="rounded border-gray-300 text-indigo-600" /><span class="text-sm">Featured</span></label><FormInput v-model="form.sort_order" label="Order" type="number" /></div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-medium text-gray-900">Package Features</h3><button type="button" @click="addFeature" class="text-sm text-indigo-600 hover:text-indigo-800">+ Add</button></div>
                <div v-for="(f, i) in form.features" :key="i" class="flex gap-3 mb-2 items-center">
                    <input v-model="f.feature" placeholder="Feature" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                    <label class="flex items-center gap-1"><input type="checkbox" v-model="f.is_included" class="rounded border-gray-300 text-indigo-600" /><span class="text-xs">Included</span></label>
                    <button type="button" @click="removeFeature(i)" class="text-red-500">✕</button>
                </div>
            </div>
            <div class="flex justify-end"><button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Update</button></div>
        </form>
    </AdminLayout>
</template>
