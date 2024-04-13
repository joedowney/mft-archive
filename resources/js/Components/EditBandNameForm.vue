<script setup>
import Label from "@/Components/Forms/Label.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
import { reactive, ref } from "vue";

let props = defineProps(['band']);

let form = reactive({
    name: props.band.Name,
    url: props.band.URL,
    alpha: props.band.Alpha
});
let submitting = ref(false);
let errors = ref([]);
let saved = ref(false);

function submit() {
    submitting.value = true;
    errors = ref([]);
    axios.post('/admin/bands/' + props.band.ID + '/name', form)
        .then(() => {
            saved.value = true;
            setTimeout(() => saved.value = false, 2000);
        })
        .catch((e) => errors = e.response.data.errors)
        .then(() => submitting.value = false);
}

</script>

<template>

    <form @submit.prevent="submit">

        <SectionHeading>
            Name and sorting
        </SectionHeading>

        <div class="mb-8">
            <Label for="name">Name</Label>
            <TextInput v-model="form.name" id="name" />
            <p v-if="errors['name']" class="text-red-600">{{ errors['name'][0] }}</p>
        </div>

        <div class="mb-8">
            <Label for="slug">Slug</Label>
            <TextInput v-model="form.url" id="slug" />
            <p v-if="errors['url']" class="text-red-600">{{ errors['url'][0] }}</p>
        </div>

        <div class="mb-8">
            <Label for="alpha">Alphabetical sort</Label>
            <TextInput v-model="form.alpha" id="alpha" />
            <p v-if="errors['alpha']" class="text-red-600">{{ errors['alpha'][0] }}</p>
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
