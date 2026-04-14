<script setup>
import { ref } from 'vue'

const props = defineProps({
    label: { type: String, default: 'Image' },
    currentImage: { type: String, default: null },
    error: String,
})
const emit = defineEmits(['file-selected'])

const preview = ref(props.currentImage)

const handleFile = (e) => {
    const file = e.target.files[0]
    if (file) {
        preview.value = URL.createObjectURL(file)
        emit('file-selected', file)
    }
}
</script>

<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
        <div class="flex items-center gap-4">
            <img v-if="preview" :src="preview" class="h-20 w-20 rounded object-cover border" />
            <div v-else class="h-20 w-20 rounded border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs">
                No image
            </div>
            <input type="file" accept="image/*" @change="handleFile" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
        </div>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>
