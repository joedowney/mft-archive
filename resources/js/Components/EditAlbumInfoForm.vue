<script setup>
import { reactive, ref } from "vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import Label from "@/Components/Forms/Label.vue";
import TextEditor from "@/Components/Forms/TextEditor.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import SectionHeading from "@/Components/Forms/SectionHeading.vue";

let props = defineProps(['album'])

let form = reactive({
    title: props.album.Title,
    description: props.album.Description
});

let submitting = ref(false);
let errors = ref([]);
let saved = ref(false);

let submit = () => {
    submitting.value = true;
    errors = ref([]);
    axios.post('/admin/albums/' + props.album.ID, form)
        .then(() => {
            saved.value = true;
            setTimeout(() => saved.value = false, 2000);
        })
        .catch((e) => errors = e.response.data.errors)
        .then(() => submitting.value = false);
};

</script>

<template>
    <form @submit.prevent="submit">

        <SectionHeading>
            Info
        </SectionHeading>
        <div class="mb-8">
            <Label for="name">Title</Label>
            <TextInput v-model="form.title" id="name" />
            <p v-if="errors['title']" class="text-red-600">{{ errors['title'][0] }}</p>
        </div>
        <div class="mb-8">
            <Label for="description">Description</Label>
            <TextEditor v-model="form.description"></TextEditor>
            <p v-if="errors['description']" class="text-red-600">{{ errors['description'][0] }}</p>
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
</template>
