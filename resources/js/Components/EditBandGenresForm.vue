<script setup>
import {onMounted, ref} from "vue";
import VueMultiselect from 'vue-multiselect';
import SectionHeading from "@/Components/Forms/SectionHeading.vue";
import 'vue-multiselect/dist/vue-multiselect.css';
import SubmitButton from "@/Components/Forms/SubmitButton.vue";

let props = defineProps(['genres', 'band']);
let selected = ref([]);
let saved = ref(false);
let submitting = ref(false);

onMounted(() => {
    let genre_ids = props.band.GenreID.split(',');
    selected.value = props.genres.filter((g) => {
        return genre_ids.includes('' + g.ID);
    });
});

function submit() {
    submitting.value = true;
    let data = {
        genres: selected.value.map((g) => '' + g.ID)
    }
    axios.post('/admin/bands/' + props.band.ID + '/genres', data)
        .then(() => {
            saved.value = true;
            submitting.value = false;
            setTimeout(() => saved.value = false, 2000);
        });
}


</script>

<template>
    <form @submit.prevent="submit">
        <SectionHeading>
            Genres
        </SectionHeading>

        <div class="flex gap-4">
            <VueMultiselect
                :options="genres"
                :multiple="true"
                :searchable="true"
                label="Name"
                trackBy="ID"
                v-model="selected"
            ></VueMultiselect>
            <div class="flex items-center">
                <SubmitButton :disabled="submitting" class="text-nowrap">
                    <span v-if="submitting">Saving ...</span>
                    <span v-else>Save</span>
                </SubmitButton>
                <span class="text-green-600 w-6 ml-4" v-if="saved">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#63E6BE" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                </span>
            </div>
        </div>
    </form>
</template>
