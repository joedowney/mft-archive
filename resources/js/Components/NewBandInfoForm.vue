<script setup>

import Label from "@/Components/Forms/Label.vue";
import TextEditor from "@/Components/Forms/TextEditor.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import {reactive, ref} from "vue";
import {router} from "@inertiajs/vue3";

let props = defineProps(['cities']);

let form = reactive({
    name: '',
    members: '',
    description: '',
    city: null
});
let submitting = ref(false);
let errors = ref([]);
let saved = ref(false);

function submit() {
    submitting.value = true;
    errors = ref([]);
    axios.post('/admin/bands/new', form)
        .then((response) => {
            saved.value = true;
            setTimeout(() => {
                console.log(response)
                router.visit('/admin/bands/' + response.data)
            }, 1000);
        })
        .catch((e) => errors = e.response.data.errors)
        .then(() => submitting.value = false);
}
</script>

<template>
    <form @submit.prevent="submit">
        <SectionHeading>
            Band info
        </SectionHeading>

        <div class="mb-8">
            <Label for="band_name">Band Name</Label>
            <TextInput v-model="form.name" id="band_name"></TextInput>
            <p v-if="errors['name']" class="text-red-600">{{ errors['name'][0] }}</p>
        </div>

        <div class="mb-8">
            <Label for="members">Members</Label>
            <TextInput v-model="form.members" id="members" />
            <p v-if="errors['members']" class="text-red-600">{{ errors['members'][0] }}</p>
        </div>

        <div class="mb-8">
            <Label for="city">City</Label>
            <select v-model="form.city" class="block rounded w-full text-gray-800">
                <option v-for="city in cities" :value="city.ID">{{ city.City }}</option>
            </select>
            <p v-if="errors['city']" class="text-red-600">{{ errors['city'][0] }}</p>
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
