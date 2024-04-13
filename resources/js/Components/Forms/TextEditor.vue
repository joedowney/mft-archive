<script setup>

import { ref, watch, onBeforeUnmount } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps(['modelValue']);
const emit = defineEmits(['update:modelValue']);
const editorContent = ref(props.modelValue);

const editorToolbar = [
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    ['link'],
];

watch(() => props.modelValue, (newValue) => {
    editorContent.value = newValue
});

watch(editorContent, (newValue) => {
    emit('update:modelValue', newValue);
});

onBeforeUnmount(() => {
    // Clean up any resources or event listeners here
});
</script>

<template>
    <div>
        <quill-editor
            v-model:content="editorContent"
            :toolbar="editorToolbar"
            contentType="html"
            theme="snow"
        />
    </div>
</template>

<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
</style>
