<script setup>
import {Head, router} from '@inertiajs/vue3';
import Page from "@/Components/Page.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import Label from "@/Components/Forms/Label.vue";
import {reactive, ref} from "vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
defineOptions({ layout:AdminLayout });
let props = defineProps(['album']);

let form = reactive({
    album_id: props.album.ID,
    title: ''
});

let submitting = ref(false);
let errors = ref([]);
let saved = ref(false);
let file = ref(null);

let submit = () => {
    let data = new FormData();
    data.append('album_id', form.album_id);
    data.append('title', form.title);
    data.append('file', file.value.files[0]);

    axios.post('/admin/songs', data)
        .then((response) => {
            router.visit('/admin/albums/' + props.album.ID);
        });
};

</script>

<template>

    <Head :title="'New Band'"></Head>
    <Page>
        <h1>New song</h1>

        <form @submit.prevent="submit">
            <div class="mb-8">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">Upload file</label>
                <input
                    class="block w-full border rounded-lg cursor-pointer bg-gray-50 text-gray-400 focus:outline-none dark:bg-gray-700 border-gray-600 placeholder-gray-400"
                    type="file"
                    id="file"
                    ref="file"
                    accept="audio/*"
                />
            </div>
            <div class="mb-8">
                <Label for="title">Title</Label>
                <TextInput v-model="form.title" id="name" />
                <p v-if="errors['title']" class="text-red-600">{{ errors['title'][0] }}</p>
            </div>
            <div class="mb-8 flex items-center">
                <SubmitButton :disabled="submitting">
                    <span v-if="submitting">Saving ...</span>
                    <span v-else>Save</span>
                </SubmitButton>
                <span class="text-green-600 w-6 ml-4" v-if="saved">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                </span>
            </div>
        </form>
    </Page>
</template>
