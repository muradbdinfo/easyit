<script setup>
defineProps({
    modelValue: { type: [String, Number, null], default: '' },
    label: String,
    options: { type: Array, default: () => [] },
    error: String,
    required: { type: Boolean, default: false },
    placeholder: { type: String, default: 'Select...' },
    valueKey: { type: String, default: 'id' },
    labelKey: { type: String, default: 'name' },
})
defineEmits(['update:modelValue'])
</script>

<template>
    <div>
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <select
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
            :class="['block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', error ? 'border-red-300' : '']"
        >
            <option value="">{{ placeholder }}</option>
            <option v-for="opt in options" :key="opt[valueKey] ?? opt" :value="opt[valueKey] ?? opt">
                {{ opt[labelKey] ?? opt }}
            </option>
        </select>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>
