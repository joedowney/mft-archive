<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Write something...'
    }
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit,
    ],
    editorProps: {
        attributes: {
            class: 'prose prose-sm focus:outline-none max-w-none',
        }
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML())
    }
})

// Keep editor content in sync with v-model
watch(() => props.modelValue, (newValue) => {
    const htmlFromEditor = editor.value?.getHTML()
    if (newValue !== htmlFromEditor) {
        editor.value?.commands.setContent(newValue, false)
    }
})
</script>

<template>
    <div class="border rounded-md">
        <!-- Basic toolbar -->
        <div class="border-b px-3 py-2 flex gap-2">
            <button
                @click="editor?.chain().focus().toggleBold().run()"
                :class="{ 'bg-gray-200': editor?.isActive('bold') }"
                class="p-1 rounded hover:bg-gray-100"
                type="button"
            >
                Bold
            </button>
            <button
                @click="editor?.chain().focus().toggleItalic().run()"
                :class="{ 'bg-gray-200': editor?.isActive('italic') }"
                class="p-1 rounded hover:bg-gray-100"
                type="button"
            >
                Italic
            </button>
            <button
                @click="editor?.chain().focus().toggleBulletList().run()"
                :class="{ 'bg-gray-200': editor?.isActive('bulletList') }"
                class="p-1 rounded hover:bg-gray-100"
                type="button"
            >
                Bullet List
            </button>
        </div>

        <!-- Editor content area -->
        <div class="px-3 py-2 min-h-[150px]">
            <editor-content :editor="editor" />
        </div>
    </div>
</template>

<style>
/* Base editor styles */
.ProseMirror {
    min-height: 100px;
    outline: none;
}

.ProseMirror p.is-editor-empty:first-child::before {
    color: #adb5bd;
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}
</style>
