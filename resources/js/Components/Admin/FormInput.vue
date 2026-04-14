<script setup>
defineProps({
    modelValue: { type: [String, Number], default: '' },
    label: String,
    type: { type: String, default: 'text' },
    error: String,
    required: { type: Boolean, default: false },
    placeholder: { type: String, default: '' },
})
defineEmits(['update:modelValue'])
</script>

<template>
    <div>
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <input
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :class="['block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', error ? 'border-red-300' : '']"
        />
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>
