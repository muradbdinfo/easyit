<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import TiptapLink from '@tiptap/extension-link'
import TiptapImage from '@tiptap/extension-image'
import { watch } from 'vue'

const props = defineProps({
    modelValue: { type: String, default: '' },
    label: String,
    error: String,
    required: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            heading: { levels: [2, 3, 4] },
        }),
        TiptapLink.configure({ openOnClick: false }),
        TiptapImage,
    ],
    editorProps: {
        attributes: {
            class: 'prose prose-sm sm:prose max-w-none focus:outline-none min-h-[300px] px-4 py-3',
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML())
    },
})

watch(() => props.modelValue, (val) => {
    if (editor.value && val !== editor.value.getHTML()) {
        editor.value.commands.setContent(val, false)
    }
})

const addImage = () => {
    const url = prompt('Image URL:')
    if (url) editor.value.chain().focus().setImage({ src: url }).run()
}

const addLink = () => {
    const url = prompt('Link URL:')
    if (url) editor.value.chain().focus().setLink({ href: url }).run()
}
</script>

<template>
    <div>
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        <div :class="['border rounded-lg overflow-hidden', error ? 'border-red-300' : 'border-gray-300']">
            <!-- Toolbar -->
            <div v-if="editor" class="flex flex-wrap gap-0.5 px-2 py-1.5 bg-gray-50 border-b border-gray-200">
                <button type="button" @click="editor.chain().focus().toggleBold().run()"
                    :class="[editor.isActive('bold') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'p-1.5 rounded text-sm font-bold']" title="Bold">
                    B
                </button>
                <button type="button" @click="editor.chain().focus().toggleItalic().run()"
                    :class="[editor.isActive('italic') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'p-1.5 rounded text-sm italic']" title="Italic">
                    I
                </button>
                <button type="button" @click="editor.chain().focus().toggleStrike().run()"
                    :class="[editor.isActive('strike') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'p-1.5 rounded text-sm line-through']" title="Strikethrough">
                    S
                </button>
                <div class="w-px bg-gray-300 mx-1"></div>
                <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                    :class="[editor.isActive('heading', { level: 2 }) ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs font-bold']" title="Heading 2">
                    H2
                </button>
                <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                    :class="[editor.isActive('heading', { level: 3 }) ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs font-bold']" title="Heading 3">
                    H3
                </button>
                <button type="button" @click="editor.chain().focus().setParagraph().run()"
                    :class="[editor.isActive('paragraph') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs']" title="Paragraph">
                    P
                </button>
                <div class="w-px bg-gray-300 mx-1"></div>
                <button type="button" @click="editor.chain().focus().toggleBulletList().run()"
                    :class="[editor.isActive('bulletList') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs']" title="Bullet List">
                    • List
                </button>
                <button type="button" @click="editor.chain().focus().toggleOrderedList().run()"
                    :class="[editor.isActive('orderedList') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs']" title="Numbered List">
                    1. List
                </button>
                <button type="button" @click="editor.chain().focus().toggleBlockquote().run()"
                    :class="[editor.isActive('blockquote') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs']" title="Quote">
                    " Quote
                </button>
                <div class="w-px bg-gray-300 mx-1"></div>
                <button type="button" @click="addLink"
                    :class="[editor.isActive('link') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs']" title="Link">
                    🔗 Link
                </button>
                <button type="button" @click="addImage" class="text-gray-600 hover:bg-gray-100 px-1.5 py-1 rounded text-xs" title="Image">
                    🖼 Image
                </button>
                <button type="button" @click="editor.chain().focus().setHorizontalRule().run()" class="text-gray-600 hover:bg-gray-100 px-1.5 py-1 rounded text-xs" title="Divider">
                    ― Line
                </button>
                <div class="w-px bg-gray-300 mx-1"></div>
                <button type="button" @click="editor.chain().focus().toggleCode().run()"
                    :class="[editor.isActive('code') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs font-mono']" title="Inline Code">
                    &lt;/&gt;
                </button>
                <button type="button" @click="editor.chain().focus().toggleCodeBlock().run()"
                    :class="[editor.isActive('codeBlock') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-100', 'px-1.5 py-1 rounded text-xs']" title="Code Block">
                    Code
                </button>
                <div class="flex-1"></div>
                <button type="button" @click="editor.chain().focus().undo().run()" class="text-gray-400 hover:text-gray-600 px-1.5 py-1 rounded text-xs" title="Undo">↩</button>
                <button type="button" @click="editor.chain().focus().redo().run()" class="text-gray-400 hover:text-gray-600 px-1.5 py-1 rounded text-xs" title="Redo">↪</button>
            </div>
            <!-- Editor -->
            <EditorContent :editor="editor" class="bg-white" />
        </div>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<style>
.ProseMirror {
    min-height: 300px;
}
.ProseMirror:focus {
    outline: none;
}
.ProseMirror h2 { font-size: 1.5em; font-weight: 700; margin: 1em 0 0.5em; }
.ProseMirror h3 { font-size: 1.25em; font-weight: 600; margin: 0.8em 0 0.4em; }
.ProseMirror h4 { font-size: 1.1em; font-weight: 600; margin: 0.6em 0 0.3em; }
.ProseMirror p { margin: 0.5em 0; }
.ProseMirror ul { list-style: disc; padding-left: 1.5em; margin: 0.5em 0; }
.ProseMirror ol { list-style: decimal; padding-left: 1.5em; margin: 0.5em 0; }
.ProseMirror blockquote { border-left: 3px solid #d1d5db; padding-left: 1em; color: #6b7280; margin: 0.5em 0; }
.ProseMirror code { background: #f3f4f6; padding: 0.15em 0.3em; border-radius: 3px; font-size: 0.9em; }
.ProseMirror pre { background: #1f2937; color: #e5e7eb; padding: 1em; border-radius: 0.5em; overflow-x: auto; margin: 0.5em 0; }
.ProseMirror pre code { background: none; padding: 0; color: inherit; }
.ProseMirror img { max-width: 100%; border-radius: 0.5em; margin: 1em 0; }
.ProseMirror a { color: #4f46e5; text-decoration: underline; }
.ProseMirror hr { border: none; border-top: 2px solid #e5e7eb; margin: 1.5em 0; }
</style>
