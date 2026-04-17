<script setup>
import { ref, nextTick, onBeforeUnmount, markRaw } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Cropper from 'cropperjs'
import 'cropperjs/dist/cropper.css'

const props = defineProps({ settings: Object })

// Build flat settings array for text fields
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

// File upload keys
const fileKeys = ['company_logo', 'company_favicon']
const isFileField = (key) => fileKeys.includes(key)

const getSettingValue = (key) => {
    const s = allSettings.find(s => s.key === key)
    return s ? s.value : null
}

// --- File field config ---
const fileFieldConfig = {
    company_logo: {
        label: 'Company Logo',
        hint: 'Recommended: 200×60px, PNG or SVG with transparent background',
        accept: 'image/png,image/svg+xml,image/jpeg,image/webp',
        previewClass: 'h-16 max-w-[200px] object-contain',
        cropAspectRatio: NaN,
        cropWidth: 400,
        cropHeight: 120,
    },
    company_favicon: {
        label: 'Company Favicon',
        hint: 'Recommended: 32×32px or 64×64px, PNG or ICO',
        accept: 'image/png,image/x-icon,image/vnd.microsoft.icon,image/svg+xml',
        previewClass: 'h-10 w-10 object-contain',
        cropAspectRatio: 1,
        cropWidth: 128,
        cropHeight: 128,
    },
}

// --- Drag & Drop ---
const dragOver = ref({})
const uploading = ref({})
const uploadProgress = ref({})

const setDragOver = (key, value) => {
    dragOver.value[key] = value
}

const handleDrop = (e, key) => {
    e.preventDefault()
    setDragOver(key, false)
    const file = e.dataTransfer.files[0]
    if (file && file.type.startsWith('image/')) {
        openCropper(key, file)
    }
}

const handleFileSelect = (e, key) => {
    const file = e.target.files[0]
    if (file) {
        openCropper(key, file)
    }
    e.target.value = ''
}

// --- Cropper Modal ---
const showCropModal = ref(false)
const cropImageSrc = ref(null)
const cropKey = ref(null)
const cropperInstance = ref(null)
const cropImageEl = ref(null)
const cropLoading = ref(false)

const openCropper = (key, file) => {
    // SVG / ICO skip cropping
    if (file.type === 'image/svg+xml' || file.type === 'image/x-icon' || file.type === 'image/vnd.microsoft.icon') {
        uploadFile(key, file)
        return
    }

    cropKey.value = key
    cropImageSrc.value = URL.createObjectURL(file)
    showCropModal.value = true

    nextTick(() => {
        initCropper()
    })
}

const initCropper = () => {
    destroyCropper()

    nextTick(() => {
        if (!cropImageEl.value) return

        const config = fileFieldConfig[cropKey.value]
        cropperInstance.value = new Cropper(cropImageEl.value, {
            aspectRatio: config?.cropAspectRatio ?? NaN,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.9,
            responsive: true,
            restore: false,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            background: true,
        })
    })
}

const destroyCropper = () => {
    if (cropperInstance.value) {
        cropperInstance.value.destroy()
        cropperInstance.value = null
    }
}

const closeCropper = () => {
    destroyCropper()
    if (cropImageSrc.value) {
        URL.revokeObjectURL(cropImageSrc.value)
    }
    showCropModal.value = false
    cropImageSrc.value = null
    cropKey.value = null
}

const applyCrop = () => {
    if (!cropperInstance.value || !cropKey.value) return

    cropLoading.value = true
    const config = fileFieldConfig[cropKey.value]

    const canvas = cropperInstance.value.getCroppedCanvas({
        width: config?.cropWidth || 400,
        height: config?.cropHeight || 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    })

    canvas.toBlob((blob) => {
        const file = new File([blob], `${cropKey.value}.png`, { type: 'image/png' })
        const key = cropKey.value
        closeCropper()
        uploadFile(key, file)
        cropLoading.value = false
    }, 'image/png', 1)
}

const uploadWithoutCrop = () => {
    if (!cropImageSrc.value || !cropKey.value) return

    fetch(cropImageSrc.value)
        .then(r => r.blob())
        .then(blob => {
            const file = new File([blob], `${cropKey.value}.png`, { type: 'image/png' })
            const key = cropKey.value
            closeCropper()
            uploadFile(key, file)
        })
}

// Cropper toolbar
const cropperRotateLeft = () => cropperInstance.value?.rotate(-90)
const cropperRotateRight = () => cropperInstance.value?.rotate(90)
const cropperFlipH = () => {
    const data = cropperInstance.value?.getImageData()
    cropperInstance.value?.scaleX(data?.scaleX === -1 ? 1 : -1)
}
const cropperFlipV = () => {
    const data = cropperInstance.value?.getImageData()
    cropperInstance.value?.scaleY(data?.scaleY === -1 ? 1 : -1)
}
const cropperReset = () => cropperInstance.value?.reset()
const cropperZoomIn = () => cropperInstance.value?.zoom(0.1)
const cropperZoomOut = () => cropperInstance.value?.zoom(-0.1)

const setAspectRatio = (ratio) => {
    cropperInstance.value?.setAspectRatio(ratio)
}

onBeforeUnmount(() => {
    destroyCropper()
})

// --- Upload / Remove ---
const uploadFile = (key, file) => {
    uploading.value[key] = true
    uploadProgress.value[key] = 0

    const formData = new FormData()
    formData.append('file', file)
    formData.append('key', key)

    router.post('/admin/settings/upload-file', formData, {
        forceFormData: true,
        onProgress: (progress) => {
            uploadProgress.value[key] = Math.round(progress.percentage)
        },
        onFinish: () => {
            uploading.value[key] = false
            uploadProgress.value[key] = 0
        },
        preserveScroll: true,
    })
}

const removeFile = (key) => {
    if (!confirm('Remove this image?')) return
    router.post('/admin/settings/remove-file', { key }, { preserveScroll: true })
}

const getImageUrl = (value) => {
    if (!value) return null
    if (value.startsWith('http')) return value
    return `/storage/${value}`
}
</script>

<template>
    <AdminLayout>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Settings</h1>

        <form @submit.prevent="submit" class="space-y-6 max-w-3xl">
            <div v-for="group in groups" :key="group" class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 capitalize">{{ groupLabels[group] || group }}</h3>
                <div class="space-y-4">
                    <template v-for="setting in getGroupSettings(group)" :key="setting.key">

                        <!-- ── File Upload Fields (Logo / Favicon) ── -->
                        <div v-if="isFileField(setting.key)" class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ fileFieldConfig[setting.key]?.label || formatLabel(setting.key) }}
                            </label>

                            <!-- Current Image Preview + Remove -->
                            <div v-if="getSettingValue(setting.key)" class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="shrink-0 bg-white rounded-lg border border-gray-100 p-2 flex items-center justify-center"
                                     :class="setting.key === 'company_favicon' ? 'w-16 h-16' : 'w-auto h-20'">
                                    <img
                                        :src="getImageUrl(getSettingValue(setting.key))"
                                        :alt="setting.key"
                                        :class="fileFieldConfig[setting.key]?.previewClass"
                                        class="rounded"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-600 truncate">{{ getSettingValue(setting.key) }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Click below to replace, or remove</p>
                                </div>
                                <button
                                    type="button"
                                    @click="removeFile(setting.key)"
                                    class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-md transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>

                            <!-- Drop Zone -->
                            <label
                                :for="`file-${setting.key}`"
                                class="relative block cursor-pointer rounded-lg border-2 border-dashed transition-all duration-200"
                                :class="[
                                    dragOver[setting.key]
                                        ? 'border-indigo-400 bg-indigo-50 scale-[1.01]'
                                        : 'border-gray-300 hover:border-indigo-300 hover:bg-gray-50',
                                    uploading[setting.key] ? 'pointer-events-none opacity-70' : ''
                                ]"
                                @dragover.prevent="setDragOver(setting.key, true)"
                                @dragleave.prevent="setDragOver(setting.key, false)"
                                @drop="handleDrop($event, setting.key)"
                            >
                                <div class="flex flex-col items-center justify-center py-6 px-4">
                                    <div v-if="uploading[setting.key]" class="mb-3">
                                        <svg class="w-8 h-8 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                        </svg>
                                    </div>
                                    <div v-else class="mb-3 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>

                                    <p v-if="uploading[setting.key]" class="text-sm font-medium text-indigo-600">
                                        Uploading... {{ uploadProgress[setting.key] || 0 }}%
                                    </p>
                                    <template v-else>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium text-indigo-600 hover:text-indigo-500">Click to upload</span>
                                            or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ fileFieldConfig[setting.key]?.hint }}
                                        </p>
                                    </template>

                                    <div v-if="uploading[setting.key]" class="w-full max-w-xs mt-3 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-300" :style="{ width: (uploadProgress[setting.key] || 0) + '%' }"/>
                                    </div>
                                </div>

                                <input
                                    :id="`file-${setting.key}`"
                                    type="file"
                                    :accept="fileFieldConfig[setting.key]?.accept || 'image/*'"
                                    class="sr-only"
                                    @change="handleFileSelect($event, setting.key)"
                                />
                            </label>
                        </div>

                        <!-- ── Regular Text Fields ── -->
                        <div v-else>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ formatLabel(setting.key) }}</label>
                            <textarea
                                v-if="setting.key.includes('description') || setting.key.includes('embed')"
                                v-model="setting.value"
                                rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            />
                            <input
                                v-else
                                v-model="setting.value"
                                type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            />
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                    Save Settings
                </button>
            </div>
        </form>

        <!-- ══════════════════════════════════════════════════ -->
        <!-- ── Crop Modal ──────────────────────────────────── -->
        <!-- ══════════════════════════════════════════════════ -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showCropModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeCropper" />

                    <!-- Modal -->
                    <Transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95 translate-y-2"
                        enter-to-class="opacity-100 scale-100 translate-y-0"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div v-if="showCropModal" class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">

                            <!-- Header -->
                            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">
                                        Crop {{ fileFieldConfig[cropKey]?.label || 'Image' }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-0.5">Adjust the crop area, then click Apply</p>
                                </div>
                                <button type="button" @click="closeCropper" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Cropper Area -->
                            <div class="flex-1 overflow-hidden bg-gray-900 relative" style="min-height: 350px; max-height: 55vh;">
                                <img
                                    ref="cropImageEl"
                                    :src="cropImageSrc"
                                    class="block max-w-full"
                                    style="display: block; max-width: 100%;"
                                />
                            </div>

                            <!-- Toolbar -->
                            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <!-- Left: Tools -->
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="cropperZoomIn" title="Zoom In"
                                            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-white transition-colors">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="cropperZoomOut" title="Zoom Out"
                                            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-white transition-colors">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/>
                                            </svg>
                                        </button>

                                        <div class="w-px h-5 bg-gray-200 mx-1" />

                                        <button type="button" @click="cropperRotateLeft" title="Rotate Left"
                                            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-white transition-colors">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="cropperRotateRight" title="Rotate Right"
                                            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-white transition-colors">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>

                                        <div class="w-px h-5 bg-gray-200 mx-1" />

                                        <button type="button" @click="cropperFlipH" title="Flip Horizontal"
                                            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-white transition-colors">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="cropperFlipV" title="Flip Vertical"
                                            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-white transition-colors">
                                            <svg class="w-[18px] h-[18px] rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                            </svg>
                                        </button>

                                        <div class="w-px h-5 bg-gray-200 mx-1" />

                                        <button type="button" @click="cropperReset" title="Reset"
                                            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-white transition-colors">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Right: Aspect Ratio Presets -->
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-gray-400 mr-1">Ratio:</span>
                                        <button type="button" @click="setAspectRatio(NaN)"
                                            class="px-2.5 py-1 text-xs font-medium rounded-md bg-white border border-gray-200 text-gray-600 hover:border-indigo-300 hover:text-indigo-600 transition-colors">
                                            Free
                                        </button>
                                        <button type="button" @click="setAspectRatio(1)"
                                            class="px-2.5 py-1 text-xs font-medium rounded-md bg-white border border-gray-200 text-gray-600 hover:border-indigo-300 hover:text-indigo-600 transition-colors">
                                            1:1
                                        </button>
                                        <button type="button" @click="setAspectRatio(16/9)"
                                            class="px-2.5 py-1 text-xs font-medium rounded-md bg-white border border-gray-200 text-gray-600 hover:border-indigo-300 hover:text-indigo-600 transition-colors">
                                            16:9
                                        </button>
                                        <button type="button" @click="setAspectRatio(4/3)"
                                            class="px-2.5 py-1 text-xs font-medium rounded-md bg-white border border-gray-200 text-gray-600 hover:border-indigo-300 hover:text-indigo-600 transition-colors">
                                            4:3
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="flex items-center justify-between px-5 py-3.5 border-t border-gray-100">
                                <button type="button" @click="uploadWithoutCrop"
                                    class="text-sm text-gray-500 hover:text-gray-700 underline underline-offset-2 transition-colors">
                                    Skip crop, upload original
                                </button>
                                <div class="flex items-center gap-2.5">
                                    <button type="button" @click="closeCropper"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="button" @click="applyCrop" :disabled="cropLoading"
                                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                                        <svg v-if="cropLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                        <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Apply & Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>
    </AdminLayout>
</template>

<style>
/* Cropper.js theme overrides */
.cropper-view-box {
    outline: 2px solid rgba(99, 102, 241, 0.75);
    outline-color: rgba(99, 102, 241, 0.75);
}
.cropper-line {
    background-color: rgba(99, 102, 241, 0.5);
}
.cropper-point {
    background-color: rgb(99, 102, 241);
    width: 8px !important;
    height: 8px !important;
    border-radius: 50%;
}
.cropper-modal {
    background-color: rgba(0, 0, 0, 0.6);
}
.cropper-dashed {
    border-color: rgba(99, 102, 241, 0.4);
}
</style>