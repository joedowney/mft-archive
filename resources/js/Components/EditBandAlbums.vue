<script setup>
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import {reactive, ref} from "vue";
import {router} from "@inertiajs/vue3";
let props = defineProps(['albums', 'band']);

let form = reactive({
    title: '',
    band_id: props.band.ID
});
let submitting = ref(false);
let errors = ref([]);
let saved = ref(false);
let show_new_album_form = ref(false);

function submit() {
    submitting.value = true;
    errors = ref([]);
    axios.post('/admin/albums', form)
        .then((response) => {
            saved.value = true;
            setTimeout(() => {
                router.visit('/admin/albums/' + response.data)
            }, 1000);
        })
        .catch((e) => errors = e.response.data.errors)
        .then(() => submitting.value = false);
}

</script>

<template>
    <div>
        <SectionHeading>
            Albums
        </SectionHeading>
        <div class="flex gap-3">
            <a href="#" @click="() => show_new_album_form = true">+ New Album</a>
            <form @submit.prevent="submit" class="flex-1 flex gap-3" :class="{'hidden': !show_new_album_form}">
                <TextInput placeholder="Title" v-model="form.title"></TextInput>
                <SubmitButton>
                    <span v-if="submitting">...</span>
                    <span v-else>Save</span>
                </SubmitButton>
                <span class="text-green-600 w-6 ml-4" v-if="saved">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                </span>
            </form>
        </div>
        <div class="">
            <div v-for="album in albums" class="p-3 border-gray-600 flex gap-4 items-center">
                <img :src="album.ImagePath" class="w-8 h-8 rounded" />
                <a :href="'/admin/albums/' + album.ID">{{ album.Title }}</a>
            </div>
        </div>
    </div>
</template>
